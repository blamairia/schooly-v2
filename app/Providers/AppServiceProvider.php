<?php

namespace App\Providers;

use App\Models\DivisionDeadline;
use App\Observers\DivisionDeadlineObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

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
        //
        DivisionDeadline::observe(DivisionDeadlineObserver::class);
        Schema::defaultStringLength(191);

        // Force HTTPS in production (Azure App Service)
        if (request()->server->get('HTTP_X_ARR_SSL') || $this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
