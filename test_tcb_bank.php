<?php
/**
 * Test script to verify TCB bank and loan linkage
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Bank;
use App\Models\LoanOffer;

echo "\n===========================================\n";
echo "TCB BANK VERIFICATION\n";
echo "===========================================\n\n";

// 1. Check if TCB exists in banks table
$tcbBank = Bank::where('swift_code', 'TAPBTZTZ')->first();

if ($tcbBank) {
    echo "✓ TCB Bank found in database:\n";
    echo "  - Name: {$tcbBank->name}\n";
    echo "  - Short Name: {$tcbBank->short_name}\n";
    echo "  - SWIFT Code: {$tcbBank->swift_code}\n";
    echo "  - Bank ID: {$tcbBank->id}\n\n";
    
    // 2. Check loans linked to TCB
    $tcbLoans = LoanOffer::where('bank_id', $tcbBank->id)->count();
    echo "Loans linked to TCB: {$tcbLoans}\n\n";
    
    // 3. Check loans with TCB swift code but no bank_id
    $unlinkedTcb = LoanOffer::where('swift_code', 'TAPBTZTZ')
                            ->whereNull('bank_id')
                            ->count();
    
    if ($unlinkedTcb > 0) {
        echo "⚠ Found {$unlinkedTcb} loans with TCB SWIFT code but no bank_id link\n";
        echo "Linking them now...\n";
        
        $updated = LoanOffer::where('swift_code', 'TAPBTZTZ')
                            ->whereNull('bank_id')
                            ->update(['bank_id' => $tcbBank->id]);
        
        echo "✓ Updated {$updated} loan records\n\n";
    } else {
        echo "✓ All TCB loans are properly linked\n\n";
    }
    
    // 4. Show sample TCB loan
    $sampleLoan = LoanOffer::where('bank_id', $tcbBank->id)
                           ->with('bank')
                           ->first();
    
    if ($sampleLoan) {
        echo "Sample TCB Loan:\n";
        echo "  - Application #: {$sampleLoan->application_number}\n";
        echo "  - Employee: {$sampleLoan->first_name} {$sampleLoan->last_name}\n";
        echo "  - Bank (via relationship): " . ($sampleLoan->bank ? $sampleLoan->bank->short_name : 'Not loaded') . "\n";
        echo "  - SWIFT Code: {$sampleLoan->swift_code}\n";
        echo "  - Bank ID: {$sampleLoan->bank_id}\n";
    }
    
} else {
    echo "✗ TCB Bank not found in database!\n";
    echo "Creating TCB bank entry...\n";
    
    $tcbBank = Bank::create([
        'swift_code' => 'TAPBTZTZ',
        'name' => 'TANZANIA COMMERCIAL BANK PLC',
        'short_name' => 'TCB'
    ]);
    
    echo "✓ TCB Bank created with ID: {$tcbBank->id}\n\n";
    
    // Link existing loans
    $updated = LoanOffer::where('swift_code', 'TAPBTZTZ')
                        ->whereNull('bank_id')
                        ->update(['bank_id' => $tcbBank->id]);
    
    echo "✓ Linked {$updated} existing loans to TCB\n";
}

// 5. Test a specific loan in the browser
echo "\n===========================================\n";
echo "TO TEST IN BROWSER:\n";
echo "===========================================\n";
echo "1. Clear browser cache (Ctrl+F5)\n";
echo "2. Go to loan offers page\n";
echo "3. Look for loans with TCB in the 'Employee Bank' column\n";
echo "4. Click on a TCB loan to open modal\n";
echo "5. Check the Banking Information tab\n";
echo "6. You should see: TCB (TANZANIA COMMERCIAL BANK PLC)\n";
echo "\n";

// 6. Check if banks are being loaded in queries
$testQuery = LoanOffer::with('bank')->where('swift_code', 'TAPBTZTZ')->first();
if ($testQuery && $testQuery->bank) {
    echo "✓ Bank relationship is working correctly\n";
    echo "  When you access \$loanOffer->bank you get:\n";
    echo "  - Name: {$testQuery->bank->name}\n";
    echo "  - Short Name: {$testQuery->bank->short_name}\n";
} else {
    echo "⚠ Bank relationship may not be working\n";
}

echo "\n";