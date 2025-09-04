<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Module\ModuleRegistry;

// Modules
use App\Modules\Core\CoreModule;
use App\Modules\Personnels\PersonnelsModule;
use App\Modules\Pages\PagesModule;
use App\Modules\ImageAPI\ImageAPIModule;
use App\Modules\Log\LogModule;
use App\Modules\Agenda\AgendaModule;
use App\Modules\Internat\InternatModule;
use App\Modules\Settings\SettingsModule;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Enregistrement des modules
        ModuleRegistry::register(new CoreModule());
        ModuleRegistry::register(new PersonnelsModule());
        ModuleRegistry::register(new PagesModule());
        ModuleRegistry::register(new ImageAPIModule());
        ModuleRegistry::register(new LogModule());
        ModuleRegistry::register(new AgendaModule());
        ModuleRegistry::register(new InternatModule());
        ModuleRegistry::register(new SettingsModule());

        // Partager les modules avec toutes les vues
        view()->composer('*', function ($view) {
            $view->with('moduleRegistry', ModuleRegistry::getModulesForSidebar());
        });
    }
}