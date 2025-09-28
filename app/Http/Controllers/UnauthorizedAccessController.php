<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnauthorizedAccess;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class UnauthorizedAccessController extends Controller
{
    /**
     * Show the unauthorized access page - Now uses backend response for security
     * Only accessible through middleware redirect, not direct access
     */
    public function show(Request $request)
    {
        // Note: UnauthorizedAccessGuard middleware already handles access control
        // If we reach here, it means access is valid through middleware redirect

        \Log::info('Valid unauthorized access page shown', [
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        // Clear the token after use (single use)
        session()->forget(['unauthorized_access_token', 'unauthorized_timestamp']);

        // For security purposes, avoid template exposure
        // Return minimal backend-generated response
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Unauthorized.',
                'error_code' => 'FORBIDDEN',
                'timestamp' => now()->toISOString()
            ], 403);
        }

        // Return secure HTML without exposing system structure
        return response($this->getSecureUnauthorizedHtml(), 403)
            ->header('Content-Type', 'text/html')
            ->header('X-Frame-Options', 'DENY')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Referrer-Policy', 'no-referrer')
            ->header('X-XSS-Protection', '1; mode=block');
    }

    /**
     * Generate secure unauthorized access HTML
     */
    private function getSecureUnauthorizedHtml()
    {
        $timestamp = now()->format('Y-m-d H:i:s T');

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 450px;
            width: 90%;
        }
        .error-code {
            font-size: 4rem;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        .title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .message {
            font-size: 1rem;
            color: #7f8c8d;
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background 0.3s ease;
            display: inline-block;
        }
        .btn:hover {
            background: #2980b9;
            text-decoration: none;
            color: white;
        }
        .timestamp {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #bdc3c7;
        }
        .security-notice {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 1rem;
            margin: 1.5rem 0;
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">403</div>
        <div class="title">Access Denied</div>
        <div class="message">
            You do not have permission to access this resource. This incident has been logged for security monitoring.
        </div>
        <div class="security-notice">
            <strong>Security Notice:</strong> Unauthorized access attempts are monitored and logged.
        </div>
        <a href="/" class="btn">Return to Dashboard</a>
        <div class="timestamp">Generated: ' . htmlspecialchars($timestamp) . '</div>
    </div>
</body>
</html>';
    }

    /**
     * Get unauthorized access attempts data for analytics
     */
    public function getUnauthorizedAccessData(Request $request)
    {
        $attempts = UnauthorizedAccess::with('user')
            ->orderBy('attempted_at', 'desc')
            ->take(100)
            ->get()
            ->map(function($attempt) {
                return [
                    'id' => $attempt->id,
                    'user_name' => $attempt->user_details['name'] ?? 'Unknown',
                    'user_phone' => $attempt->user_details['phone_number'] ?? 'N/A',
                    'user_role' => $attempt->user_role,
                    'region' => $attempt->user_details['region'] ?? 'N/A',
                    'branch' => $attempt->user_details['branch'] ?? 'N/A',
                    'district' => $attempt->user_details['district'] ?? 'N/A',
                    'route_attempted' => $attempt->route_name,
                    'url_attempted' => $attempt->url_attempted,
                    'required_roles' => $attempt->required_roles,
                    'attempted_at' => $attempt->attempted_at->format('d/m/Y H:i:s'),
                    'date' => $attempt->attempted_at->format('d/m/Y'),
                    'time' => $attempt->attempted_at->format('H:i:s'),
                    'year' => $attempt->attempted_at->format('Y')
                ];
            });

        return response()->json([
            'attempts' => $attempts,
            'total_count' => UnauthorizedAccess::count(),
            'today_count' => UnauthorizedAccess::whereDate('attempted_at', today())->count(),
            'this_week_count' => UnauthorizedAccess::whereBetween('attempted_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ]);
    }

    /**
     * Export unauthorized access attempts to Excel
     */
    public function exportToExcel(Request $request)
    {
        $attempts = UnauthorizedAccess::with('user')
            ->orderBy('attempted_at', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'A1' => 'User Name',
            'B1' => 'Phone Number',
            'C1' => 'User Role',
            'D1' => 'Region',
            'E1' => 'Branch',
            'F1' => 'District',
            'G1' => 'Page Attempted',
            'H1' => 'Required Roles',
            'I1' => 'Date',
            'J1' => 'Time',
            'K1' => 'Year',
            'L1' => 'Full URL',
            'M1' => 'IP Address'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('17479E');
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
        }

        // Set data
        $row = 2;
        foreach ($attempts as $attempt) {
            $sheet->setCellValue('A' . $row, $attempt->user_details['name'] ?? 'Unknown');
            $sheet->setCellValue('B' . $row, $attempt->user_details['phone_number'] ?? 'N/A');
            $sheet->setCellValue('C' . $row, $attempt->user_role);
            $sheet->setCellValue('D' . $row, $attempt->user_details['region'] ?? 'N/A');
            $sheet->setCellValue('E' . $row, $attempt->user_details['branch'] ?? 'N/A');
            $sheet->setCellValue('F' . $row, $attempt->user_details['district'] ?? 'N/A');
            $sheet->setCellValue('G' . $row, $attempt->route_name);
            $sheet->setCellValue('H' . $row, $attempt->required_roles);
            $sheet->setCellValue('I' . $row, $attempt->attempted_at->format('d/m/Y'));
            $sheet->setCellValue('J' . $row, $attempt->attempted_at->format('H:i:s'));
            $sheet->setCellValue('K' . $row, $attempt->attempted_at->format('Y'));
            $sheet->setCellValue('L' . $row, $attempt->url_attempted);
            $sheet->setCellValue('M' . $row, $attempt->ip_address);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'unauthorized_access_attempts_' . date('Y-m-d');

        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export frequent unauthorized access attempts to PDF
     */
    public function exportFrequentAttemptsToPdf(Request $request)
    {
        // Get users with 3+ attempts in last 24 hours
        $frequentAttempts = UnauthorizedAccess::selectRaw('user_id, COUNT(*) as attempt_count, MAX(attempted_at) as last_attempt')
            ->where('attempted_at', '>=', Carbon::now()->subHours(24))
            ->groupBy('user_id')
            ->having('attempt_count', '>=', 3)
            ->with(['user'])
            ->get()
            ->map(function($record) {
                $latestAttempt = UnauthorizedAccess::where('user_id', $record->user_id)
                    ->orderBy('attempted_at', 'desc')
                    ->first();

                return [
                    'user_name' => $latestAttempt->user_details['name'] ?? 'Unknown',
                    'user_phone' => $latestAttempt->user_details['phone_number'] ?? 'N/A',
                    'user_role' => $latestAttempt->user_role,
                    'region' => $latestAttempt->user_details['region'] ?? 'N/A',
                    'branch' => $latestAttempt->user_details['branch'] ?? 'N/A',
                    'district' => $latestAttempt->user_details['district'] ?? 'N/A',
                    'attempt_count' => $record->attempt_count,
                    'last_attempt' => Carbon::parse($record->last_attempt)->format('d/m/Y H:i:s'),
                    'routes_attempted' => UnauthorizedAccess::where('user_id', $record->user_id)
                        ->where('attempted_at', '>=', Carbon::now()->subHours(24))
                        ->pluck('route_name')
                        ->unique()
                        ->implode(', ')
                ];
            });

        $data = [
            'frequent_attempts' => $frequentAttempts,
            'total_users' => $frequentAttempts->count(),
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'generated_by' => auth()->user()->name,
            'period' => 'Last 24 Hours'
        ];

        $pdf = Pdf::loadView('reports.frequent-unauthorized-attempts', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 10,
                'margin_right' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10
            ]);

        return $pdf->download('frequent_unauthorized_attempts_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export all unauthorized access attempts to PDF
     */
    public function exportToPdf(Request $request)
    {
        $attempts = UnauthorizedAccess::with('user')
            ->orderBy('attempted_at', 'desc')
            ->take(100)
            ->get()
            ->map(function($attempt) {
                return [
                    'user_name' => $attempt->user_details['name'] ?? 'Unknown',
                    'user_phone' => $attempt->user_details['phone_number'] ?? 'N/A',
                    'user_role' => $attempt->user_role,
                    'region' => $attempt->user_details['region'] ?? 'N/A',
                    'branch' => $attempt->user_details['branch'] ?? 'N/A',
                    'district' => $attempt->user_details['district'] ?? 'N/A',
                    'route_attempted' => $attempt->route_name,
                    'attempted_at' => $attempt->attempted_at->format('d/m/Y H:i:s'),
                    'date' => $attempt->attempted_at->format('d/m/Y'),
                    'time' => $attempt->attempted_at->format('H:i:s'),
                    'year' => $attempt->attempted_at->format('Y')
                ];
            });

        $data = [
            'attempts' => $attempts,
            'total_count' => $attempts->count(),
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'generated_by' => auth()->user()->name,
            'title' => 'Unauthorized Access Attempts Report'
        ];

        $pdf = Pdf::loadView('reports.unauthorized-access-attempts', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 10,
                'margin_right' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10
            ]);

        return $pdf->download('unauthorized_access_attempts_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export frequent unauthorized access attempts to CSV
     */
    public function exportFrequentAttemptsToCSV(Request $request)
    {
        // Get users with 3+ attempts in last 24 hours
        $frequentAttempts = UnauthorizedAccess::selectRaw('user_id, COUNT(*) as attempt_count, MAX(attempted_at) as last_attempt')
            ->where('attempted_at', '>=', Carbon::now()->subHours(24))
            ->groupBy('user_id')
            ->having('attempt_count', '>=', 3)
            ->with(['user'])
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'A1' => 'User Name',
            'B1' => 'Phone Number',
            'C1' => 'User Role',
            'D1' => 'Region',
            'E1' => 'Branch',
            'F1' => 'District',
            'G1' => 'Attempt Count',
            'H1' => 'Last Attempt',
            'I1' => 'Routes Attempted'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Set data
        $row = 2;
        foreach ($frequentAttempts as $record) {
            $latestAttempt = UnauthorizedAccess::where('user_id', $record->user_id)
                ->orderBy('attempted_at', 'desc')
                ->first();

            $routesAttempted = UnauthorizedAccess::where('user_id', $record->user_id)
                ->where('attempted_at', '>=', Carbon::now()->subHours(24))
                ->pluck('route_name')
                ->unique()
                ->implode(', ');

            $sheet->setCellValue('A' . $row, $latestAttempt->user_details['name'] ?? 'Unknown');
            $sheet->setCellValue('B' . $row, $latestAttempt->user_details['phone_number'] ?? 'N/A');
            $sheet->setCellValue('C' . $row, $latestAttempt->user_role);
            $sheet->setCellValue('D' . $row, $latestAttempt->user_details['region'] ?? 'N/A');
            $sheet->setCellValue('E' . $row, $latestAttempt->user_details['branch'] ?? 'N/A');
            $sheet->setCellValue('F' . $row, $latestAttempt->user_details['district'] ?? 'N/A');
            $sheet->setCellValue('G' . $row, $record->attempt_count);
            $sheet->setCellValue('H' . $row, Carbon::parse($record->last_attempt)->format('d/m/Y H:i:s'));
            $sheet->setCellValue('I' . $row, $routesAttempted);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Csv($spreadsheet);
        $filename = 'frequent_unauthorized_attempts_' . date('Y-m-d');

        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
