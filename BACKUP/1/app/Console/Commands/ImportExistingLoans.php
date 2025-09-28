<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoanOffer;
use App\Models\Member;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportExistingLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:import-existing {file? : Path to the Excel file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import existing loans from Excel file that can be topped up';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = $this->argument('file') ?? public_path('apidoc/DED_URA SACCOS LTD.xlsx');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Starting import from: {$filePath}");
        
        try {
            $data = Excel::toArray([], $filePath);
            
            if (empty($data) || empty($data[0])) {
                $this->error("No data found in the Excel file");
                return 1;
            }

            $sheet = $data[0];
            $headers = array_shift($sheet); // Remove header row
            
            $this->info("Found " . count($sheet) . " rows to import");
            
            $imported = 0;
            $skipped = 0;
            $errors = 0;

            DB::beginTransaction();

            foreach ($sheet as $index => $row) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Map Excel columns to database fields
                    // Adjust these indices based on your Excel structure
                    $loanData = $this->mapRowToLoanData($row, $index + 2);
                    
                    if (!$loanData) {
                        $skipped++;
                        continue;
                    }

                    // Check if loan already exists
                    $existingLoan = LoanOffer::where('loan_number', $loanData['loan_number'])
                        ->orWhere('check_number', $loanData['check_number'])
                        ->first();

                    if ($existingLoan) {
                        $this->warn("Loan already exists for Check Number: {$loanData['check_number']}, Loan Number: {$loanData['loan_number']}");
                        $skipped++;
                        continue;
                    }

                    // Create the loan offer
                    $loanOffer = LoanOffer::create($loanData);
                    
                    $this->info("Imported loan for: {$loanData['first_name']} {$loanData['last_name']} - Loan #: {$loanData['loan_number']}");
                    $imported++;

                } catch (\Exception $e) {
                    $this->error("Error on row " . ($index + 2) . ": " . $e->getMessage());
                    $errors++;
                    Log::error("Import error on row " . ($index + 2), [
                        'error' => $e->getMessage(),
                        'row' => $row
                    ]);
                }
            }

            if ($this->confirm("Import complete. Imported: {$imported}, Skipped: {$skipped}, Errors: {$errors}. Commit changes?", true)) {
                DB::commit();
                $this->info("Changes committed successfully!");
            } else {
                DB::rollBack();
                $this->warn("Import rolled back.");
            }

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Import failed: " . $e->getMessage());
            Log::error("Import failed", ['error' => $e->getMessage()]);
            return 1;
        }
    }

    /**
     * Map Excel row to loan data array
     */
    private function mapRowToLoanData($row, $rowNumber)
    {
        try {
            // Map columns based on actual Excel structure:
            // 0 = check_number, 1 = first_name, 2 = middle_name, 3 = last_name
            // 4 = vote_code, 5 = vote_name, 6 = application_number, 7 = loan_number
            // 8 = amount, 9 = initial_balance, 10 = ded_balance_amount
            
            $checkNumber = trim($row[0] ?? '');
            $firstName = trim($row[1] ?? '');
            $middleName = trim($row[2] ?? '');
            $lastName = trim($row[3] ?? '');
            $voteCode = trim($row[4] ?? '');
            $voteName = trim($row[5] ?? '');
            $applicationNumber = trim($row[6] ?? '');
            $loanNumber = trim($row[7] ?? '');
            
            // Parse amounts
            $monthlyDeduction = $this->parseAmount($row[8] ?? 0); // amount column is monthly deduction
            $initialBalance = $this->parseAmount($row[9] ?? 0);   // initial loan amount
            $currentBalance = $this->parseAmount($row[10] ?? 0);  // current outstanding balance
            
            // Skip if essential fields are missing
            if (empty($checkNumber) || empty($loanNumber) || empty($applicationNumber)) {
                $this->warn("Skipping row {$rowNumber}: Missing check_number, loan_number or application_number");
                return null;
            }
            
            // Calculate the original loan amount (use initial_balance as the original principal)
            $originalAmount = $initialBalance > 0 ? $initialBalance : $currentBalance;
            
            // Calculate tenure based on original amount and monthly deduction
            $tenure = $this->calculateTenure($originalAmount, $monthlyDeduction);
            
            // Calculate installments already paid
            $installmentsPaid = $this->calculateInstallmentsPaid($originalAmount, $currentBalance, $monthlyDeduction);

            return [
                'check_number' => $checkNumber,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'loan_number' => $loanNumber,
                'application_number' => $applicationNumber, // Use actual application number from Excel
                'requested_amount' => $originalAmount,     // Original loan amount
                'desired_deductible_amount' => $monthlyDeduction,
                'total_employee_deduction' => $monthlyDeduction, // Set same as monthly deduction
                'outstanding_balance' => $currentBalance,  // Current balance
                'settlement_amount' => $currentBalance,    // Same as outstanding for existing loans
                'tenure' => $tenure,
                'installments_paid' => $installmentsPaid,
                'loan_type' => 'new',                      // These are existing loans marked as 'new'
                'offer_type' => 'NEW',
                'status' => 'DISBURSED',                   // These are already disbursed loans
                'approval' => 'APPROVED',
                'state' => 'Active Loan',
                'disbursement_date' => $this->estimateDisbursementDate($installmentsPaid, $tenure),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                
                // Vote information from Excel
                'vote_code' => $voteCode,
                'vote_name' => $voteName,
                
                // Default values for other required fields
                'interest_rate' => 12,                     // Default 12% if not specified
                'processing_fee' => 0,
                'insurance' => 0,
                'bank_account_number' => '',               // Not in Excel
                'mobile_number' => '',                     // Not in Excel
                'email_address' => '',                     // Not in Excel
                'basic_salary' => 0,                       // Not in Excel
                'net_salary' => 0,                         // Not in Excel
                'one_third_amount' => 0,
                'sex' => 'M',                              // Default
                'marital_status' => 'MARRIED',             // Default
                'employment_date' => Carbon::now()->subYears(5)->format('Y-m-d'),
                'retirement_date' => Carbon::now()->addYears(10)->timestamp, // Add retirement date as timestamp
                'terms_of_employment' => 'Permanent and Pensionable',
                'loan_purpose' => 'Existing loan - imported for topup tracking',
                'product_code' => '769',                  // Product code
                'funding' => 'EMPLOYER',                  // Funding source
                'date_of_birth' => '1980-01-01',          // Default DOB
                'physical_address' => 'Tanzania',
                'fsp_code' => 'FL7456',
                'nin' => '',                               // Not in Excel
                'designation_code' => '',
                'designation_name' => '',
            ];
            
        } catch (\Exception $e) {
            $this->error("Error mapping row {$rowNumber}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Parse full name into parts
     */
    private function parseFullName($fullName)
    {
        $parts = explode(' ', trim($fullName));
        $count = count($parts);
        
        if ($count >= 3) {
            return [
                'first' => $parts[0],
                'middle' => $parts[1],
                'last' => implode(' ', array_slice($parts, 2))
            ];
        } elseif ($count == 2) {
            return [
                'first' => $parts[0],
                'middle' => '',
                'last' => $parts[1]
            ];
        } else {
            return [
                'first' => $fullName,
                'middle' => '',
                'last' => ''
            ];
        }
    }

    /**
     * Parse amount from string (remove commas, currency symbols)
     */
    private function parseAmount($value)
    {
        if (empty($value)) return 0;
        
        // Remove any non-numeric characters except decimal point
        $cleaned = preg_replace('/[^0-9.]/', '', $value);
        return (float) $cleaned;
    }

    /**
     * Calculate tenure based on amount and monthly payment
     */
    private function calculateTenure($amount, $monthlyPayment)
    {
        if ($monthlyPayment <= 0) return 36; // Default 36 months
        
        $months = ceil($amount / $monthlyPayment);
        return min($months, 60); // Cap at 60 months
    }

    /**
     * Calculate installments already paid
     */
    private function calculateInstallmentsPaid($originalAmount, $remainingBalance, $monthlyPayment)
    {
        if ($monthlyPayment <= 0) return 0;
        
        $paidAmount = $originalAmount - $remainingBalance;
        return (int) round($paidAmount / $monthlyPayment);
    }

    /**
     * Estimate disbursement date based on installments paid
     */
    private function estimateDisbursementDate($installmentsPaid, $tenure)
    {
        // Estimate the loan started this many months ago
        $monthsAgo = min($installmentsPaid, $tenure);
        return Carbon::now()->subMonths($monthsAgo)->format('Y-m-d');
    }
}