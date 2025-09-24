<?php
/**
 * Script to calculate installments_paid for imported loans
 * This should be run during the import process
 */

require_once 'vendor/autoload.php';

use App\Models\LoanOffer;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

/**
 * Calculate installments paid based on loan start date and current date
 * 
 * @param string $loanStartDate The date when loan started
 * @param string $currentDate Current date (default: today)
 * @return int Number of monthly installments paid
 */
function calculateInstallmentsPaid($loanStartDate, $currentDate = null) {
    $start = Carbon::parse($loanStartDate);
    $current = $currentDate ? Carbon::parse($currentDate) : Carbon::now();
    
    // Calculate months between start and current date
    $monthsPaid = $start->diffInMonths($current);
    
    return $monthsPaid;
}

// Example for URL013572
$loanNumber = 'URL013572';
$loan = LoanOffer::where('loan_number', $loanNumber)->first();

if ($loan) {
    // If contract_start_date is available, use it
    // Otherwise, estimate based on other available dates
    $startDate = $loan->contract_start_date ?? $loan->employment_date ?? '2024-01-01'; // Default or estimated start
    
    // Calculate installments paid
    $installmentsPaid = calculateInstallmentsPaid($startDate);
    
    // For imported loans, you might also calculate it from the balance difference
    // If initial_balance and current_balance are known:
    $initial_balance = (float)$loan->requested_amount; // 6,397,076
    $monthly_payment = (float)($loan->desired_deductible_amount ?? $loan->total_employee_deduction ?? 177697);
    
    // Alternative calculation: installments = (initial - current) / monthly_payment
    // But we need the current balance from somewhere
    
    // For URL013572, based on the Excel data showing ded_balance of 5,508,591:
    // installments_paid = (6,397,076 - 5,508,591) / 177,697 = 5
    if ($monthly_payment > 0) {
        $paidAmount = $initial_balance - 5508591; // ded_balance from Excel
        $calculatedInstallments = round($paidAmount / $monthly_payment);
        
        echo "Loan: $loanNumber\n";
        echo "Initial Balance: " . number_format($initial_balance, 2) . "\n";
        echo "Monthly Payment: " . number_format($monthly_payment, 2) . "\n";
        echo "Calculated Installments Paid: $calculatedInstallments\n";
        
        // Update the database
        $loan->installments_paid = $calculatedInstallments;
        $loan->save();
        
        echo "Updated installments_paid to: $calculatedInstallments\n";
    }
} else {
    echo "Loan $loanNumber not found\n";
}

echo "\n=== Formula for Import Process ===\n";
echo "When importing from ESS Excel:\n";
echo "1. Read initial_balance from Excel\n";
echo "2. Read ded_balance_amount from Excel\n";
echo "3. Read amount (monthly payment) from Excel\n";
echo "4. Calculate: installments_paid = (initial_balance - ded_balance_amount) / monthly_payment\n";
echo "5. Store this value in the installments_paid field\n";