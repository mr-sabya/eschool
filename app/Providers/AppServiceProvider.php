<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Set default string length for older MySQL versions
        Schema::defaultStringLength(191);

        // Use a try-catch block to prevent errors during migrations
        // when the 'settings' table might not exist yet.
        try {
            // Check if settings are already cached
            $settings = Cache::rememberForever('settings', function () {
                // If not cached, fetch from the database (we assume ID 1)
                return Setting::find(1);
            });

            // Share the settings variable with all views
            View::share('settings', $settings);
        } catch (\Exception $e) {
            // If an error occurs (like the table not existing),
            // share a null or empty object so views don't break.
            View::share('settings', null);
        }
    }
}
