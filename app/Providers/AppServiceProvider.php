<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        // Share hospital settings with all views
        try {
            View::composer('*', function ($view) {
                $view->with('hospitalName', Setting::get('hospital_name', 'Hospital HMS'));
                $view->with('currencySymbol', Setting::get('currency_symbol', '$'));
            });
        } catch (\Exception $e) {
            // Database may not be migrated yet
        }
    }
}
