<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates based on user roles
        Gate::define('admin-access', function ($user) {
            return $user->isAdmin() || $user->isStaff();
        });
        
        Gate::define('client-access', function ($user) {
            return $user->isClient();
        });
        
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
} 