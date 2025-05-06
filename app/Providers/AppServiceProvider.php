<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\TimeLog;
use App\Observers\TimeLogObserver;

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
        TimeLog::observe(TimeLogObserver::class);
    }
}
