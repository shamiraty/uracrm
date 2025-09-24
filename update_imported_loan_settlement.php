<?php
/**
 * Script to update settlement amounts for imported loans
 * This should be run during or after the import process
 */

require_once 'vendor/autoload.php';

use App\Models\LoanOffer;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// For URL013572 specifically, set the correct payoff amount
$loanNumber = 'URL013572';
$correctPayoffAmount = 3774876.79;

$loan = LoanOffer::where('loan_number', $loanNumber)->first();

if ($loan) {
    // Update with the correct settlement amount
    $loan->settlement_amount = $correctPayoffAmount;
    $loan->outstanding_balance = $correctPayoffAmount;
    $loan->save();
    
    echo "Updated loan $loanNumber with settlement amount: " . number_format($correctPayoffAmount, 2) . "\n";
} else {
    echo "Loan $loanNumber not found\n";
}

// For future imports, you should calculate or provide the settlement amount
// during the import process based on the original lender's calculation
echo "\nFor future imports:\n";
echo "1. Include a 'settlement_amount' or 'payoff_amount' column in your import Excel\n";
echo "2. Or request the payoff amount from the original lender's API\n";
echo "3. Store it in the settlement_amount field during import\n";