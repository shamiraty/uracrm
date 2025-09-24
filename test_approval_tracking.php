<?php
/**
 * Test script to verify loan approval tracking in normalized tables
 * Run with: php test_approval_tracking.php
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\LoanOffer;
use App\Models\LoanOfferApproval;
use Illuminate\Support\Facades\DB;

echo "\n=== LOAN APPROVAL TRACKING TEST ===\n\n";

// 1. Check existing loan offers and their approval records
echo "1. Checking recent loan offers and their approval records:\n";
echo str_repeat("-", 80) . "\n";

$recentLoans = LoanOffer::with(['approvals.approvedBy', 'approvals.rejectedBy'])
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($recentLoans as $loan) {
    echo "Loan ID: {$loan->id}\n";
    echo "Application Number: {$loan->application_number}\n";
    echo "Approval Status: {$loan->approval}\n";
    echo "Approvals in normalized table:\n";
    
    if ($loan->approvals->count() > 0) {
        foreach ($loan->approvals as $approval) {
            echo "  - Type: {$approval->approval_type}, ";
            echo "Status: {$approval->status}, ";
            if ($approval->approved_by) {
                echo "Approved by: " . ($approval->approvedBy->name ?? "User #{$approval->approved_by}") . ", ";
                echo "At: {$approval->approved_at}";
            } elseif ($approval->rejected_by) {
                echo "Rejected by: " . ($approval->rejectedBy->name ?? "User #{$approval->rejected_by}") . ", ";
                echo "At: {$approval->rejected_at}";
            } else {
                echo "Pending";
            }
            echo "\n";
        }
    } else {
        echo "  No approval records found in normalized table\n";
    }
    echo "\n";
}

// 2. Check for mismatches between loan_offers and loan_offer_approvals
echo "\n2. Checking for data consistency:\n";
echo str_repeat("-", 80) . "\n";

// Approved loans without approval records
$approvedWithoutRecords = DB::select("
    SELECT lo.id, lo.application_number, lo.approval
    FROM loan_offers lo
    LEFT JOIN loan_offer_approvals loa ON lo.id = loa.loan_offer_id
    WHERE lo.approval = 'APPROVED'
    AND loa.id IS NULL
    LIMIT 10
");

if (count($approvedWithoutRecords) > 0) {
    echo "⚠️  Found " . count($approvedWithoutRecords) . " approved loans without approval records:\n";
    foreach ($approvedWithoutRecords as $loan) {
        echo "  - Loan ID: {$loan->id}, Application: {$loan->application_number}\n";
    }
} else {
    echo "✅ All approved loans have corresponding approval records\n";
}

// Rejected loans without approval records
$rejectedWithoutRecords = DB::select("
    SELECT lo.id, lo.application_number, lo.approval
    FROM loan_offers lo
    LEFT JOIN loan_offer_approvals loa ON lo.id = loa.loan_offer_id
    WHERE lo.approval = 'REJECTED'
    AND loa.id IS NULL
    LIMIT 10
");

if (count($rejectedWithoutRecords) > 0) {
    echo "⚠️  Found " . count($rejectedWithoutRecords) . " rejected loans without approval records:\n";
    foreach ($rejectedWithoutRecords as $loan) {
        echo "  - Loan ID: {$loan->id}, Application: {$loan->application_number}\n";
    }
} else {
    echo "✅ All rejected loans have corresponding approval records\n";
}

// 3. Summary statistics
echo "\n3. Summary Statistics:\n";
echo str_repeat("-", 80) . "\n";

$stats = [
    'total_loans' => LoanOffer::count(),
    'approved_loans' => LoanOffer::where('approval', 'APPROVED')->count(),
    'rejected_loans' => LoanOffer::where('approval', 'REJECTED')->count(),
    'pending_loans' => LoanOffer::where('approval', 'PENDING')->orWhereNull('approval')->count(),
    'approval_records' => LoanOfferApproval::count(),
    'approved_records' => LoanOfferApproval::where('status', 'approved')->count(),
    'rejected_records' => LoanOfferApproval::where('status', 'rejected')->count(),
    'pending_records' => LoanOfferApproval::where('status', 'pending')->count(),
];

echo "Loan Offers Table:\n";
echo "  Total Loans: {$stats['total_loans']}\n";
echo "  Approved: {$stats['approved_loans']}\n";
echo "  Rejected: {$stats['rejected_loans']}\n";
echo "  Pending: {$stats['pending_loans']}\n";
echo "\n";
echo "Loan Offer Approvals Table:\n";
echo "  Total Records: {$stats['approval_records']}\n";
echo "  Approved: {$stats['approved_records']}\n";
echo "  Rejected: {$stats['rejected_records']}\n";
echo "  Pending: {$stats['pending_records']}\n";

// 4. Test recommendation
echo "\n4. Test Recommendations:\n";
echo str_repeat("-", 80) . "\n";
echo "To test the approval tracking:\n";
echo "1. Go to /loan-offers in your browser\n";
echo "2. Find a pending loan and click Edit\n";
echo "3. Change the Approval field to APPROVED or REJECTED\n";
echo "4. Save the changes\n";
echo "5. Run this script again to verify the loan_offer_approvals table was updated\n";
echo "\nAlternatively, test bulk operations:\n";
echo "1. Select multiple loans in the index page\n";
echo "2. Use the bulk approve/reject action\n";
echo "3. Check that loan_offer_approvals records are created\n";

echo "\n=== END OF TEST ===\n";