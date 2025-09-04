<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Modules\Personnels\Models\Role;

class AssignAdminRoleSeeder extends Seeder
{
    /**
     * Assigne le rôle Admin aux utilisateurs qui ont is_admin = true
     */
    public function run(): void
    {
        // Trouver le rôle Admin
        $adminRole = Role::where('name', 'Admin')->first();

        if (!$adminRole) {
            $this->command->error('❌ Le rôle Admin n\'existe pas. Veuillez d\'abord exécuter AdminRolePermissionsSeeder.');
            return;
        }

        // Trouver tous les utilisateurs avec is_admin = true
        $adminUsers = User::where('is_admin', true)->get();

        if ($adminUsers->isEmpty()) {
            $this->command->info('ℹ️  Aucun utilisateur avec is_admin = true trouvé.');
            return;
        }

        $this->command->info("👥 {$adminUsers->count()} utilisateur(s) administrateur(s) trouvé(s) :");

        foreach ($adminUsers as $user) {
            $hasAdminRole = $user->roles()->where('name', 'Admin')->exists();
            
            $this->command->line("  • {$user->name} ({$user->email}) - " . ($hasAdminRole ? '✅ Rôle Admin déjà assigné' : '⚠️  Rôle Admin manquant'));
            
            if (!$hasAdminRole) {
                $user->roles()->attach($adminRole->id);
                $this->command->info("    → Rôle Admin assigné automatiquement");
            }
        }

        $this->command->info("\n🎉 Attribution des rôles Admin terminée !");
    }
}