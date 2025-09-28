<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;  
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Response;
use Maatwebsite\Excel\Facades\Excel;

use TCPDF;


class DeductionApiController extends Controller
{
    private function authenticate()
    {
        $response = Http::asForm()->post('http://identityserver.mof.go.tz/connect/token', [
            'client_id' => 'URA',
            'client_secret' => 'apisecret',
            'grant_type' => 'client_credentials',
        ]);
    
        // Convert the response to JSON
        $responseData = $response->json();
    
        // Check if the response contains an 'access_token'
        if (!isset($responseData['access_token'])) {
            \Log::error('Failed to retrieve access token', [
                'response' => $responseData,  // Log the whole response to see what's wrong
                'status' => $response->status(),
                'body' => $response->body()  // This shows the raw response body
            ]);
            abort(500, 'Failed to authenticate with the API. Please check logs.');
        }
    
        return $responseData['access_token'];
    }

    public function showImportForm()
    {
        return view('deductions.import_form');
    }

    /**
     * Import all deduction details for a given checkDate.
     *
     * @param Request $request (Optional: date filter in YYYY-MM-DD format)
     * @return \Illuminate\Http\JsonResponse
     */
    public function importAllDeductions(Request $request)
    {
        // Use the checkDate from the request or default to a known date.
        $date = $request->input('date');

        $token = $this->authenticate();
        $pageSize = 10000;
        $currentPage = 1;
        $totalPages = 1; // will be updated after the header call
        $totalInserted = 0;

        // First, get the header/summary to know total pages
        $headerResponse = Http::timeout(7200)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('http://gsppapi.mof.go.tz/api/deductions/getdeductionheader', [
            'checkDate' => $date,
            'pageSize'  => $pageSize,
        ]);

        // Check for errors in the header response
        if ($headerResponse->failed()) {
            \Log::error('Failed to retrieve deduction header', [
                'status' => $headerResponse->status(),
                'body'   => $headerResponse->body(),
            ]);
            return response()->json(['error' => 'Failed to retrieve header data'], 500);
        }

        $headerResult = $headerResponse->json();
        if (isset($headerResult['totalPages'])) {
            $totalPages = $headerResult['totalPages'];
            \Log::info("Deduction header indicates totalPages: {$totalPages}", []);
        } else {
            \Log::warning("No totalPages info found in deduction header response.", []);
        }

        // Now loop through each page of deduction details
        do {
            // \Log::info("Processing page {$currentPage}", []);

            $params = [
                'pageNo'   => $currentPage,
                'pageSize' => $pageSize,
                'checkDate'=> $date,
            ];

            $response = Http::timeout(7200)->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('http://gsppapi.mof.go.tz/api/deductions/getdeductiondetails', $params);

            // Check for errors in the details request
            if ($response->failed()) {
                \Log::error("API request failed for page {$currentPage}", [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                $currentPage++;
                continue; // Skip to the next page
            }

            $result = $response->json();
            $deductions = $result ?? [];

            // Process the deductions
            foreach ($deductions as $record) {
                try {
                    DB::table('deduction_details')->insert([
                        'nationalId'       => $record['nationalId'] ?? null,
                        'checkNumber'      => $record['checkNumber'] ?? null,
                        'voteCode'         => $record['voteCode'] ?? null,
                        'voteName'         => $record['voteName'] ?? null,
                        'deptCode'         => $record['deptCode'] ?? null,
                        'deptName'         => $record['deptName'] ?? null,
                        'firstName'        => $record['firstName'] ?? null,
                        'middleName'       => $record['middleName'] ?? null,
                        'lastName'         => $record['lastName'] ?? null,
                        'deductionCode'    => $record['deductionCode'] ?? null,
                        'deductionDesc'    => $record['deductionDesc'] ?? null,
                        'deductionAmount'  => $record['deductionAmount'] ?? 0,
                        'balanceAmount'    => $record['balanceAmount'] ?? 0,
                        'monthlySalary'    => $record['monthlySalary'] ?? 0,
                        'fundingSource'    => $record['fundingSource'] ?? null,
                        'checkDate'        => isset($record['checkDate'])
                                               ? Carbon::parse($record['checkDate'])
                                               : null,
                        'created_at'       => Carbon::now(),
                        'updated_at'       => Carbon::now(),
                    ]);
                    $totalInserted++;
                } catch (\Exception $e) {
                    \Log::error('Failed to insert deduction record', [
                        'record' => $record,
                        'error'  => $e->getMessage(),
                    ]);
                }
            }

            $currentPage++; // Move to the next page
        } while ($currentPage <= $totalPages);

        return response()->json([
            'message'        => 'Import completed',
            'pagesProcessed' => $totalPages,
            'totalInserted'  => $totalInserted,
        ]);
    }


    // public function viewContributions(Request $request)
    // {
    //     // Retrieve search parameters from the query string
    //     $date        = $request->input('date', '2025-01-31'); // default date
    //     $nationalId  = $request->input('nationalId', '');
    //     $checkNumber = $request->input('checkNumber', '');
    
    //     // Build query: Only contribution records (deductionCode exactly "667")
    //     $query = DB::table('deduction_details')
    //                 ->where('deductionCode', '=', '667');
    
    //     // Filter by checkDate if provided
    //     if (!empty($date)) {
    //         $query->whereDate('checkDate', $date);
    //     }
    
    //     // Filter by nationalId if provided
    //     if (!empty($nationalId)) {
    //         $query->where('nationalId', 'like', "%{$nationalId}%");
    //     }
    
    //     // Filter by checkNumber if provided; for checkNumber, we assume exact match.
    //     if (!empty($checkNumber)) {
    //         $query->where('checkNumber', '=', $checkNumber);
    //     }
    
    //     // Paginate results; adjust per page count as needed.
    //     $contributions = $query->paginate(100);
    
    //     return view('deductions.contributions', compact('contributions', 'date', 'nationalId', 'checkNumber'));
    // }
    
    // public function viewContributions(Request $request)
    // {
    //     // Retrieve search parameters from the query string
    //     $date        = $request->input('date', '2025-01-31'); // default date
    //     $nationalId  = $request->input('nationalId', '');
    //     $checkNumber = $request->input('checkNumber', '');
    
    //     // Build a query for contribution records (deductionCode exactly "667")
    //     $query = DB::table('deduction_details')
    //                 ->select(
    //                     'nationalId',
    //                     'firstName',
    //                     'middleName',
    //                     'lastName',
    //                     'checkNumber',
    //                     'monthlySalary',
    //                     'voteCode',
    //                     'deductionAmount',
    //                     'deptName'
    //                 )
    //                 ->where('deductionCode', '=', '667');
    
    //     // Filter by checkDate if provided
    //     if (!empty($date)) {
    //         $query->whereDate('checkDate', $date);
    //     }
    
    //     // Filter by nationalId if provided
    //     if (!empty($nationalId)) {
    //         $query->where('nationalId', 'like', "%{$nationalId}%");
    //     }
    
    //     // Filter by checkNumber if provided (exact match)
    //     if (!empty($checkNumber)) {
    //         $query->where('checkNumber', '=', $checkNumber);
    //     }
    
    //     // Ensure uniqueness by grouping on the selected fields
    //     $query->groupBy(
    //         'nationalId',
    //         'firstName',
    //         'middleName',
    //         'lastName',
    //         'checkNumber',
    //         'monthlySalary',
    //         'voteCode',
    //         'deductionAmount',
    //         'deptName'
    //     );
    
    //     // Paginate the results (e.g., 100 per page)
    //     $contributions = $query->paginate(100);
    
    //     return view('deductions.contributions', compact('contributions', 'date', 'nationalId', 'checkNumber'));
    // }
    
    public function handleContributions(Request $request)
{
    $date        = $request->input('date', '2025-01-31');
    $checkNumber = $request->input('checkNumber', '');
    $firstName   = $request->input('firstName', '');
    $middleName  = $request->input('middleName', '');
    $lastName    = $request->input('lastName', '');
    $action      = $request->input('action', 'view');

    // Query: Fetch unique records
    $query = DB::table('deduction_details')
        ->selectRaw('DISTINCT nationalId, firstName, middleName, lastName, checkNumber, monthlySalary, voteCode, deductionAmount, deptName,checkDate')
        ->where('deductionCode', '=', '667');

    if (!empty($date)) {
        $query->whereDate('checkDate', $date);
    }
    if (!empty($checkNumber)) {
        $query->where('checkNumber', '=', $checkNumber);
    }
    if (!empty($firstName)) {
        $query->where('firstName', 'like', "%{$firstName}%");
    }
    if (!empty($middleName)) {
        $query->where('middleName', 'like', "%{$middleName}%");
    }
    if (!empty($lastName)) {
        $query->where('lastName', 'like', "%{$lastName}%");
    }

    // Ikiwa action ni "export", tengeneza CSV
    if ($action == 'export') {
        $contributions = $query->get();

        // Jina la file
        $filename = 'contributions_' . date('Y_m_d_H_i_s') . '.csv';

        // Headers ili browser ishushie kama CSV badala ya kuonyesha plain text
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        // Fungua stream ya file
        $callback = function () use ($contributions) {
            $file = fopen('php://output', 'w');

            // Andika headers
            fputcsv($file, ['National ID', 'First Name', 'Middle Name', 'Last Name', 'Check Number', 'Monthly Salary', 'Vote Code', 'Deduction Amount', 'Department Name','checkDate',]);

            // Andika data
            foreach ($contributions as $contribution) {
                fputcsv($file, [
                    $contribution->nationalId,
                    $contribution->firstName,
                    $contribution->middleName,
                    $contribution->lastName,
                    $contribution->checkNumber,
                    $contribution->monthlySalary,
                    $contribution->voteCode,
                    $contribution->deductionAmount,
                    $contribution->deptName,
                    $contribution->checkDate

                    
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Ikiwa action ni "view", tumia pagination
    $contributions = $query->paginate(100);

    return view('deductions.contributions', compact(
        'contributions', 'date', 'checkNumber', 'firstName', 'middleName', 'lastName'
    ));
}


public function listMembers(Request $request)
{
    // Retrieve search parameters from the query string
    $date        = $request->input('date', '2025-01-31'); // default check date
    $checkNumber = $request->input('checkNumber', '');
    $firstName   = $request->input('firstName', '');
    $middleName  = $request->input('middleName', '');
    $lastName    = $request->input('lastName', '');

    // Build a query for URA Saccos Ltd member contributions (deductionCode "667")
    $query = DB::table('deduction_details')
                ->select(
                    'nationalId',
                    'firstName',
                    'middleName',
                    'lastName',
                    'checkNumber',
                    'monthlySalary',
                    'voteCode',
                    'deptName'
                )
                ->where('deductionCode', '=', '667');

    // Filter by checkDate if provided
    if (!empty($date)) {
        $query->whereDate('checkDate', $date);
    }

    // Filter by checkNumber if provided (exact match)
    if (!empty($checkNumber)) {
        $query->where('checkNumber', '=', $checkNumber);
    }

    // Filter by first name if provided (partial match)
    if (!empty($firstName)) {
        $query->where('firstName', 'like', '%' . $firstName . '%');
    }

    // Filter by middle name if provided (partial match)
    if (!empty($middleName)) {
        $query->where('middleName', 'like', '%' . $middleName . '%');
    }

    // Filter by last name if provided (partial match)
    if (!empty($lastName)) {
        $query->where('lastName', 'like', '%' . $lastName . '%');
    }

    // Group by the selected columns to ensure unique member records.
    $query->groupBy(
        'nationalId',
        'firstName',
        'middleName',
        'lastName',
        'checkNumber',
        'monthlySalary',
        'voteCode',
        'deptName'
    );

    // If action is 'export', generate CSV
    if ($request->input('action') == 'export') {
        $members = $query->distinct()->get(); // Fetch unique records

        // Define the CSV filename
        $filename = 'members_' . date('Y_m_d_H_i_s') . '.csv';

        // Set headers to force download as CSV
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        // Open file stream and write CSV
        $callback = function () use ($members) {
            $file = fopen('php://output', 'w');

            // Write CSV headers
            fputcsv($file, ['National ID', 'First Name', 'Middle Name', 'Last Name', 'Check Number', 'Monthly Salary', 'Vote Code', 'Department Name']);

            // Write member data
            foreach ($members as $member) {
                fputcsv($file, [
                    $member->nationalId,
                    $member->firstName,
                    $member->middleName,
                    $member->lastName,
                    $member->checkNumber,
                    $member->monthlySalary,
                    $member->voteCode,
                    $member->deptName
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Paginate the results (e.g., 100 per page)
    $members = $query->distinct()->paginate(100);

    return view('deductions.members_list', compact(
        'members', 
        'date', 
        'checkNumber', 
        'firstName', 
        'middleName', 
        'lastName'
    ));
}

// public function listSalaryLoans(Request $request)
// {
//     // Retrieve search parameters with defaults.
//     $date        = $request->input('date', '2025-01-31'); // default check date
//     $checkNumber = $request->input('checkNumber', '');
//     $firstName   = $request->input('firstName', '');
//     $middleName  = $request->input('middleName', '');
//     $lastName    = $request->input('lastName', '');

//     // Build a query for salary loan records.
//     // Assuming salary loans are identified by deduction codes "769" and "769A".
//     $query = DB::table('deduction_details')
//                 ->select(
//                     'checkNumber',
//                     'firstName',
//                     'middleName',
//                     'lastName',
//                     'voteCode',
//                     'deptName',
//                     'monthlySalary',
//                     'deductionAmount',
//                     'balanceAmount'
//                 )
//                 ->whereIn('deductionCode', ['769', '769A']);

//     // Filter by checkDate if provided.
//     if (!empty($date)) {
//         $query->whereDate('checkDate', $date);
//     }

//     // Filter by checkNumber if provided (exact match).
//     if (!empty($checkNumber)) {
//         $query->where('checkNumber', '=', $checkNumber);
//     }

//     // Filter by first name if provided (partial match).
//     if (!empty($firstName)) {
//         $query->where('firstName', 'like', '%' . $firstName . '%');
//     }

//     // Filter by middle name if provided (partial match).
//     if (!empty($middleName)) {
//         $query->where('middleName', 'like', '%' . $middleName . '%');
//     }

//     // Filter by last name if provided (partial match).
//     if (!empty($lastName)) {
//         $query->where('lastName', 'like', '%' . $lastName . '%');
//     }

//     // Group by the selected fields to ensure unique records.
//     $query->groupBy(
//         'checkNumber',
//         'firstName',
//         'middleName',
//         'lastName',
//         'voteCode',
//         'deptName',
//         'monthlySalary',
//         'deductionAmount',
//         'balanceAmount'
//     );

//     // Paginate the results (e.g., 100 records per page).
//     $salaryLoans = $query->paginate(100);

//     return view('deductions.salary_loans_list', compact(
//         'salaryLoans',
//         'date',
//         'checkNumber',
//         'firstName',
//         'middleName',
//         'lastName'
//     ));
// }
public function listSalaryLoans(Request $request)
{
    // Retrieve search parameters with defaults.
    $date        = $request->input('date', '2025-01-31'); // default check date
    $checkNumber = $request->input('checkNumber', '');
    $firstName   = $request->input('firstName', '');
    $middleName  = $request->input('middleName', '');
    $lastName    = $request->input('lastName', '');

    // Build a query for salary loan records.
    $query = DB::table('deduction_details')
                ->select(
                    'checkNumber',
                    'firstName',
                    'middleName',
                    'lastName',
                    'voteCode',
                    'deptCode',
                    'voteName',
                    'deptName',
                    'monthlySalary',
                    'deductionAmount',
                    'balanceAmount',
                    'deductionCode',
                    'deductionDesc',
                    'checkDate',  // Include checkDate field
                    DB::raw('(balanceAmount / deductionAmount) AS month') // Virtual field for "month"
                )
                ->whereIn('deductionCode', ['769', '769A']);

    // Filter by checkDate if provided.
    if (!empty($date)) {
        $query->whereDate('checkDate', $date);
    }

    // Filter by checkNumber if provided.
    if (!empty($checkNumber)) {
        $query->where('checkNumber', '=', $checkNumber);
    }

    // Filter by first name if provided.
    if (!empty($firstName)) {
        $query->where('firstName', 'like', '%' . $firstName . '%');
    }

    // Filter by middle name if provided.
    if (!empty($middleName)) {
        $query->where('middleName', 'like', '%' . $middleName . '%');
    }

    // Filter by last name if provided.
    if (!empty($lastName)) {
        $query->where('lastName', 'like', '%' . $lastName . '%');
    }

    // Ikiwa action ni "export", tengeneza CSV
    if ($request->input('action') == 'export') {
        $salaryLoans = $query->distinct()->get(); // Fetch unique records

        // Jina la file
        $filename = 'salary_loans_' . date('Y_m_d_H_i_s') . '.csv';

        // Headers ili browser ishushie kama CSV badala ya kuonyesha plain text
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        // Fungua stream ya file
        $callback = function () use ($salaryLoans) {
            $file = fopen('php://output', 'w');

            // Andika headers kwa mpangilio wa requested
            fputcsv($file, [
                'Vote Code',
                'Vote Name',    // Assuming voteName is available in the dataset.
                'Department Code',    // Assuming deptCode is available in the dataset.
                'Department Name',
                'CheckNumber',
                'FirstName',
                'MiddleName',
                'LastName',
                'Date',
                'monthlySalary',
                'Deduction Code', // Assuming deductionCode is available in the dataset.
                'Deduction Description',
                'Deduction Amount',
                'Balance Amount',
                'Month'
            ]);

            // Andika data kwa mpangilio wa requested
            foreach ($salaryLoans as $loan) {
                fputcsv($file, [
                    $loan->voteCode,
                    $loan->voteName ?? '',   
                    $loan->deptCode ?? '',   
                    $loan->deptName,
                    $loan->checkNumber,
                    $loan->firstName,
                    $loan->middleName,
                    $loan->lastName,
                    $loan->checkDate,
                    $loan->monthlySalary,
                    $loan->deductionCode , 
                    $loan->deductionDesc,
                    $loan->deductionAmount,
                    $loan->balanceAmount,
                    $loan->month
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



    // Ikiwa action ni "view", tumia pagination
    $salaryLoans = $query->distinct()->paginate(100); // Fetch unique records

    return view('deductions.salary_loans_list', compact(
        'salaryLoans', 'date', 'checkNumber', 'firstName', 'middleName', 'lastName'
    ));
}

 


//taarifa zaidi  loan salary
public function showSalaryLoanDetails($checkNumber)
{
    // Fetch unique records grouped by year and month but exclude deductionCode 667
    $salaryDetails = DB::table('deduction_details')
        ->select(
            DB::raw('YEAR(checkDate) as year'),
            DB::raw('MONTH(checkDate) as month'),
            'deductionDesc',
            'deductionCode',
            'firstName',
            'middleName',
            'lastName',
            'balanceAmount', // Keep balanceAmount as it is, without SUM
            'deductionAmount' // Keep deductionAmount as it is, without SUM
        )
        ->where('checkNumber', $checkNumber)
        ->where('deductionCode', '!=', 667) // Exclude deductionCode 667
        ->groupBy('year', 'month', 'deductionDesc', 'deductionCode', 'balanceAmount', 'deductionAmount', 'firstName', 'middleName', 'lastName')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'asc')
        ->get();

    // Reformat data into a table structure
    $formattedData = [];
    $deductionTypes = [];

    foreach ($salaryDetails as $detail) {
        $year = $detail->year;
        $month = date("F", mktime(0, 0, 0, $detail->month, 10)); // Convert month number to name
        $deductionType = $detail->deductionDesc;
        $amount = $detail->deductionAmount; // Individual deductionAmount
        $balance = $detail->balanceAmount; // Individual balanceAmount
        $firstName = $detail->firstName;
        $middleName = $detail->middleName;
        $lastName = $detail->lastName;

        // Group data by year and month
        if (!isset($formattedData[$year])) {
            $formattedData[$year] = [];
        }
        if (!isset($formattedData[$year][$month])) {
            $formattedData[$year][$month] = [];
        }

        // Store deduction types uniquely
        if (!in_array($deductionType, $deductionTypes)) {
            $deductionTypes[] = $deductionType;
        }

        // Store data for each deduction and balanceAmount
        $formattedData[$year][$month][$deductionType] = $amount;
        $formattedData[$year][$month][$deductionType . '_balance'] = $balance;
    }

    return view('deductions.salary_details', compact('checkNumber', 'formattedData', 'deductionTypes', 'firstName', 'middleName', 'lastName'));
}







//taarifa zaidi  Member contribution
public function showMemberContribution($checkNumber)
{
    // Fetch unique records grouped by year and month for deductionCode 667
    $salaryDetails = DB::table('deduction_details')
        ->select(
            DB::raw('YEAR(checkDate) as year'),
            DB::raw('MONTH(checkDate) as month'),
            'deductionDesc',
            'deductionCode',
            'firstName',
            'middleName',
            'lastName',
            'balanceAmount',
            'deductionAmount' // Select deductionAmount directly
        )
        ->where('checkNumber', $checkNumber)
        ->where('deductionCode', '=', 667)
        ->groupBy('year', 'month', 'deductionDesc', 'deductionCode', 'balanceAmount', 'deductionAmount','firstName','middleName','lastName') // Group by all selected columns
        ->orderBy('year', 'desc')
        ->orderBy('month', 'asc')
        ->get();

    // Reformat data into a table structure, taking only the first record for each month
    $formattedData = [];
    $deductionTypes = [];

    foreach ($salaryDetails as $detail) {
        $year = $detail->year;
        $month = date("F", mktime(0, 0, 0, $detail->month, 10)); // Convert month number to name
        $deductionType = $detail->deductionDesc;
        $amount = $detail->deductionAmount; // Use the single deductionAmount
        $balance = $detail->balanceAmount;
        $firstName = $detail->firstName;
        $middleName = $detail->middleName;
        $lastName = $detail->lastName;

        // Group data by year and month, only store if not already present for the month
        if (!isset($formattedData[$year])) {
            $formattedData[$year] = [];
        }
        if (!isset($formattedData[$year][$month])) {
            $formattedData[$year][$month] = [
                $deductionType => $amount,
                $deductionType . '_balance' => $balance,
            ];

            // Store deduction types uniquely
            if (!in_array($deductionType, $deductionTypes)) {
                $deductionTypes[] = $deductionType;
            }
        }
    }

    return view('deductions.contributions_details', compact('checkNumber', 'formattedData', 'deductionTypes','firstName','middleName','lastName'));
}




public function exportMemberContributionCsv($checkNumber): StreamedResponse
    {
        // Fetch and format data exactly as in the showMemberContribution function
        $salaryDetails = DB::table('deduction_details')
            ->select(
                DB::raw('YEAR(checkDate) as year'),
                DB::raw('MONTH(checkDate) as month'),
                'deductionDesc',
                'deductionCode',
                'balanceAmount',
                'deductionAmount'
            )
            ->where('checkNumber', $checkNumber)
            ->where('deductionCode', '=', 667)
            ->groupBy('year', 'month', 'deductionDesc', 'deductionCode', 'balanceAmount', 'deductionAmount')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'asc')
            ->get();

        $formattedData = [];
        $deductionTypes = [];

        foreach ($salaryDetails as $detail) {
            $year = $detail->year;
            $month = date("F", mktime(0, 0, 0, $detail->month, 10));
            $deductionType = $detail->deductionDesc;
            $amount = $detail->deductionAmount;
            $balance = $detail->balanceAmount;

            if (!isset($formattedData[$year])) {
                $formattedData[$year] = [];
            }
            if (!isset($formattedData[$year][$month])) {
                $formattedData[$year][$month] = [
                    $deductionType => $amount,
                    $deductionType . '_balance' => $balance,
                ];

                if (!in_array($deductionType, $deductionTypes)) {
                    $deductionTypes[] = $deductionType;
                }
            }
        }

        // Generate the CSV content
        $filename = 'contribution_details_check_' . $checkNumber . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($formattedData, $deductionTypes) {
            $output = fopen('php://output', 'w');

            // Add CSV BOM for UTF-8 encoding in Excel
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add header row
            $headerRow = ['DATE'];
            foreach ($deductionTypes as $type) {
                $headerRow[] = $type;
            }
            fputcsv($output, $headerRow);

            // Add data rows
            foreach ($formattedData as $year => $months) {
                // Add year row
                fputcsv($output, [$year]);
                foreach ($months as $month => $deductions) {
                    $dataRow = [$month];
                    foreach ($deductionTypes as $type) {
                        $dataRow[] = $deductions[$type] ?? '';
                    }
                    fputcsv($output, $dataRow);
                }
            }

            // Add total row (optional, based on your template's totals)
            $columnTotals = array_fill_keys($deductionTypes, 0);
            foreach ($formattedData as $year => $months) {
                foreach ($months as $month => $deductions) {
                    foreach ($deductionTypes as $type) {
                        $columnTotals[$type] += $deductions[$type] ?? 0;
                    }
                }
            }
            $totalRow = ['Total'];
            foreach ($columnTotals as $total) {
                $totalRow[] = number_format($total, 2);
            }
            fputcsv($output, $totalRow);

            fclose($output);
        };

        return new StreamedResponse($callback, 200, $headers);
    }












    public function exportSalaryDetailCsv($checkNumber): StreamedResponse
    {
        // Fetch salary deduction details following the logic of showSalaryLoanDetails
        $salaryDetails = DB::table('deduction_details')
            ->select(
                DB::raw('YEAR(checkDate) as year'),
                DB::raw('MONTH(checkDate) as month'),
                'deductionDesc',
                'deductionCode',
                'balanceAmount',
                'firstName',
                'middleName',
                'lastName',
                'deductionAmount' // Add deductionAmount directly here
            )
            ->where('checkNumber', $checkNumber)
            ->where('deductionCode', '!=', 667) // Exclude deductionCode 667
            ->orderBy('year', 'desc')
            ->orderBy('month', 'asc')
            ->get();
    
        // Reformat data into a table structure
        $formattedData = [];
        $deductionTypes = [];
    
        foreach ($salaryDetails as $detail) {
            $year = $detail->year;
            $month = date("F", mktime(0, 0, 0, $detail->month, 10)); // Convert month number to name
            $deductionType = $detail->deductionDesc;
            $amount = $detail->deductionAmount; // Use the exact deductionAmount value
            $balance = $detail->balanceAmount;
    
            // Group data by year and month
            if (!isset($formattedData[$year])) {
                $formattedData[$year] = [];
            }
            if (!isset($formattedData[$year][$month])) {
                $formattedData[$year][$month] = [];
            }
    
            // Store deduction types uniquely
            if (!in_array($deductionType, $deductionTypes)) {
                $deductionTypes[] = $deductionType;
            }
    
            // Store data for each deduction and balanceAmount
            $formattedData[$year][$month][$deductionType] = $amount;
            $formattedData[$year][$month][$deductionType . '_balance'] = $balance;
        }
    
        // Generate CSV file
        $filename = 'salary_Deduction_details_check_' . $checkNumber . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
    
        $callback = function () use ($formattedData, $deductionTypes) {
            $output = fopen('php://output', 'w');
    
            // Add CSV BOM for UTF-8 encoding in Excel
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
    
            // Add header row
            $headerRow = ['DATE'];
            foreach ($deductionTypes as $type) {
                $headerRow[] = $type . ' DEDUCTION';
                $headerRow[] = $type . ' BALANCE'; // Balance column for each deduction type
            }
            fputcsv($output, $headerRow);
    
            // Initialize total calculations
            $columnTotals = array_fill_keys($deductionTypes, 0);
            $balanceTotals = array_fill_keys($deductionTypes, 0);
    
            // Add data rows
            foreach ($formattedData as $year => $months) {
                fputcsv($output, [$year]); // Add year row
                foreach ($months as $month => $deductions) {
                    $dataRow = [$month];
                    foreach ($deductionTypes as $type) {
                        // Use the exact deductionAmount value without summing it
                        $amount = $deductions[$type] ?? 0;
                        $balance = $deductions[$type . '_balance'] ?? 0;
    
                        // Sum deductionAmount for total calculation
                        $columnTotals[$type] += $amount;
                        $balanceTotals[$type] += $balance;
    
                        // Add amount and balance values
                        $dataRow[] = number_format($amount, 2);
                        $dataRow[] = number_format($balance, 2);
                    }
                    fputcsv($output, $dataRow);
                }
            }
    
            // Add total row (don't show sum for balanceAmount, but show total for deductionAmount)
            $totalRow = ['Total'];
            foreach ($deductionTypes as $type) {
                // Show total for deductionAmount, but dash for balanceAmount
                $totalRow[] = number_format($columnTotals[$type], 2);  // Show total for deductionAmount
                $totalRow[] = 'N/A';  // Dash for balanceAmount
            }
            fputcsv($output, $totalRow);
    
            fclose($output);
        };
    
        return new StreamedResponse($callback, 200, $headers);
    }
    




   

     

    public function exportSalaryDetailPdf($checkNumber): StreamedResponse
    {
        // Fetch salary deduction details following the logic of showSalaryLoanDetails
        $salaryDetails = DB::table('deduction_details')
            ->select(
                DB::raw('YEAR(checkDate) as year'),
                DB::raw('MONTH(checkDate) as month'),
                'deductionDesc',
                'deductionCode',
                'balanceAmount',
                'firstName',
                'middleName',
                'lastName',
                'deductionAmount' // Use exact deductionAmount
            )
            ->where('checkNumber', $checkNumber)
            ->where('deductionCode', '!=', 667) // Exclude deductionCode 667
            ->orderBy('year', 'desc')
            ->orderBy('month', 'asc')
            ->get();
    
        // Reformat data into a table structure
        $formattedData = [];
        $deductionTypes = [];
    
        foreach ($salaryDetails as $detail) {
            $year = $detail->year;
            $month = date("F", mktime(0, 0, 0, $detail->month, 10)); // Convert month number to name
            $deductionType = $detail->deductionDesc;
            $amount = $detail->deductionAmount;
            $balance = $detail->balanceAmount;
            $firstName = $detail->firstName;
            $middleName = $detail->middleName;
            $lastName = $detail->lastName;
    
            // Group data by year and month
            if (!isset($formattedData[$year])) {
                $formattedData[$year] = [];
            }
            if (!isset($formattedData[$year][$month])) {
                $formattedData[$year][$month] = [];
            }
    
            // Store unique deduction types
            if (!in_array($deductionType, $deductionTypes)) {
                $deductionTypes[] = $deductionType;
            }
    
            // Store data for each deduction and balanceAmount
            $formattedData[$year][$month][$deductionType] = $amount;
            $formattedData[$year][$month][$deductionType . '_balance'] = $balance;
        }
    
        // Create a new PDF instance in Landscape mode (A4)
        $pdf = new TCPDF('L', 'mm', 'A4');
    
        // Disable default header and footer to remove the unwanted line at the top
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    
        $pdf->AddPage();
    
        // Set title header
        $pdf->SetFont('Times', 'B', 18);
        // Line 1: Company name
        $pdf->Cell(0, 10, strtoupper("USALAMA WA RAIA SACCOS LTD"), 0, 1, 'C');
        // Line 2: Salary Deduction Details na check number
        $pdf->Cell(0, 10, strtoupper("SALARY DEDUCTION DETAILS - CHECK NUMBER: " . $checkNumber), 0, 1, 'C');
        // Line 3: Full name (first, middle, last)
        $pdf->Cell(0, 10, strtoupper($firstName . " " . $middleName . " " . $lastName), 0, 1, 'C');
        // Line 4: Unaweza kuongeza mstari wa blank hapa kama unavyotaka
        $pdf->Cell(0, 10, '', 0, 1, 'C');
        
        // Set font for table header (smaller size for wrapping)
        $pdf->SetFont('Times', 'B', 12);
    
        // Calculate usable width of the page (page width minus left and right margins)
        $margins = $pdf->getMargins();
        $usableWidth = $pdf->getPageWidth() - $margins['left'] - $margins['right'];
        // One column is for DATE; then two columns per deduction type.
        $totalColumns = count($deductionTypes) * 2 + 1;
        // We'll allocate width for the DATE column fixed at 30 mm,
        // and the rest distributed evenly
        $remainingWidth = $usableWidth - 30;
        $colWidth = $remainingWidth / (count($deductionTypes) * 2);
    
        // Set left margin explicitly for table start (if needed)
        $pdf->SetX($margins['left']);
    
        // Use MultiCell for headers so that long text wraps within cell
        // First header cell: DATE
        $pdf->MultiCell(30, 15, 'DATE', 1, 'C', 0, 0);
        foreach ($deductionTypes as $type) {
            // Deduction header cell
            $pdf->MultiCell($colWidth, 15, $type . "  ", 1, 'C', 0, 0);
            // Balance header cell
            $pdf->MultiCell($colWidth, 15, $type . " BALANCE", 1, 'C', 0, 0);
        }
        $pdf->Ln();
    
        // Set font for data rows
        $pdf->SetFont('Times', '', 12);
    
        // Initialize total calculations
        $columnTotals = array_fill_keys($deductionTypes, 0);
        $balanceTotals = array_fill_keys($deductionTypes, 0);
    
        // Print table rows
        foreach ($formattedData as $year => $months) {
            // Year row spanning across columns
            $pdf->Cell(0, 10, $year, 0, 1, 'L');
            foreach ($months as $month => $deductions) {
                $pdf->Cell(30, 10, $month, 1, 0, 'C');
                foreach ($deductionTypes as $type) {
                    $amount = $deductions[$type] ?? 0;
                    $balance = $deductions[$type . '_balance'] ?? 0;
                    $columnTotals[$type] += $amount;
                    $balanceTotals[$type] += $balance;
                    $pdf->Cell($colWidth, 10, number_format($amount, 2), 1, 0, 'C');
                    $pdf->Cell($colWidth, 10, number_format($balance, 2), 1, 0, 'C');
                }
                $pdf->Ln();
            }
        }
    
        // Add total row: show total for deduction amounts, 'N/A' for balances
        $pdf->Cell(30, 10, 'Total', 1, 0, 'C');
        foreach ($deductionTypes as $type) {
            $pdf->Cell($colWidth, 10, number_format($columnTotals[$type], 2), 1, 0, 'C');
            $pdf->Cell($colWidth, 10, 'N/A', 1, 0, 'C');
        }
        $pdf->Ln();
    
        // Add a signature section at the bottom
        // Leave some vertical space before the signature
        $pdf->Ln(10);
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(0, 10, 'Signature: _______________________________', 0, 1, 'L');
        $pdf->Cell(0, 10, 'Exported on: ' . date("Y-m-d"), 0, 1, 'L');
    
        // Output the PDF to the browser
        return response(
            $pdf->Output('salary_Deduction_details_check_' . $checkNumber . '.pdf', 'I'),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="salary_Deduction_details_check_' . $checkNumber . '.pdf"'
            ]
        );
    }
  
    






    public function exportMemberContributionPdf($checkNumber): StreamedResponse
{
    // Chukua data kama ilivyo katika function ya showMemberContribution
    $salaryDetails = DB::table('deduction_details')
        ->select(
            DB::raw('YEAR(checkDate) as year'),
            DB::raw('MONTH(checkDate) as month'),
            'deductionDesc',
            'deductionCode',
            'balanceAmount',
            'deductionAmount',
            'firstName',
            'middleName',
            'lastName'
        )
        ->where('checkNumber', $checkNumber)
        ->where('deductionCode', '=', 667)
        ->groupBy('year', 'month', 'deductionDesc', 'deductionCode', 'balanceAmount', 'deductionAmount','firstName','middleName','lastName')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'asc')
        ->get();

    $formattedData = [];
    $deductionTypes = [];

    foreach ($salaryDetails as $detail) {
        $year = $detail->year;
        $month = date("F", mktime(0, 0, 0, $detail->month, 10));
        $deductionType = $detail->deductionDesc;
        $amount = $detail->deductionAmount;
        $firstName = $detail->firstName;
        $middleName = $detail->middleName;
        $lastName = $detail->lastName;
        // Tunaweka data kwa kila mwaka na mwezi
        if (!isset($formattedData[$year])) {
            $formattedData[$year] = [];
        }
        if (!isset($formattedData[$year][$month])) {
            $formattedData[$year][$month] = [
                $deductionType => $amount,
            ];
            if (!in_array($deductionType, $deductionTypes)) {
                $deductionTypes[] = $deductionType;
            }
        }
    }

    // Tumia TCPDF kutengeneza PDF
    $pdf = new TCPDF('L', 'mm', 'A4');
    // Zima header na footer za default za TCPDF
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();

    // Kichwa cha PDF
    $pdf->SetFont('Times', 'B', 18);
    // Line 1: Company name
    $pdf->Cell(0, 10, strtoupper("USALAMA WA RAIA SACCOS LTD"), 0, 1, 'C');
    // Line 2: Salary Deduction Details na check number
    $pdf->Cell(0, 10, strtoupper("MEMBER CONTRIBUTION DETAILS  - CHECK NUMBER: " . $checkNumber), 0, 1, 'C');
    // Line 3: Full name (first, middle, last)
    $pdf->Cell(0, 10, strtoupper($firstName . " " . $middleName . " " . $lastName), 0, 1, 'C');
    // Line 4: Unaweza kuongeza mstari wa blank hapa kama unavyotaka
    $pdf->Cell(0, 10, '', 0, 1, 'C');
    // Andaa table header
    $pdf->SetFont('Times', 'B', 12);
    $margins = $pdf->getMargins();
    $usableWidth = $pdf->getPageWidth() - $margins['left'] - $margins['right'];
    // Kiwango cha column ya "DATE" kitakuwa 30 mm
    $fixedWidth = 30;
    // Column za deduction zinaigawanywa sawia kati ya deducitonTypes zote
    $colWidth = ($usableWidth - $fixedWidth) / count($deductionTypes);

    // Chapisha header row ya table: "DATE" na majina ya deduction
    $pdf->Cell($fixedWidth, 10, 'DATE', 1, 0, 'C');
    foreach ($deductionTypes as $type) {
        // Tunatumia MultiCell ili kuruhusu maneno kupindika ndani ya cell
        $pdf->MultiCell($colWidth, 10, $type, 1, 'C', 0, 0);
    }
    $pdf->Ln();

    // Chapisha data za table
    $pdf->SetFont('Times', '', 12);
    $columnTotals = array_fill_keys($deductionTypes, 0);
    foreach ($formattedData as $year => $months) {
        // Chapisha row ya mwaka
        $pdf->Cell(0, 10, $year, 0, 1, 'L');
        foreach ($months as $month => $deductions) {
            $pdf->Cell($fixedWidth, 10, $month, 1, 0, 'C');
            foreach ($deductionTypes as $type) {
                $amount = $deductions[$type] ?? 0;
                $columnTotals[$type] += $amount;
                $pdf->Cell($colWidth, 10, number_format($amount, 2), 1, 0, 'C');
            }
            $pdf->Ln();
        }
    }

    // Chapisha row ya Total
    $pdf->Cell($fixedWidth, 10, 'Total', 1, 0, 'C');
    foreach ($deductionTypes as $type) {
        $pdf->Cell($colWidth, 10, number_format($columnTotals[$type], 2), 1, 0, 'C');
    }
    $pdf->Ln();

    // Chini ya table, ongeza sehemu ya kusaini na tarehe ya export
    $pdf->Ln(10);
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(0, 10, 'Signature: _______________________________', 0, 1, 'L');
    $pdf->Cell(0, 10, 'Exported on: ' . date("Y-m-d"), 0, 1, 'L');

    // Rudisha PDF kama response
    return response(
        $pdf->Output('contribution_details_check_' . $checkNumber . '.pdf', 'I'),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="contribution_details_check_' . $checkNumber . '.pdf"'
        ]
    );
}


}

