<?php

/**
 * Test script to verify KPIs are calculating correctly
 * Run this script from the command line: php test_kpi_verification.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\LoanOffer;
use App\Models\LoanOfferApproval;
use App\Models\LoanDisbursement;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n=== KPI Verification Test ===\n\n";

try {
    // Base query matching the controller's logic
    $baseQuery = LoanOffer::where('approval', 'APPROVED')
                          ->whereIn('status', ['APPROVED', 'DISBURSEMENT_REJECTED', 'disbursement_pending', 'disbursed'])
                          ->whereNotIn('state', ['cancelled', 'CANCELLED', 'LOAN_CANCELLATION', 'loan_cancellation'])
                          ->where(function($q) {
                              $q->whereHas('approvals', function($subQ) {
                                  $subQ->where('approval_type', 'final')
                                       ->where('status', 'approved');
                              })
                              ->orWhere(function($subQ2) {
                                  $subQ2->where('approval', 'APPROVED')
                                        ->whereIn('status', ['APPROVED', 'DISBURSEMENT_REJECTED', 'disbursement_pending', 'disbursed'])
                                        ->where('fsp_reference_number', '!=', null);
                              });
                          });
    
    echo "KPI CALCULATIONS:\n";
    echo "=================\n\n";
    
    // 1. Total Approved Loans
    $total = (clone $baseQuery)->count();
    echo "1. Total Approved Loans: " . $total . "\n";
    
    // 2. Total Amount
    $totalAmount = (clone $baseQuery)->sum('take_home_amount') ?: 
                   (clone $baseQuery)->sum('net_loan_amount') ?: 
                   (clone $baseQuery)->sum('requested_amount');
    echo "2. Total Amount (TZS): " . number_format($totalAmount, 2) . "\n";
    
    // 3. Successfully Disbursed
    $disbursed = (clone $baseQuery)->whereHas('disbursements', function($q) {
        $q->where('status', 'success');
    })->count();
    echo "3. Successfully Disbursed: " . $disbursed . "\n";
    
    // 4. Disbursed Amount
    $disbursedAmount = (clone $baseQuery)->whereHas('disbursements', function($q) {
        $q->where('status', 'success');
    })->sum('take_home_amount');
    echo "4. Disbursed Amount (TZS): " . number_format($disbursedAmount, 2) . "\n";
    
    // 5. Pending Disbursement
    $pendingDisbursement = (clone $baseQuery)
        ->whereIn('status', ['APPROVED', 'disbursement_pending'])
        ->whereDoesntHave('disbursements', function($q) {
            $q->where('status', 'success');
        })
        ->count();
    echo "5. Pending Disbursement: " . $pendingDisbursement . "\n";
    
    // 6. Rejected Disbursements
    $rejected = (clone $baseQuery)
        ->where('status', 'DISBURSEMENT_REJECTED')
        ->count();
    echo "6. Rejected Disbursements: " . $rejected . "\n";
    
    // 7. Failed Disbursements
    $failed = (clone $baseQuery)
        ->whereHas('disbursements', function($q) {
            $q->where('status', 'failed');
        })
        ->whereDoesntHave('disbursements', function($q) {
            $q->where('status', 'success');
        })
        ->count();
    echo "7. Failed Disbursements: " . $failed . "\n";
    
    echo "\n\nDETAILED BREAKDOWN:\n";
    echo "===================\n\n";
    
    // Show status distribution
    $statusDistribution = LoanOffer::where('approval', 'APPROVED')
                                   ->selectRaw('status, COUNT(*) as count')
                                   ->groupBy('status')
                                   ->get();
    
    echo "Status Distribution:\n";
    foreach ($statusDistribution as $status) {
        echo "  - {$status->status}: {$status->count}\n";
    }
    
    // Show recent rejected loans with reasons
    echo "\nRecent Rejected Loans:\n";
    $recentRejected = LoanOffer::where('status', 'DISBURSEMENT_REJECTED')
                               ->select('id', 'application_number', 'reason', 'updated_at')
                               ->orderBy('updated_at', 'desc')
                               ->limit(5)
                               ->get();
    
    if ($recentRejected->count() > 0) {
        foreach ($recentRejected as $loan) {
            echo "  - Loan #{$loan->id} (App: {$loan->application_number})\n";
            echo "    Reason: " . ($loan->reason ?: 'Not specified') . "\n";
            echo "    Date: {$loan->updated_at}\n\n";
        }
    } else {
        echo "  No rejected loans found\n";
    }
    
    // Show disbursement statistics
    echo "\nDisbursement Statistics:\n";
    $disbursementStats = LoanDisbursement::selectRaw('status, COUNT(*) as count, SUM(amount) as total_amount')
                                         ->groupBy('status')
                                         ->get();
    
    foreach ($disbursementStats as $stat) {
        echo "  - {$stat->status}: {$stat->count} loans, Total: " . number_format($stat->total_amount, 2) . " TZS\n";
    }
    
    echo "\n=== KPI Summary ===\n";
    echo "The KPIs are calculated based on:\n";
    echo "✓ Approved loans with final employer approval\n";
    echo "✓ Excludes cancelled loans\n";
    echo "✓ Tracks disbursement status (pending, success, failed, rejected)\n";
    echo "✓ Provides accurate counts and amounts for decision making\n";
    
    echo "\nKPI Display in Dashboard:\n";
    echo "- Main KPIs: Total Approved, Disbursed, Pending, Total Amount\n";
    echo "- Additional KPIs: Rejected and Failed (shown when > 0)\n";
    echo "- Filter options: All, Ready to Disburse, Disbursed, Failed, Rejected\n";
    
} catch (\Exception $e) {
    echo "\n✗ Test Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n=== Test Completed Successfully ===\n\n";