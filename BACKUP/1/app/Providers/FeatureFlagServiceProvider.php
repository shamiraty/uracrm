<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FeatureFlagServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register feature flag configuration
        $this->app->singleton('feature.flags', function ($app) {
            return [
                'use_normalized_approvals' => env('USE_NORMALIZED_APPROVALS', false),
                'use_normalized_disbursements' => env('USE_NORMALIZED_DISBURSEMENTS', false),
                'use_swift_code_mapping' => env('USE_SWIFT_CODE_MAPPING', false),
                'use_improved_loan_controller' => env('USE_IMPROVED_LOAN_CONTROLLER', false),
                'use_improved_disbursement' => env('USE_IMPROVED_DISBURSEMENT', false),
                'use_improved_nmb_service' => env('USE_IMPROVED_NMB_SERVICE', false),
            ];
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Make feature flags available in config
        config([
            'features.use_normalized_approvals' => env('USE_NORMALIZED_APPROVALS', false),
            'features.use_normalized_disbursements' => env('USE_NORMALIZED_DISBURSEMENTS', false),
            'features.use_swift_code_mapping' => env('USE_SWIFT_CODE_MAPPING', false),
            'features.use_improved_loan_controller' => env('USE_IMPROVED_LOAN_CONTROLLER', false),
            'features.use_improved_disbursement' => env('USE_IMPROVED_DISBURSEMENT', false),
            'features.use_improved_nmb_service' => env('USE_IMPROVED_NMB_SERVICE', false),
        ]);
        
        // Log which features are enabled (useful for debugging)
        if (config('app.debug')) {
            $features = config('features');
            foreach ($features as $key => $value) {
                if ($value) {
                    \Log::info("Feature flag enabled: {$key}");
                }
            }
        }
    }
}