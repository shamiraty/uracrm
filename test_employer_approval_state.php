<?php

// Test script to verify employer approval state transition

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
    echo "Usage: php test_employer_approval_state.php <loan_id>\n";
    echo "Example: php test_employer_approval_state.php 48\n";
    exit(1);
}

$loan = LoanOffer::find($loanId);

if (!$loan) {
    echo "Loan with ID {$loanId} not found.\n";
    exit(1);
}

echo "Testing employer approval state transition for loan #{$loanId}\n";
echo "Application Number: {$loan->application_number}\n";
echo "Current Approval Status: {$loan->approval}\n";
echo "Current State: {$loan->state}\n";
echo "\n";

// Simulate employer approval from ESS
echo "Simulating employer approval from ESS...\n";

// First, set the loan to "Loan Offer at employee" state (as if FSP approved it)
$loan->state = 'Loan Offer at employee';
$loan->approval = 'PENDING'; // Reset approval to pending
$loan->save();

echo "Set initial state to: {$loan->state}\n";

// Now simulate the LOAN_FINAL_APPROVAL_NOTIFICATION from ESS
$controller = new \App\Http\Controllers\EmployeeLoanController(
    app(\App\Services\NmbDisbursementService::class)
);

// Create XML for employer approval
$xml = new SimpleXMLElement('<Document/>');
$data = $xml->addChild('Data');
$header = $data->addChild('Header');
$header->addChild('Sender', 'ESS_UTUMISHI');
$header->addChild('Receiver', 'URA SACCOS');
$header->addChild('FSPCode', 'FL7456');
$header->addChild('MsgId', uniqid());
$header->addChild('MessageType', 'LOAN_FINAL_APPROVAL_NOTIFICATION');

$messageDetails = $data->addChild('MessageDetails');
$messageDetails->addChild('ApplicationNumber', $loan->application_number);
$messageDetails->addChild('Approval', 'APPROVED');
$messageDetails->addChild('Reason', 'Employer approved via test script');
$messageDetails->addChild('FSPReferenceNumber', $loan->fsp_reference_number ?: 'TZ' . mt_rand(1000, 9999));
$messageDetails->addChild('LoanNumber', $loan->loan_number ?: (string)mt_rand(100000, 999999));

echo "\nProcessing employer approval notification...\n";

// Use reflection to call the private method
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('handleLoanFinalApprovalNotification');
$method->setAccessible(true);

try {
    $result = $method->invoke($controller, $xml);
    echo "Notification processed.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Reload the loan to see the changes
$loan->refresh();

echo "\n--- After Employer Approval ---\n";
echo "Approval Status: {$loan->approval}\n";
echo "State: {$loan->state}\n";
echo "Status: {$loan->status}\n";

// Check if state is correctly set
if ($loan->state === 'Submitted for disbursement') {
    echo "\n✓ SUCCESS: State correctly updated to 'Submitted for disbursement'\n";
} else {
    echo "\n✗ FAILURE: State is '{$loan->state}', expected 'Submitted for disbursement'\n";
}

echo "\nTest completed.\n";