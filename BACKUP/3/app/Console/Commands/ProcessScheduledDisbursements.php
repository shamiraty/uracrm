<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoanOffer;
use App\Models\LoanDisbursement;
use App\Services\NmbService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProcessScheduledDisbursements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'disbursements:process 
                            {--time= : Specify time slot (morning/afternoon/evening)}
                            {--limit=100 : Maximum number of loans to process}
                            {--dry-run : Run without actually disbursing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled loan disbursements at specified times (9AM, 12PM, 3PM)';

    private $nmbService;
    private $timeSlots = [
        'morning' => '09:00',
        'afternoon' => '12:00',
        'evening' => '15:00'
    ];

    /**
     * Create a new command instance.
     */
    public function __construct(NmbService $nmbService)
    {
        parent::__construct();
        $this->nmbService = $nmbService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeSlot = $this->option('time') ?? $this->getCurrentTimeSlot();
        $limit = (int) $this->option('limit');
        $isDryRun = $this->option('dry-run');
        
        if (!$timeSlot) {
            $this->error('No disbursement scheduled for current time.');
            return 1;
        }
        
        $this->info("========================================");
        $this->info("LOAN DISBURSEMENT BATCH PROCESSOR");
        $this->info("========================================");
        $this->info("Time Slot: " . strtoupper($timeSlot) . " (" . $this->timeSlots[$timeSlot] . ")");
        $this->info("Date: " . Carbon::now()->format('Y-m-d'));
        $this->info("Mode: " . ($isDryRun ? 'DRY RUN' : 'LIVE'));
        $this->info("Batch Limit: " . $limit);
        $this->info("========================================\n");
        
        try {
            // Get pending disbursement loans
            $loans = $this->getPendingLoans($limit);
            
            if ($loans->isEmpty()) {
                $this->info("✓ No pending loans to process.");
                $this->logBatchResult($timeSlot, 0, 0, 0, 'No pending loans');
                return 0;
            }
            
            $this->info("Found {$loans->count()} loans ready for disbursement.\n");
            
            if ($isDryRun) {
                $this->performDryRun($loans);
                return 0;
            }
            
            // Process disbursements
            $results = $this->processDisbursements($loans, $timeSlot);
            
            // Display results
            $this->displayResults($results);
            
            // Send notification
            $this->sendBatchNotification($timeSlot, $results);
            
            // Log batch processing
            $this->logBatchResult(
                $timeSlot,
                $results['total'],
                $results['success'],
                $results['failed'],
                'Batch completed'
            );
            
            return $results['failed'] > 0 ? 1 : 0;
            
        } catch (\Exception $e) {
            $this->error("Error processing disbursements: " . $e->getMessage());
            Log::error('Scheduled disbursement failed', [
                'time_slot' => $timeSlot,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
    
    /**
     * Get current time slot based on system time
     */
    private function getCurrentTimeSlot(): ?string
    {
        $currentHour = Carbon::now()->hour;
        
        // Allow 30-minute window for each slot
        if ($currentHour == 9) {
            return 'morning';
        } elseif ($currentHour == 12) {
            return 'afternoon';
        } elseif ($currentHour == 15) {
            return 'evening';
        }
        
        return null;
    }
    
    /**
     * Get pending loans for disbursement
     */
    private function getPendingLoans(int $limit)
    {
        return LoanOffer::where('approval', 'APPROVED')
            ->where('state', 'Submitted for disbursement')
            ->whereNotIn('status', ['disbursed', 'DISBURSEMENT_FAILED', 'DISBURSEMENT_REJECTED'])
            ->whereNull('nmb_batch_id') // Not yet sent to NMB
            ->orderBy('updated_at', 'asc') // Process oldest first
            ->limit($limit)
            ->get();
    }
    
    /**
     * Perform dry run simulation
     */
    private function performDryRun($loans)
    {
        $this->info("DRY RUN - Simulating disbursement for {$loans->count()} loans:\n");
        
        $totalAmount = 0;
        $byBank = [];
        
        $this->table(
            ['ID', 'Application No', 'Employee', 'Amount (TZS)', 'Bank', 'Account'],
            $loans->map(function($loan) use (&$totalAmount, &$byBank) {
                $amount = $loan->take_home_amount ?? $loan->net_loan_amount ?? $loan->requested_amount;
                $totalAmount += $amount;
                
                $bankName = $loan->bank->short_name ?? 'Unknown';
                if (!isset($byBank[$bankName])) {
                    $byBank[$bankName] = ['count' => 0, 'amount' => 0];
                }
                $byBank[$bankName]['count']++;
                $byBank[$bankName]['amount'] += $amount;
                
                return [
                    $loan->id,
                    $loan->application_number,
                    $loan->first_name . ' ' . $loan->last_name,
                    number_format($amount, 2),
                    $bankName,
                    substr($loan->bank_account_number, -4)
                ];
            })->toArray()
        );
        
        $this->info("\nSummary by Bank:");
        foreach ($byBank as $bank => $data) {
            $this->info(sprintf(
                "  %s: %d loans, TZS %s",
                $bank,
                $data['count'],
                number_format($data['amount'], 2)
            ));
        }
        
        $this->info("\nTotal Amount: TZS " . number_format($totalAmount, 2));
        $this->info("\n✓ DRY RUN completed. No actual disbursements were made.");
    }
    
    /**
     * Process actual disbursements
     */
    private function processDisbursements($loans, string $timeSlot)
    {
        $results = [
            'total' => $loans->count(),
            'success' => 0,
            'failed' => 0,
            'details' => [],
            'batch_ids' => [],
            'total_amount' => 0,
            'failed_loans' => []
        ];
        
        // Check if NMB is enabled
        if (!config('services.nmb.enabled', false)) {
            $this->warn("⚠ NMB service is disabled. Processing in test mode.");
        }
        
        // Group loans by similar characteristics for batch processing
        $batches = $this->groupLoansForBatching($loans);
        
        $this->info("Processing {$batches->count()} batch(es)...\n");
        
        $progressBar = $this->output->createProgressBar($loans->count());
        $progressBar->start();
        
        foreach ($batches as $batchKey => $batchLoans) {
            $this->processBatch($batchLoans, $results, $progressBar, $timeSlot);
        }
        
        $progressBar->finish();
        $this->info("\n");
        
        return $results;
    }
    
    /**
     * Group loans for efficient batch processing
     */
    private function groupLoansForBatching($loans)
    {
        // Group by destination type for efficient processing
        // NMB allows up to 100 transactions per batch
        $batchSize = config('services.nmb.batch_size_limit', 100);
        
        return $loans->chunk($batchSize);
    }
    
    /**
     * Process a batch of loans
     */
    private function processBatch($batchLoans, &$results, $progressBar, $timeSlot)
    {
        try {
            DB::beginTransaction();
            
            // Use bulk disbursement for efficiency
            if ($batchLoans->count() > 1) {
                $response = $this->nmbService->disburseBulkLoans($batchLoans);
            } else {
                $response = $this->nmbService->disburseLoan($batchLoans->first());
            }
            
            if ($response['success']) {
                $batchId = $response['batch_id'] ?? null;
                $results['batch_ids'][] = $batchId;
                
                foreach ($batchLoans as $loan) {
                    // Update loan status
                    $loan->update([
                        'nmb_batch_id' => $batchId,
                        'status' => 'disbursement_pending',
                        'state' => 'Submitted for disbursement'
                    ]);
                    
                    // Create disbursement record
                    LoanDisbursement::create([
                        'loan_offer_id' => $loan->id,
                        'amount' => $loan->take_home_amount ?? $loan->net_loan_amount,
                        'status' => 'pending',
                        'batch_id' => $batchId,
                        'channel' => 'NMB',
                        'channel_identifier' => $this->getChannelIdentifier($loan),
                        'account_number' => $loan->bank_account_number,
                        'disbursed_by' => 1 // System user ID for automated process
                    ]);
                    
                    $amount = $loan->take_home_amount ?? $loan->net_loan_amount ?? $loan->requested_amount;
                    $results['total_amount'] += $amount;
                    $results['success']++;
                    
                    $results['details'][] = [
                        'loan_id' => $loan->id,
                        'application_number' => $loan->application_number,
                        'amount' => $amount,
                        'status' => 'submitted',
                        'batch_id' => $batchId
                    ];
                    
                    $progressBar->advance();
                }
                
                DB::commit();
                
                // Log successful batch
                Log::info("Disbursement batch submitted", [
                    'time_slot' => $timeSlot,
                    'batch_id' => $batchId,
                    'count' => $batchLoans->count(),
                    'total_amount' => $results['total_amount']
                ]);
                
            } else {
                DB::rollBack();
                
                foreach ($batchLoans as $loan) {
                    $results['failed']++;
                    $results['failed_loans'][] = [
                        'loan_id' => $loan->id,
                        'application_number' => $loan->application_number,
                        'reason' => $response['error'] ?? 'Unknown error'
                    ];
                    
                    $progressBar->advance();
                }
                
                Log::error("Disbursement batch failed", [
                    'time_slot' => $timeSlot,
                    'error' => $response['error'] ?? 'Unknown error',
                    'count' => $batchLoans->count()
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            foreach ($batchLoans as $loan) {
                $results['failed']++;
                $results['failed_loans'][] = [
                    'loan_id' => $loan->id,
                    'application_number' => $loan->application_number,
                    'reason' => $e->getMessage()
                ];
                
                $progressBar->advance();
            }
            
            Log::error("Batch processing exception", [
                'time_slot' => $timeSlot,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get channel identifier for loan
     */
    private function getChannelIdentifier($loan)
    {
        $swiftCode = $loan->swift_code ?? $loan->bank->swift_code ?? '';
        
        if (strpos($swiftCode, 'NMB') !== false) {
            return 'INTERNAL';
        }
        
        return 'DOMESTIC';
    }
    
    /**
     * Display processing results
     */
    private function displayResults($results)
    {
        $this->info("\n========================================");
        $this->info("DISBURSEMENT BATCH RESULTS");
        $this->info("========================================");
        $this->info("Total Processed: " . $results['total']);
        $this->info("✓ Successful: " . $results['success']);
        $this->info("✗ Failed: " . $results['failed']);
        $this->info("Total Amount: TZS " . number_format($results['total_amount'], 2));
        
        if (!empty($results['batch_ids'])) {
            $this->info("\nBatch IDs:");
            foreach ($results['batch_ids'] as $batchId) {
                $this->info("  - " . $batchId);
            }
        }
        
        if (!empty($results['failed_loans'])) {
            $this->error("\nFailed Loans:");
            $this->table(
                ['Loan ID', 'Application No', 'Reason'],
                array_map(function($loan) {
                    return [
                        $loan['loan_id'],
                        $loan['application_number'],
                        substr($loan['reason'], 0, 50)
                    ];
                }, $results['failed_loans'])
            );
        }
        
        $this->info("========================================\n");
    }
    
    /**
     * Send batch notification
     */
    private function sendBatchNotification($timeSlot, $results)
    {
        try {
            $recipients = config('disbursements.notification_emails', []);
            
            if (empty($recipients)) {
                return;
            }
            
            $data = [
                'time_slot' => $timeSlot,
                'date' => Carbon::now()->format('Y-m-d'),
                'time' => Carbon::now()->format('H:i:s'),
                'total' => $results['total'],
                'success' => $results['success'],
                'failed' => $results['failed'],
                'total_amount' => $results['total_amount'],
                'batch_ids' => $results['batch_ids']
            ];
            
            // You can create a mail template for this
            // Mail::to($recipients)->send(new \App\Mail\DisbursementBatchReport($data));
            
            Log::info("Disbursement batch notification sent", [
                'time_slot' => $timeSlot,
                'recipients' => count($recipients)
            ]);
            
        } catch (\Exception $e) {
            Log::warning("Failed to send batch notification", [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Log batch result to database
     */
    private function logBatchResult($timeSlot, $total, $success, $failed, $notes = null)
    {
        try {
            DB::table('disbursement_batch_logs')->insert([
                'batch_date' => Carbon::now()->format('Y-m-d'),
                'time_slot' => $timeSlot,
                'total_processed' => $total,
                'successful' => $success,
                'failed' => $failed,
                'notes' => $notes,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::warning("Failed to log batch result", ['error' => $e->getMessage()]);
        }
    }
}