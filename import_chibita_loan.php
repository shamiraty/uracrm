<?php

require_once 'vendor/autoload.php';

use App\Models\LoanOffer;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find the loan
$loanNumber = 'URL013572';
$loan = LoanOffer::where('loan_number', $loanNumber)->first();

if (!$loan) {
    echo "Loan not found: $loanNumber\n";
    exit(1);
}

echo "Current loan data:\n";
echo "Loan Number: " . $loan->loan_number . "\n";
echo "Requested Amount: " . number_format($loan->requested_amount, 2) . "\n";
echo "Settlement Amount: " . number_format($loan->settlement_amount, 2) . "\n";
echo "Outstanding Balance: " . number_format($loan->outstanding_balance, 2) . "\n";
echo "\n";

// The correct payoff amount based on your requirements
$correctPayoffAmount = 3774876.79;

echo "Updating to correct payoff amount: " . number_format($correctPayoffAmount, 2) . "\n";

// Update the loan with correct values
$loan->settlement_amount = $correctPayoffAmount;
$loan->outstanding_balance = $correctPayoffAmount;

// Store the ded_balance_amount from Excel if needed
$loan->initial_balance = 6397076.00;  // from Excel
$loan->balance_amount = 4264712.07;   // from screenshot

$loan->save();

echo "Loan updated successfully!\n";
echo "New settlement amount: " . number_format($loan->settlement_amount, 2) . "\n";
echo "New outstanding balance: " . number_format($loan->outstanding_balance, 2) . "\n";