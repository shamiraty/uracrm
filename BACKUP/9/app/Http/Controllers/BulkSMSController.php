<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Jobs\SendSmsJob;
use PhpOffice\PhpSpreadsheet\IOFactory; // Assuming PhpSpreadsheet is installed

class BulkSMSController extends Controller
{
    private function sendEnquirySMS($phone, $message)
    {
        $url = 'https://41.59.228.68:8082/api/v1/sendSMS';
        $apiKey = 'xYz123#'; // Your real API key here
        $client = new Client();
        try {
            $response = $client->request('POST', $url, [
                'verify' => false,
                'form_params' => [
                    'msisdn'  => $phone,
                    'message' => $message,
                    'key'     => $apiKey,
                ],
            ]);
            $responseBody = $response->getBody()->getContents();
            \Log::info("SMS sent to {$phone}: " . $responseBody);
            return $responseBody;
        } catch (GuzzleException $e) {
            \Log::error("Failed to send SMS to {$phone}: " . $e->getMessage());
            return null;
        }
    }


    public function showForm()
    {
        return view('sms.bulk-sms', [
            'headers' => null,
            'data' => null,
            'raw_csv' => null,
            'cleanData' => [],
            'problematicData' => [],
            'cleanRawCsv' => null,
        ]);
    }

    public function parseCSV(Request $request)
    {
        $request->validate([
             'csv_file' => 'required|mimes:csv,txt,xlsx,xls|max:30240', // Added xlsx and xls
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();
        $extension = $file->getClientOriginalExtension();

        $rows = [];

        if ($extension === 'csv' || $extension === 'txt') {
            $rows = array_map('str_getcsv', file($filePath));
        } elseif ($extension === 'xlsx' || $extension === 'xls') {
            try {
                // Load the spreadsheet using PhpSpreadsheet
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray(null, true, true, true); // Get all data as an array
            } catch (\Exception $e) {
                return redirect()->route('bulk.sms.form')->with('error', 'Failed to read Excel file: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('bulk.sms.form')->with('error', 'Unsupported file type. Please upload a CSV or Excel file.');
        }

        if (empty($rows) || count($rows) < 2) {
            return redirect()->route('bulk.sms.form')->with('error', 'File must contain at least one row of data (including headers).');
        }

        $headers = array_map('trim', array_values(array_shift($rows))); // Extract headers and re-index
        $allData = array_values($rows); // Re-index the remaining rows

        $cleanData = [];
        $problematicData = [];
        $phoneFieldGuess = null;

        // Try to guess the phone field based on common names
        foreach ($headers as $index => $header) {
            if (stripos($header, 'phone') !== false || stripos($header, 'mobile') !== false || stripos($header, 'msisdn') !== false) {
                $phoneFieldGuess = $header;
                break;
            }
        }

        foreach ($allData as $index => $row) {
            // Re-index row to be numeric for consistent access if it came from Excel's associative array
            $row = array_values($row);

            // Validate that the number of elements in the row matches the number of headers
            if (count($headers) !== count($row)) {
                $problematicData[] = ['data' => $row, 'reason' => 'Row column count mismatch with headers.'];
                continue; // Skip this row to prevent array_combine error
            }

            $rowData = array_combine($headers, $row);
            $isValidRow = true;
            $reason = [];
            $phone = '';

            // Determine the phone column for validation
            $phoneIndex = -1;
            if ($phoneFieldGuess) {
                $phoneIndex = array_search($phoneFieldGuess, $headers);
            } else {
                // Fallback: try to find a column that looks like a phone number (e.g., contains only digits)
                foreach ($row as $colIdx => $cellValue) {
                    if (preg_match('/^\d+$/', trim($cellValue)) && strlen(trim($cellValue)) >= 9) {
                        $phoneIndex = $colIdx;
                        break;
                    }
                }
                // If no numeric column found, default to the last column
                if ($phoneIndex === -1 && count($row) > 0) {
                     $phoneIndex = count($row) - 1;
                }
            }


            if ($phoneIndex === -1 || !isset($row[$phoneIndex])) {
                $problematicData[] = ['data' => $row, 'reason' => 'Missing phone number column or data.'];
                continue; // Skip this row entirely
            }

            $phone = trim($row[$phoneIndex]);
            // Phone validation: must be 12 digits, start with 255, and all digits
            if (empty($phone)) {
                $isValidRow = false;
                $reason[] = 'Empty phone number.';
            } elseif (!preg_match('/^255\d{9}$/', $phone)) {
                $isValidRow = false;
                $reason[] = 'Invalid phone number format (must be 12 digits, starting with 255).';
            }

            if ($isValidRow) {
                $cleanData[] = $row;
            } else {
                $problematicData[] = ['data' => $row, 'reason' => implode('; ', $reason)];
            }
        }

        // Prepare raw CSV data for clean data to be sent in the next step
        $cleanCsvContent = array_merge([$headers], $cleanData);
        $cleanRawCsv = base64_encode(json_encode($cleanCsvContent));


        return view('sms.bulk-sms', [
            'headers' => $headers,
            'cleanData' => $cleanData,
            'problematicData' => $problematicData,
            'raw_csv' => null,
            'cleanRawCsv' => $cleanRawCsv,
        ])->with('success', 'File parsed successfully. Review data below.');
    }

    public function sendBulkSMS(Request $request)
    {
        set_time_limit(21600);
        $request->validate([
            'message_template' => 'required|string',
            'phone_field' => 'required|string',
            'csv_data' => 'required|string',
        ]);
        // Decode and extract clean CSV
        $csv = json_decode(base64_decode($request->csv_data), true);
        $headers = array_map('trim', $csv[0]);
        $rows = array_slice($csv, 1);

        $phoneField = $request->phone_field;
        $phoneIndex = array_search($phoneField, $headers);
        $template = $request->message_template;
        if ($phoneIndex === false) {
            return redirect()->route('bulk.sms.form')->with('error', 'Phone number column not found in CSV headers. Please ensure the selected column exists.');
        }

        $jobsDispatched = 0;
        $failedToDispatch = [];

        foreach ($rows as $index => $row) {
            // IMPORTANT FIX: Ensure the row has the same number of elements as headers
            if (count($headers) !== count($row)) {
                \Log::warning("Skipped row " . ($index + 2) . " during job dispatch due to column count mismatch. Headers: " .
                count($headers) . ", Row: " . count($row));
                $failedToDispatch[] = [
                    'phone' => 'Row ' .
                    ($index + 2),
                    'message' => 'N/A',
                    'reason' => 'Column count mismatch, job not dispatched',
                ];
                continue;
            }

            // Check if the phone number column exists in this specific row
            if (!isset($row[$phoneIndex])) {
                \Log::warning("Skipped row " . ($index + 2) . " during job dispatch due to missing phone number data in the selected column.");
                $failedToDispatch[] = [
                    'phone' => 'Row ' .
                    ($index + 2),
                    'message' => 'N/A',
                    'reason' => 'Missing phone number data, job not dispatched',
                ];
                continue;
            }

            $rowData = array_combine($headers, $row);
            $phone = trim($row[$phoneIndex]);
            if (empty($phone) || !preg_match('/^255\d{9}$/', $phone)) {
                 \Log::warning("Encountered unexpected invalid phone number during job dispatch: {$phone} (Row " . ($index + 2) . ")");
                 $failedToDispatch[] = [
                     'phone' => $phone,
                     'message' => 'N/A',
                     'reason' => 'Invalid phone format after initial validation, job not dispatched',
                 ];
                 continue;
            }

            // Prepare personalized message
            $message = $template;
            foreach ($rowData as $key => $value) {
                $message = str_replace('{' . $key . '}', trim($value ?? ''), $message);
            }

            // Dispatch the SendSmsJob
            try {
                SendSmsJob::dispatch($phone, $message);
                $jobsDispatched++;
            } catch (\Exception $e) {
                \Log::error("Failed to dispatch SMS job for {$phone}: " . $e->getMessage());
                $failedToDispatch[] = [
                    'phone' => $phone,
                    'message' => $message,
                    'reason' => 'Failed to dispatch job: ' .
                    $e->getMessage(),
                ];
            }
        }

        $feedbackMessage = "SMS sending process initiated. {$jobsDispatched} messages are being processed in the background.";
        if (!empty($failedToDispatch)) {
            $feedbackMessage .= " Some SMS jobs failed to be dispatched. Check the 'Problematic Data' section or download the 'Failed SMS Data CSV' for details.";
        } else {
             $feedbackMessage .= " All SMS jobs were successfully dispatched.";
        }


        return redirect()->route('bulk.sms.form')->with([
            'success' => $feedbackMessage,
            'failedSendsSummary' => $failedToDispatch
        ]);
    }

    public function exportProblematicCSV(Request $request)
    {
        $request->validate([
            'problematic_csv_data' => 'required|string',
        ]);
        $data = json_decode(base64_decode($request->problematic_csv_data), true);
        $headers = $data['headers'];
        $problematicRows = $data['data'];

        $fileName = 'problematic_sms_data_' . now()->format('Ymd_His') . '.csv';
        $headersWithReason = array_merge($headers, ['Reason for Issue']);

        $callback = function() use ($problematicRows, $headersWithReason) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headersWithReason);

            foreach ($problematicRows as $row) {
                $rowData = isset($row['data']) ?
                $row['data'] : [];
                $reason = isset($row['reason']) ? $row['reason'] : 'Unknown issue';
                fputcsv($file, array_merge($rowData, [$reason]));
            }
            fclose($file);
        };
        return new StreamedResponse($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function exportFailedSMSCSV(Request $request)
    {
        $request->validate([
            'failed_sms_data' => 'required|string',
        ]);
        $data = json_decode(base64_decode($request->failed_sms_data), true);
        $fileName = 'failed_sms_data_' . now()->format('Ymd_His') . '.csv';
        $headers = ['Phone Number', 'Message Sent', 'Reason for Failure'];

        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row['phone'] ?? 'N/A',
                    $row['message'] ?? 'N/A',
                    $row['reason'] ?? 'Unknown issue'
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}