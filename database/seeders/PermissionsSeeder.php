<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Personnels\Models\Permission;
use App\Modules\Personnels\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'pages' => [
                'view_pages' => 'Voir les pages',
                'create_pages' => 'Créer des pages',
                'edit_pages' => 'Modifier les pages',
                'delete_pages' => 'Supprimer les pages',
                'publish_pages' => 'Publier les pages',
            ],
            'folders' => [
                'view_folders' => 'Voir les dossiers',
                'create_folders' => 'Créer des dossiers',
                'edit_folders' => 'Modifier les dossiers',
                'delete_folders' => 'Supprimer les dossiers',
            ],
            'drive' => [
                'view_drive' => 'Accéder au drive',
                'upload_files' => 'Télécharger des fichiers',
                'download_files' => 'Télécharger des fichiers',
                'create_folders' => 'Créer des dossiers dans le drive',
                'delete_folders' => 'Supprimer des dossiers du drive',
                'delete_files' => 'Supprimer des fichiers',
                'rename_files' => 'Renommer des fichiers',
                'move_files' => 'Déplacer des fichiers',
            ],
            'images' => [
                'view_images' => 'Voir les images',
                'upload_images' => 'Télécharger des images',
                'delete_images' => 'Supprimer des images',
                'edit_images' => 'Modifier les images',
                'organize_images' => 'Organiser les images',
            ],
            'users' => [
                'view_users' => 'Voir les utilisateurs',
                'create_users' => 'Créer des utilisateurs',
                'edit_users' => 'Modifier les utilisateurs',
                'delete_users' => 'Supprimer des utilisateurs',
                'manage_user_roles' => 'Gérer les rôles utilisateur',
            ],
            'roles' => [
                'view_roles' => 'Voir les rôles',
                'create_roles' => 'Créer des rôles',
                'edit_roles' => 'Modifier les rôles',
                'delete_roles' => 'Supprimer des rôles',
                'assign_permissions' => 'Assigner des permissions',
            ],
            'logs' => [
                'view_logs' => 'Voir les logs',
                'export_logs' => 'Exporter les logs',
                'delete_logs' => 'Supprimer les logs',
                'configure_logs' => 'Configurer le système de logs',
            ],
            'settings' => [
                'view_settings' => 'Voir les paramètres',
                'edit_settings' => 'Modifier les paramètres',
                'manage_system' => 'Gérer le système',
            ],
            'agenda' => [
                'view_agenda' => 'Voir l\'agenda',
                'create_events' => 'Créer des événements',
                'edit_events' => 'Modifier des événements',
                'delete_events' => 'Supprimer des événements',
            ]
        ];

        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $slug => $name) {
                Permission::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'module' => $module,
                        'action' => str_replace($module . '_', '', $slug),
                        'description' => $name . ' dans le module ' . ucfirst($module)
                    ]
                );
            }
        }

        $this->createDefaultRoles();
    }

    private function createDefaultRoles()
    {
        $adminRole = Role::updateOrCreate(
            ['name' => 'Administrateur'],
            [
                'description' => 'Accès complet à toutes les fonctionnalités',
                'is_default' => false
            ]
        );

        $editorRole = Role::updateOrCreate(
            ['name' => 'Éditeur'],
            [
                'description' => 'Peut créer et modifier du contenu',
                'is_default' => false
            ]
        );

        $viewerRole = Role::updateOrCreate(
            ['name' => 'Observateur'],
            [
                'description' => 'Accès en lecture seule',
                'is_default' => true
            ]
        );

        $contributorRole = Role::updateOrCreate(
            ['name' => 'Contributeur'],
            [
                'description' => 'Peut contribuer au contenu avec des permissions limitées',
                'is_default' => false
            ]
        );

        $adminPermissions = Permission::all()->pluck('id')->toArray();
        $adminRole->permissions()->sync($adminPermissions);

        $editorPermissions = Permission::whereIn('slug', [
            'view_pages', 'create_pages', 'edit_pages', 'publish_pages',
            'view_folders', 'create_folders', 'edit_folders',
            'view_drive', 'upload_files', 'download_files', 'create_folders', 'rename_files', 'move_files',
            'view_images', 'upload_images', 'edit_images', 'organize_images',
            'view_agenda', 'create_events', 'edit_events', 'delete_events'
        ])->pluck('id')->toArray();
        $editorRole->permissions()->sync($editorPermissions);

        $viewerPermissions = Permission::whereIn('slug', [
            'view_pages', 'view_folders', 'view_drive', 'download_files',
            'view_images', 'view_agenda', 'view_users'
        ])->pluck('id')->toArray();
        $viewerRole->permissions()->sync($viewerPermissions);

        $contributorPermissions = Permission::whereIn('slug', [
            'view_pages', 'create_pages', 'edit_pages',
            'view_folders', 'view_drive', 'upload_files', 'download_files',
            'view_images', 'upload_images', 'view_agenda', 'create_events'
        ])->pluck('id')->toArray();
        $contributorRole->permissions()->sync($contributorPermissions);
    }
}