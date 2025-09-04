<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeDirectivesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::if('permission', function ($permission) {
            if (!auth()->check()) {
                return false;
            }
            
            return auth()->user()->hasAccess($permission);
        });
        
        Blade::if('role', function ($role) {
            if (!auth()->check()) {
                return false;
            }
            
            return auth()->user()->hasRole($role);
        });
        
        Blade::if('admin', function () {
            if (!auth()->check()) {
                return false;
            }
            
            return auth()->user()->is_admin;
        });
    }
}