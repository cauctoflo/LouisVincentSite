<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Personnels\Models\Role;
use App\Modules\Personnels\Models\Permission;

class AdminRolePermissionsSeeder extends Seeder
{
    /**
     * Assigne toutes les permissions au rôle Admin
     */
    public function run(): void
    {
        // Trouver ou créer le rôle Admin
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            [
                'description' => 'Rôle administrateur avec accès complet au système',
                'is_default' => false
            ]
        );

        // Récupérer toutes les permissions
        $allPermissions = Permission::all();

        if ($allPermissions->isEmpty()) {
            $this->command->warn('Aucune permission trouvée. Veuillez d\'abord exécuter le PermissionsSeeder.');
            return;
        }

        // Assigner toutes les permissions au rôle Admin
        $adminRole->permissions()->sync($allPermissions->pluck('id')->toArray());

        $this->command->info("✅ Rôle Admin créé/mis à jour avec {$allPermissions->count()} permissions assignées.");
        
        // Afficher un résumé des permissions par module
        $permissionsByModule = $allPermissions->groupBy('module');
        $this->command->info("\n📋 Résumé des permissions assignées :");
        
        foreach ($permissionsByModule as $module => $permissions) {
            $this->command->line("  • {$module}: {$permissions->count()} permissions");
        }

        // Vérifier si des utilisateurs admin existent sans ce rôle
        $adminUsers = \App\Models\User::where('is_admin', true)->get();
        
        if ($adminUsers->isNotEmpty()) {
            $this->command->info("\n👥 Utilisateurs administrateurs détectés :");
            foreach ($adminUsers as $user) {
                $hasAdminRole = $user->roles()->where('name', 'Admin')->exists();
                $this->command->line("  • {$user->name} ({$user->email}) - " . ($hasAdminRole ? '✅ Rôle Admin assigné' : '⚠️  Rôle Admin manquant'));
                
                // Optionnel: assigner automatiquement le rôle Admin aux utilisateurs is_admin=true
                if (!$hasAdminRole) {
                    $user->roles()->attach($adminRole->id);
                    $this->command->info("    → Rôle Admin assigné automatiquement");
                }
            }
        }
        
        $this->command->info("\n🎉 Configuration des permissions Admin terminée avec succès !");
    }
}