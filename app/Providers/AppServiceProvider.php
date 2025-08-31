<?php

namespace App\Providers;

use App\Models\DivisionDeadline;
use App\Observers\DivisionDeadlineObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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

    }
}
