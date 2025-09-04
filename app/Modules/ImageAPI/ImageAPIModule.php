<?php

namespace App\Modules\ImageAPI;

use App\Core\Module\BaseModule;

class ImageAPIModule extends BaseModule
{
    protected string $name = 'ImageAPI';
    protected string $displayName = 'Drive Images';
    protected string $description = 'Gestionnaire d\'images et de fichiers média';
    protected string $icon = 'fas fa-images';
    protected int $order = 40;

    public function getRoutes(): array
    {
        return [
            [
                'name' => 'Gestionnaire d\'images',
                'route' => 'personnels.ImageAPI.index',
                'icon' => 'fas fa-images',
                'permission' => 'view_images'
            ]
        ];
    }

    public function getPermissions(): array
    {
        return [
            'view_images',
            'upload_images',
            'delete_images',
            'edit_images',
            'organize_images'
        ];
    }
}