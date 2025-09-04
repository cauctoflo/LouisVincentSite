<?php

namespace App\Modules\Internat;

use App\Core\Module\BaseModule;

class InternatModule extends BaseModule
{
    protected string $name = 'Internat';
    protected string $displayName = 'Internat';
    protected string $description = 'Gestion de l\'internat et des pensionnaires';
    protected string $icon = 'fas fa-home';
    protected int $order = 60;

    public function getRoutes(): array
    {
        return [
            [
                'name' => 'Module en développement',
                'route' => null,
                'icon' => 'fas fa-tools',
                'permission' => null,
                'isPlaceholder' => true
            ]
        ];
    }

    public function getPermissions(): array
    {
        return [
            'view_internat',
            'manage_internat'
        ];
    }
}