<?php

namespace App\Imports;

use App\Models\LoanOffer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ExistingLoansImport implements 
    ToModel, 
    WithHeadingRow, 
    SkipsEmptyRows, 
    SkipsOnError, 
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    use Importable;

    private $rowNumber = 0;
    private $imported = 0;
    private $skipped = 0;
    private $errors = [];

    /**
     * Transform a row into a model
     */
    public function model(array $row)
    {
        $this->rowNumber++;

        try {
            // Skip if check number is empty
            if (empty($row['check_number'])) {
                $this->skipped++;
                return null;
            }

            // Map columns based on actual Excel headers:
            // check_number, first_name, middle_name, last_name, vote_code, vote_name,
            // application_number, loan_number, amount, initial_balance, ded_balance_amount
            
            $checkNumber = trim($row['check_number'] ?? '');
            $firstName = trim($row['first_name'] ?? '');
            $middleName = trim($row['middle_name'] ?? '');
            $lastName = trim($row['last_name'] ?? '');
            $voteCode = trim($row['vote_code'] ?? '');
            $voteName = trim($row['vote_name'] ?? '');
            $applicationNumber = trim($row['application_number'] ?? '');
            $loanNumber = trim($row['loan_number'] ?? '');
            
            // Parse amounts - based on Excel structure
            $monthlyDeduction = $this->parseAmount($row['amount'] ?? 0);           // amount is monthly deduction
            $initialBalance = $this->parseAmount($row['initial_balance'] ?? 0);    // original loan amount
            $currentBalance = $this->parseAmount($row['ded_balance_amount'] ?? 0); // current outstanding
            
            // Skip if essential fields are missing
            if (empty($loanNumber) || empty($applicationNumber)) {
                Log::warning("Skipping row {$this->rowNumber}: Missing loan_number or application_number");
                $this->skipped++;
                return null;
            }
            
            // Calculate original loan amount
            $originalAmount = $initialBalance > 0 ? $initialBalance : $currentBalance;

            // Check if loan already exists
            $existingLoan = LoanOffer::where('loan_number', $loanNumber)
                ->orWhere(function($query) use ($checkNumber, $applicationNumber) {
                    $query->where('check_number', $checkNumber)
                          ->where('application_number', $applicationNumber);
                })
                ->first();

            if ($existingLoan) {
                Log::info("Loan already exists", [
                    'check_number' => $checkNumber,
                    'loan_number' => $loanNumber,
                    'application_number' => $applicationNumber
                ]);
                $this->skipped++;
                return null;
            }

            // Calculate tenure and installments
            $tenure = $this->calculateTenure($originalAmount, $monthlyDeduction);
            $installmentsPaid = $this->calculateInstallmentsPaid($originalAmount, $currentBalance, $monthlyDeduction);

            $this->imported++;

            return new LoanOffer([
                'check_number' => $checkNumber,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'loan_number' => $loanNumber,
                'application_number' => $applicationNumber, // Use actual application number from Excel
                'requested_amount' => $originalAmount,
                'desired_deductible_amount' => $monthlyDeduction,
                'total_employee_deduction' => $monthlyDeduction, // Set same as monthly deduction
                'outstanding_balance' => $currentBalance,
                'settlement_amount' => $currentBalance,
                'tenure' => $tenure,
                'installments_paid' => $installmentsPaid,
                
                // Mark as new loan (will change to topup when topped up)
                'loan_type' => 'new',
                'offer_type' => 'NEW',
                'status' => 'DISBURSED',
                'approval' => 'APPROVED',
                'state' => 'Active Loan - Imported',
                
                // Dates
                'disbursement_date' => $this->estimateDisbursementDate($installmentsPaid),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                
                // Vote information from Excel
                'vote_code' => $voteCode,
                'vote_name' => $voteName,
                
                // Bank details (not in Excel, use defaults)
                'bank_account_number' => '',
                'swift_code' => '',
                
                // Default values for required fields not in Excel
                'interest_rate' => 12,
                'processing_fee' => 0,
                'insurance' => 0,
                'basic_salary' => 0,
                'net_salary' => 0,
                'one_third_amount' => 0,
                'sex' => 'M',
                'marital_status' => 'MARRIED',
                'employment_date' => Carbon::now()->subYears(5)->format('Y-m-d'),
                'retirement_date' => Carbon::now()->addYears(10)->timestamp, // Add retirement date as timestamp
                'terms_of_employment' => 'Permanent and Pensionable',
                'loan_purpose' => 'Existing loan imported for topup tracking',
                'product_code' => '769',                  // Product code
                'funding' => 'EMPLOYER',                  // Funding source
                'date_of_birth' => '1980-01-01',          // Default DOB
                'physical_address' => 'Tanzania',
                'mobile_number' => '',
                'email_address' => '',
                'fsp_code' => 'FL7456',
                'nin' => '',
                'designation_code' => '',
                'designation_name' => '',
            ]);

        } catch (\Exception $e) {
            Log::error("Error importing row {$this->rowNumber}", [
                'error' => $e->getMessage(),
                'row' => $row
            ]);
            $this->errors[] = "Row {$this->rowNumber}: " . $e->getMessage();
            $this->skipped++;
            return null;
        }
    }

    /**
     * Parse full name into parts
     */
    private function parseFullName($fullName)
    {
        if (empty($fullName)) {
            return ['first' => 'Unknown', 'middle' => '', 'last' => 'Employee'];
        }

        $parts = preg_split('/\s+/', trim($fullName));
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
     * Parse amount from various formats
     */
    private function parseAmount($value)
    {
        if (empty($value) || $value === '-') return 0;
        
        // Remove currency symbols, commas, and spaces
        $cleaned = preg_replace('/[^0-9.-]/', '', $value);
        return abs((float) $cleaned);
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($value)
    {
        if (empty($value)) return null;
        
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Calculate loan tenure
     */
    private function calculateTenure($amount, $monthlyPayment)
    {
        if ($monthlyPayment <= 0) return 36;
        
        $months = ceil($amount / $monthlyPayment);
        return min(max($months, 12), 60); // Between 12 and 60 months
    }

    /**
     * Calculate installments already paid
     */
    private function calculateInstallmentsPaid($original, $remaining, $monthly)
    {
        if ($monthly <= 0) return 0;
        
        $paid = $original - $remaining;
        return max(0, (int) round($paid / $monthly));
    }

    /**
     * Estimate when loan was disbursed
     */
    private function estimateDisbursementDate($installmentsPaid)
    {
        return Carbon::now()->subMonths($installmentsPaid)->format('Y-m-d');
    }

    /**
     * Handle errors
     */
    public function onError(\Throwable $e)
    {
        Log::error('Import error', ['error' => $e->getMessage()]);
        $this->errors[] = $e->getMessage();
    }

    /**
     * Handle failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            Log::warning('Import validation failure', [
                'row' => $failure->row(),
                'errors' => $failure->errors()
            ]);
        }
    }

    /**
     * Batch size for inserts
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Chunk size for reading
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Get import statistics
     */
    public function getStats()
    {
        return [
            'imported' => $this->imported,
            'skipped' => $this->skipped,
            'errors' => $this->errors,
            'total_processed' => $this->rowNumber
        ];
    }
}