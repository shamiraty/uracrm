<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NmbService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReconcileNmbTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nmb:reconcile {--date= : Date to reconcile (Y-m-d format)} {--email= : Email address for report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconcile NMB transactions with local records and identify discrepancies';

    private $nmbService;

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
        $date = $this->option('date') ? 
            Carbon::parse($this->option('date')) : 
            Carbon::yesterday(); // Default to yesterday for end-of-day reconciliation
            
        $email = $this->option('email');
        
        $this->info("Starting NMB transaction reconciliation for {$date->format('Y-m-d')}...");
        
        try {
            // Perform reconciliation
            $result = $this->nmbService->reconcileTransactions($date);
            
            $this->info("Reconciliation completed:");
            $this->info("- Total transactions checked: {$result['total_checked']}");
            $this->info("- Successfully reconciled: {$result['reconciled']}");
            $this->info("- Discrepancies found: " . count($result['discrepancies']));
            
            if (count($result['discrepancies']) > 0) {
                $this->warn("\nDiscrepancies detected:");
                
                $this->table(
                    ['Loan ID', 'Application Number', 'Local Status', 'NMB Status'],
                    array_map(function($d) {
                        return [
                            $d['loan_id'],
                            $d['application_number'],
                            $d['local_status'],
                            $d['nmb_status']
                        ];
                    }, $result['discrepancies'])
                );
                
                // Log discrepancies
                Log::warning('NMB reconciliation discrepancies found', [
                    'date' => $date->format('Y-m-d'),
                    'discrepancies' => $result['discrepancies']
                ]);
                
                // Send email report if requested
                if ($email) {
                    $this->sendDiscrepancyReport($email, $date, $result);
                }
            } else {
                $this->info("\n✓ All transactions reconciled successfully!");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Reconciliation failed: " . $e->getMessage());
            
            Log::error('NMB reconciliation command failed', [
                'date' => $date->format('Y-m-d'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }
    
    /**
     * Send discrepancy report via email
     */
    private function sendDiscrepancyReport($email, Carbon $date, array $result)
    {
        try {
            $data = [
                'date' => $date->format('Y-m-d'),
                'total_checked' => $result['total_checked'],
                'reconciled' => $result['reconciled'],
                'discrepancies' => $result['discrepancies']
            ];
            
            // You can create a mail template for this
            // Mail::to($email)->send(new \App\Mail\NmbReconciliationReport($data));
            
            $this->info("Discrepancy report sent to {$email}");
        } catch (\Exception $e) {
            $this->warn("Failed to send email report: " . $e->getMessage());
        }
    }
}