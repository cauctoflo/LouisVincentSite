<?php

namespace App\Modules\Personnels\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Définir les gates pour les permissions
        Gate::before(function ($user, $ability) {
            if ($user instanceof User && $user->is_admin) {
                return true;
            }
        });
        
        // Définir les directives Blade
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });
        
        Blade::if('hasAccess', function ($permission) {
            return auth()->check() && auth()->user()->hasAccess($permission);
        });
    }
} 