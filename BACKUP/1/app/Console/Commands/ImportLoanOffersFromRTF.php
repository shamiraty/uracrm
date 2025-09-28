<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportLoanOffersFromRTF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:loan-offers {file=public/apidoc/uraloan1.rtf}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import loan offers from RTF file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = base_path($this->argument('file'));
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Reading RTF file: {$filePath}");
        
        // Read the RTF file
        $content = file_get_contents($filePath);
        
        // Parse RTF content
        $loans = $this->parseRTFContent($content);
        
        if (empty($loans)) {
            $this->error("No data found in the RTF file");
            return 1;
        }
        
        $this->info("Found " . count($loans) . " loan records");
        
        // Import loans
        $bar = $this->output->createProgressBar(count($loans));
        $bar->start();
        
        $imported = 0;
        $failed = 0;
        
        foreach ($loans as $loan) {
            try {
                $this->importLoan($loan);
                $imported++;
            } catch (\Exception $e) {
                $failed++;
                $this->error("\nFailed to import loan: " . $loan['application_number'] . " - " . $e->getMessage());
            }
            $bar->advance();
        }
        
        $bar->finish();
        
        $this->info("\n\nImport completed!");
        $this->info("Successfully imported: {$imported} loans");
        if ($failed > 0) {
            $this->warn("Failed to import: {$failed} loans");
        }
        
        return 0;
    }

    /**
     * Parse RTF content and extract loan data
     */
    private function parseRTFContent($content)
    {
        $loans = [];
        
        // Find rows by looking for the \row command
        preg_match_all('/\\\\pard.*?\\\\row/s', $content, $matches);
        
        $isHeader = true;
        foreach ($matches[0] as $row) {
            // Skip header row
            if ($isHeader) {
                if (strpos($row, 'check_number') !== false) {
                    $isHeader = false;
                }
                continue;
            }
            
            // Extract cell data
            preg_match_all('/\\\\(?:intbl|qr)[^\\\\]*?\\\\cell/', $row, $cellMatches);
            
            if (count($cellMatches[0]) >= 11) {
                $cells = [];
                foreach ($cellMatches[0] as $cell) {
                    // Clean the cell content
                    $cell = preg_replace('/\\\\(?:intbl|qr|pard|cf\d+|f\d+|fs\d+|lang\d+)\s*/', '', $cell);
                    $cell = str_replace('\\cell', '', $cell);
                    $cell = trim($cell);
                    // Remove spaces from numbers
                    $cell = preg_replace('/(\d+),(\d+)/', '$1$2', $cell);
                    $cell = preg_replace('/\s+([\d,]+\.\d+)\s+/', '$1', $cell);
                    $cells[] = trim($cell);
                }
                
                // Only add if we have valid data
                if (!empty($cells[0]) && is_numeric(str_replace(',', '', $cells[0]))) {
                    $loans[] = [
                        'check_number' => str_replace(',', '', $cells[0]),
                        'first_name' => $cells[1] ?? '',
                        'middle_name' => $cells[2] ?? '',
                        'last_name' => $cells[3] ?? '',
                        'vote_code' => $cells[4] ?? '',
                        'vote_name' => $cells[5] ?? '',
                        'application_number' => $cells[6] ?? '',
                        'loan_number' => $cells[7] ?? '',
                        'amount' => floatval(str_replace(',', '', $cells[8] ?? 0)),
                        'initial_balance' => floatval(str_replace(',', '', $cells[9] ?? 0)),
                        'ded_balance_amount' => floatval(str_replace(',', '', $cells[10] ?? 0)),
                    ];
                }
            }
        }
        
        return $loans;
    }

    /**
     * Import a single loan record
     */
    private function importLoan($loan)
    {
        // Check if all required fields are present
        if (empty($loan['application_number']) || empty($loan['check_number'])) {
            throw new \Exception("Missing required fields");
        }
        
        DB::table('loan_offers')->updateOrInsert(
            ['application_number' => $loan['application_number']],
            [
                'check_number' => substr($loan['check_number'], 0, 9), // Ensure max 9 chars
                'first_name' => substr($loan['first_name'], 0, 30),
                'middle_name' => substr($loan['middle_name'], 0, 30),
                'last_name' => substr($loan['last_name'], 0, 30),
                'vote_code' => substr($loan['vote_code'], 0, 6),
                'vote_name' => $loan['vote_name'],
                'application_number' => substr($loan['application_number'], 0, 15),
                'requested_amount' => $loan['amount'],
                
                // Additional columns that might be added
                'net_loan_amount' => $loan['amount'], // Assuming this is the net amount
                'take_home_amount' => $loan['amount'] * 0.95, // 95% of loan amount as take-home
                
                // Set default values for required fields
                'sex' => $this->guessSex($loan['first_name']),
                'employment_date' => Carbon::now()->subYears(5),
                'marital_status' => 'SINGLE',
                'bank_account_number' => $this->generateBankAccount($loan['check_number']),
                'nin' => $this->generateNIN($loan['check_number']),
                'designation_code' => 'DES001',
                'designation_name' => 'Officer',
                'basic_salary' => round($loan['initial_balance'] / 12, 2),
                'net_salary' => round($loan['ded_balance_amount'] / 12, 2),
                'one_third_amount' => round($loan['amount'] / 3, 2),
                'total_employee_deduction' => round(($loan['initial_balance'] - $loan['ded_balance_amount']) / 12, 2),
                'retirement_date' => intval(Carbon::now()->addYears(20)->format('Ymd')),
                'terms_of_employment' => 'PERMANENT',
                'desired_deductible_amount' => round($loan['amount'] / 12, 2),
                'tenure' => 12,
                'fsp_code' => 'URA001',
                'product_code' => 'LOAN01',
                'interest_rate' => 10.00,
                'processing_fee' => 1.00,
                'insurance' => 0.50,
                'physical_address' => 'Dar es Salaam, Tanzania',
                'email_address' => $this->generateEmail($loan['first_name'], $loan['last_name']),
                'mobile_number' => $this->generatePhoneNumber(),
                'loan_purpose' => 'Personal Development',
                'swift_code' => 'NMIBTZTZ',
                'funding' => 'URAERP',
                'approval' => 'PENDING',
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }

    /**
     * Guess sex based on first name
     */
    private function guessSex($firstName)
    {
        $femaleNames = ['modesta', 'nuru', 'mary', 'jane', 'grace', 'joyce', 'rose', 'fatuma', 'asha'];
        return in_array(strtolower($firstName), $femaleNames) ? 'F' : 'M';
    }

    /**
     * Generate a bank account number
     */
    private function generateBankAccount($checkNumber)
    {
        return str_pad($checkNumber, 20, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a NIN
     */
    private function generateNIN($checkNumber)
    {
        return date('Ymd') . str_pad($checkNumber, 12, '0', STR_PAD_LEFT);
    }

    /**
     * Generate email address
     */
    private function generateEmail($firstName, $lastName)
    {
        $firstName = preg_replace('/[^a-zA-Z]/', '', $firstName);
        $lastName = preg_replace('/[^a-zA-Z]/', '', $lastName);
        return strtolower($firstName . '.' . $lastName . '@uraerp.com');
    }

    /**
     * Generate phone number
     */
    private function generatePhoneNumber()
    {
        return '07' . rand(10000000, 99999999);
    }
}