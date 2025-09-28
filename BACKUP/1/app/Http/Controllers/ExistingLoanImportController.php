<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ExistingLoansImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ExistingLoanImportController extends Controller
{
    /**
     * Show the import form
     */
    public function showImportForm()
    {
        return view('employee_loan.import_existing');
    }

    /**
     * Handle the import
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240' // 10MB max
        ]);

        try {
            DB::beginTransaction();

            $import = new ExistingLoansImport();
            Excel::import($import, $request->file('file'));

            $stats = $import->getStats();

            if ($stats['imported'] > 0) {
                DB::commit();
                
                $message = "Successfully imported {$stats['imported']} existing loans.";
                if ($stats['skipped'] > 0) {
                    $message .= " Skipped {$stats['skipped']} duplicate/invalid records.";
                }

                return redirect()->back()->with('success', $message);
            } else {
                DB::rollBack();
                
                $errorMessage = "No loans were imported.";
                if (!empty($stats['errors'])) {
                    $errorMessage .= " Errors: " . implode('; ', array_slice($stats['errors'], 0, 3));
                }

                return redirect()->back()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Import using default file
     */
    public function importDefault()
    {
        $filePath = public_path('apidoc/DED_URA SACCOS LTD.xlsx');
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Default file not found: DED_URA SACCOS LTD.xlsx');
        }

        try {
            DB::beginTransaction();

            $import = new ExistingLoansImport();
            Excel::import($import, $filePath);

            $stats = $import->getStats();

            if ($stats['imported'] > 0) {
                DB::commit();
                
                $message = "Successfully imported {$stats['imported']} existing loans from default file.";
                if ($stats['skipped'] > 0) {
                    $message .= " Skipped {$stats['skipped']} duplicate/invalid records.";
                }

                return redirect()->route('loan-offers.index')->with('success', $message);
            } else {
                DB::rollBack();
                
                $errorMessage = "No loans were imported from default file.";
                if (!empty($stats['errors'])) {
                    $errorMessage .= " Errors: " . implode('; ', array_slice($stats['errors'], 0, 3));
                }

                return redirect()->back()->with('error', $errorMessage);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Default import failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download sample import template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Check Number',
            'Employee Name',
            'Loan Number',
            'Principal Amount',
            'Monthly Deduction',
            'Outstanding Balance',
            'Bank Account',
            'Mobile Number',
            'Email',
            'Basic Salary',
            'Net Salary',
            'Employment Date'
        ];

        $sampleData = [
            [
                '12345678',
                'John Doe Smith',
                'LN001234',
                '5000000',
                '150000',
                '3500000',
                '01234567890',
                '0712345678',
                'john.doe@example.com',
                '1500000',
                '1200000',
                '2020-01-15'
            ]
        ];

        $callback = function() use ($headers, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        $filename = 'existing_loans_template_' . date('Y-m-d') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}