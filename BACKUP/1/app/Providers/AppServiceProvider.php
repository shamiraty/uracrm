<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\Enquiry;
use App\Models\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compose all views with unread notifications data, potentially caching this data.
        view()->composer('*', function ($view) {
            // Using caching to improve performance by reducing database hits for frequently accessed data
            $notifications = Cache::remember('notifications.unread', now()->addMinutes(10), function () {
                return Notification::where('is_read', false)->orderBy('created_at', 'desc')->get();
            });

            $view->with('notifications', $notifications);
        });

        // Compose the sidebar with payment types only where it is necessary.
        View::composer('body.sidebar', function ($view) {
            $paymentTypes = collect();
            if (auth()->check() && auth()->user()->can('view_payments')) { // Ensuring only authorized users see this
                $paymentTypes = Enquiry::select('type')
                                       ->distinct()
                                       ->whereIn('type', [
                                           'refund',
                                           'retirement',
                                           'withdraw_savings',
                                           'benefit_from_disasters',
                                           'deduction_add',
                                           'share_enquiry',
                               'retirement',
                               'withdraw_deposit',
                               'unjoin_membership'

                                       ])
                                       ->get();

                $view->with('paymentTypes', $paymentTypes);
            }
        });
    }
}
