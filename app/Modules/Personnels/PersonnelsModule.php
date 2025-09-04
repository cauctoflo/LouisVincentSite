<?php

namespace App\Modules\Personnels;

use App\Core\Module\BaseModule;

class PersonnelsModule extends BaseModule
{
    protected string $name = 'Personnels';
    protected string $displayName = 'Gestion des Utilisateurs';
    protected string $description = 'Gestion des utilisateurs, rôles et permissions';
    protected string $icon = 'fas fa-users';
    protected int $order = 20;

    public function getRoutes(): array
    {
        return [
            [
                'name' => 'Liste des utilisateurs',
                'route' => 'personnels.personnels.index',
                'icon' => 'fas fa-users',
                'permission' => 'view_users'
            ],
            [
                'name' => 'Ajouter un utilisateur',
                'route' => 'personnels.personnels.create',
                'icon' => 'fas fa-user-plus',
                'permission' => 'create_users'
            ],
            [
                'name' => 'Rôles et permissions',
                'route' => 'personnels.roles-permissions.index',
                'icon' => 'fas fa-shield-alt',
                'permission' => 'view_roles'
            ]
        ];
    }

    public function getPermissions(): array
    {
        return [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_roles',
            'manage_user_roles'
        ];
    }

    public function isActive(): bool
    {
        return true; // Toujours actif car core
    }
}