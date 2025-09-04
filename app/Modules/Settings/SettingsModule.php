<?php

namespace App\Modules\Settings;

use App\Core\Module\BaseModule;

class SettingsModule extends BaseModule
{
    protected string $name = 'Settings';
    protected string $displayName = 'Administration';
    protected string $description = 'Paramètres système et configuration générale';
    protected string $icon = 'fas fa-cog';
    protected int $order = 90;

    public function getRoutes(): array
    {
        return [
            [
                'name' => 'Modules',
                'route' => 'personnels.modules.index',
                'icon' => 'fas fa-puzzle-piece',
                'permission' => 'manage_system'
            ],
            [
                'name' => 'Paramètres du site',
                'route' => 'personnels.settings.index',
                'icon' => 'fas fa-cog',
                'permission' => 'edit_settings'
            ],
            [
                'name' => 'Mon compte',
                'route' => null,
                'icon' => 'fas fa-user-circle',
                'permission' => null,
                'isPlaceholder' => false,
                'url' => url('personnels/profile')
            ]
        ];
    }

    public function getPermissions(): array
    {
        return [
            'view_settings',
            'edit_settings',
            'manage_system'
        ];
    }

    public function isActive(): bool
    {
        return true; // Toujours actif car core
    }
}