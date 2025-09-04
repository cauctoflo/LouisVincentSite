<?php

namespace App\Modules\Log;

use App\Core\Module\BaseModule;

class LogModule extends BaseModule
{
    protected string $name = 'Log';
    protected string $displayName = 'Journaux d\'Activité';
    protected string $description = 'Suivi et gestion des logs d\'activité';
    protected string $icon = 'fas fa-history';
    protected int $order = 50;

    public function getRoutes(): array
    {
        return [
            [
                'name' => 'Tous les logs',
                'route' => 'personnels.Log.index',
                'icon' => 'fas fa-list',
                'permission' => 'view_logs'
            ],
            [
                'name' => 'Modifications',
                'route' => 'personnels.Log.index',
                'icon' => 'fas fa-edit',
                'permission' => 'view_logs',
                'params' => ['action' => 'update']
            ],
            [
                'name' => 'Vider les logs',
                'route' => null,
                'icon' => 'fas fa-trash-alt',
                'permission' => 'delete_logs',
                'action' => 'clear-logs'
            ]
        ];
    }

    public function getPermissions(): array
    {
        return [
            'view_logs',
            'export_logs',
            'delete_logs',
            'configure_logs'
        ];
    }
}