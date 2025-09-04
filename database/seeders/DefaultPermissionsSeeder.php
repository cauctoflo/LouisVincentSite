<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DefaultPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions par défaut pour le système de pages
        $defaultPermissions = [
            // Pages
            'pages.view',
            'pages.create', 
            'pages.edit',
            'pages.delete',
            'pages.publish',
            'pages.view_drafts',
            
            // Utilisateurs
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Rôles et permissions
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            
            // Images
            'images.view',
            'images.upload',
            'images.delete',
            
            // Logs
            'logs.view',
            'logs.clear',
            
            // Paramètres
            'settings.view',
            'settings.edit'
        ];
        
        // Assigner toutes les permissions aux admins existants
        User::where('is_admin', true)->each(function ($admin) use ($defaultPermissions) {
            $admin->update(['permissions' => $defaultPermissions]);
            $this->command->info("Permissions attribuées à l'admin: {$admin->name}");
        });
        
        $this->command->info('Permissions par défaut créées avec succès.');
    }
}