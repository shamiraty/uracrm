<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeLoanController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/employee_loan', [EmployeeLoanController::class, 'handleRequest']);

// Weekly statistics for loan offers
Route::get('/loan-offers/weekly-stats', [EmployeeLoanController::class, 'weeklyStats'])
     ->name('api.loan-offers.weekly-stats');

// KPI details with period support
Route::get('/loan-offers/kpi-details', [EmployeeLoanController::class, 'getKPIDetails'])
     ->name('api.loan-offers.kpi-details');

// Filter counts for quick filters
Route::get('/loan-offers/filter-counts', [EmployeeLoanController::class, 'getFilterCounts'])
     ->name('api.loan-offers.filter-counts');

Route::get('/trigger-decommission', [App\Http\Controllers\ApiController::class, 'triggerDecommission'])
     ->name('trigger.decommission');
     
Route::get('/send-product-details', [App\Http\Controllers\ApiController::class, 'sendProductDetails']);