<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImprovedEmployeeLoanController;

/*
|--------------------------------------------------------------------------
| Improved Loan Routes
|--------------------------------------------------------------------------
|
| Routes for the improved loan system with better architecture
| These routes use the ImprovedEmployeeLoanController
|
*/

// ESS API Integration endpoint - this is the main entry point for ESS
Route::post('/api/employee_loan_v2', [ImprovedEmployeeLoanController::class, 'handleRequest'])
    ->name('employee-loan.v2.handle');

// Disbursement processing
Route::post('/api/loan-offers/{loanOfferId}/disburse', [ImprovedEmployeeLoanController::class, 'processDisbursement'])
    ->name('loan-offers.v2.disburse');

// Web routes for loan management (protected by auth)
Route::middleware(['auth'])->group(function () {
    
    // Loan offers management
    Route::prefix('v2/loan-offers')->name('loan-offers.v2.')->group(function () {
        
        // List and search
        Route::get('/', [ImprovedEmployeeLoanController::class, 'index'])->name('index');
        Route::get('/pending', [ImprovedEmployeeLoanController::class, 'pendingLoans'])->name('pending');
        Route::get('/approved', [ImprovedEmployeeLoanController::class, 'approvedLoans'])->name('approved');
        Route::get('/rejected', [ImprovedEmployeeLoanController::class, 'rejectedLoans'])->name('rejected');
        Route::get('/disbursed', [ImprovedEmployeeLoanController::class, 'disbursedLoans'])->name('disbursed');
        
        // Individual loan management
        Route::get('/{id}', [ImprovedEmployeeLoanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ImprovedEmployeeLoanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ImprovedEmployeeLoanController::class, 'update'])->name('update');
        
        // Disbursement and approval
        Route::post('/{id}/approve', [ImprovedEmployeeLoanController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [ImprovedEmployeeLoanController::class, 'reject'])->name('reject');
        Route::post('/{id}/disburse', [ImprovedEmployeeLoanController::class, 'processDisbursement'])->name('disburse');
        
        // Reports and analytics
        Route::get('/reports/weekly-stats', [ImprovedEmployeeLoanController::class, 'weeklyStats'])->name('weekly-stats');
        Route::get('/reports/kpi-details', [ImprovedEmployeeLoanController::class, 'kpiDetails'])->name('kpi-details');
        Route::get('/reports/export', [ImprovedEmployeeLoanController::class, 'export'])->name('export');
    });
    
    // Disbursement tracking
    Route::prefix('v2/disbursements')->name('disbursements.v2.')->group(function () {
        Route::get('/', [ImprovedEmployeeLoanController::class, 'disbursementsList'])->name('index');
        Route::get('/pending', [ImprovedEmployeeLoanController::class, 'pendingDisbursements'])->name('pending');
        Route::get('/successful', [ImprovedEmployeeLoanController::class, 'successfulDisbursements'])->name('successful');
        Route::get('/failed', [ImprovedEmployeeLoanController::class, 'failedDisbursements'])->name('failed');
        Route::get('/{id}', [ImprovedEmployeeLoanController::class, 'disbursementDetails'])->name('show');
    });
});

// NMB callback endpoint (no auth required as it's called by NMB)
Route::post('/api/nmb/callback/v2', [ImprovedEmployeeLoanController::class, 'nmbCallback'])
    ->name('nmb.callback.v2');