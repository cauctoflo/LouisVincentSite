<?php

namespace App\Modules\Agenda;

use App\Core\Module\BaseModule;

class AgendaModule extends BaseModule
{
    protected string $name = 'Agenda';
    protected string $displayName = 'Agenda';
    protected string $description = 'Gestion des événements et planning';
    protected string $icon = 'fas fa-calendar-alt';
    protected int $order = 35;

    public function getRoutes(): array
    {
        return [
            [
                'name' => 'Paramètres agenda',
                'route' => 'personnels.Agenda.settings',
                'icon' => 'fas fa-calendar',
                'permission' => 'view_agenda'
            ]
        ];
    }

    public function getPermissions(): array
    {
        return [
            'view_agenda',
            'create_events',
            'edit_events',
            'delete_events'
        ];
    }
}