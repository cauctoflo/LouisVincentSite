<?php

namespace App\Modules\Pages;

use App\Core\Module\BaseModule;

class PagesModule extends BaseModule
{
    protected string $name = 'Pages';
    protected string $displayName = 'Gestion de Contenu';
    protected string $description = 'Gestion des pages, sections et dossiers avec Editor.js';
    protected string $icon = 'fas fa-file-alt';
    protected int $order = 30;

    public function getRoutes(): array
    {
        return [
            [
                'name' => 'Tableau de bord',
                'route' => 'personnels.pages.manager',
                'icon' => 'fas fa-tachometer-alt',
                'permission' => 'view_pages'
            ],
            [
                'name' => 'Toutes les pages',
                'route' => 'personnels.pages.pages.index',
                'icon' => 'fas fa-list',
                'permission' => 'view_pages'
            ],
            [
                'name' => 'Nouvelle page',
                'route' => 'personnels.pages.pages.create',
                'icon' => 'fas fa-plus',
                'permission' => 'create_pages'
            ],
            [
                'name' => 'Sections',
                'route' => 'personnels.pages.sections.index',
                'icon' => 'fas fa-layer-group',
                'permission' => 'view_pages'
            ],
            [
                'name' => 'Dossiers',
                'route' => 'personnels.pages.folders.index',
                'icon' => 'fas fa-folder',
                'permission' => 'view_folders'
            ]
        ];
    }

    public function getPermissions(): array
    {
        return [
            'view_pages',
            'create_pages',
            'edit_pages',
            'delete_pages',
            'publish_pages',
            'view_folders',
            'create_folders',
            'edit_folders',
            'delete_folders'
        ];
    }
}