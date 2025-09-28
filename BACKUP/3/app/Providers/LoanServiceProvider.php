<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\EmployeeLoanController;
use App\Http\Controllers\ImprovedEmployeeLoanController;
use App\Services\NmbDisbursementService;
use App\Services\ImprovedNmbDisbursementService;

class LoanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the appropriate controller based on configuration
        if (config('loan.use_improved_controller', false)) {
            $this->app->bind(
                'App\Http\Controllers\IEmployeeLoanController',
                ImprovedEmployeeLoanController::class
            );
        } else {
            $this->app->bind(
                'App\Http\Controllers\IEmployeeLoanController',
                EmployeeLoanController::class
            );
        }
        
        // Bind the appropriate disbursement service
        // Comment out for now as ImprovedNmbDisbursementService doesn't extend NmbDisbursementService
        // if (config('loan.disbursement_service.use_improved', false)) {
        //     $this->app->bind(
        //         NmbDisbursementService::class,
        //         ImprovedNmbDisbursementService::class
        //     );
        // }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Log which controller is being used
        if (config('loan.use_improved_controller', false)) {
            \Log::info('Using ImprovedEmployeeLoanController for ESS integration');
        } else {
            \Log::info('Using standard EmployeeLoanController for ESS integration');
        }
    }
}