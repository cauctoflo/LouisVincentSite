<?php

namespace App\Modules\Core;

use App\Core\Module\BaseModule;

class CoreModule extends BaseModule
{
    protected string $name = 'Core';
    protected string $displayName = 'Tableau de Bord';
    protected string $description = 'Module principal avec vue d\'ensemble et dashboard';
    protected string $icon = 'fas fa-table-columns';
    protected int $order = 1;

    public function getRoutes(): array
    {
        return [
            [
                'name' => 'Vue d\'ensemble',
                'route' => 'personnels.index',
                'icon' => 'far fa-chart-bar',
                'permission' => null
            ]
        ];
    }
}