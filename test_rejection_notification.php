<?php

// Test script to verify loan rejection ESS notification

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\LoanOffer;
use Illuminate\Support\Facades\Log;

// Find a loan to test with
$loanId = $argv[1] ?? null;

if (!$loanId) {
    echo "Usage: php test_rejection_notification.php <loan_id>\n";
    echo "Example: php test_rejection_notification.php 48\n";
    exit(1);
}

$loan = LoanOffer::find($loanId);

if (!$loan) {
    echo "Loan with ID {$loanId} not found.\n";
    exit(1);
}

echo "Testing rejection notification for loan #{$loanId}\n";
echo "Application Number: {$loan->application_number}\n";
echo "Current Approval Status: {$loan->approval}\n";
echo "Current State: {$loan->state}\n";
echo "\n";

// Simulate rejection
echo "Simulating rejection...\n";

// Test the notification directly
use App\Services\NmbDisbursementService;
$nmbService = app(NmbDisbursementService::class);
$controller = new \App\Http\Controllers\EmployeeLoanController($nmbService);

try {
    // Update the loan to REJECTED status
    $loan->approval = 'REJECTED';
    $loan->reason = 'Test rejection - insufficient documentation';
    $loan->state = 'rejected';
    
    // Generate reference numbers if missing
    if (empty($loan->fsp_reference_number)) {
        $loan->fsp_reference_number = 'TZ' . mt_rand(1000, 9999);
    }
    if (empty($loan->loan_number)) {
        $loan->loan_number = (string)mt_rand(100000, 999999);
    }
    
    $loan->save();
    
    echo "Loan updated to REJECTED status.\n";
    echo "Reason: {$loan->reason}\n";
    echo "\n";
    
    // Call the notification method
    echo "Sending ESS notification...\n";
    $result = $controller->notifyEssOnInitialApproval($loanId);
    
    echo "Notification Result: {$result}\n";
    
    // Check logs
    echo "\nCheck the log file at storage/logs/laravel.log for detailed information.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed.\n";