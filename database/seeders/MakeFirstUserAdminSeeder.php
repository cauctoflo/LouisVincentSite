<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Modules\Personnels\Models\Role;

class MakeFirstUserAdminSeeder extends Seeder
{
    /**
     * Définit le premier utilisateur comme admin et lui assigne le rôle Admin
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->error('❌ Aucun utilisateur trouvé dans la base de données.');
            return;
        }

        // Définir comme admin
        $user->is_admin = true;
        $user->save();

        $this->command->info("✅ Utilisateur {$user->name} ({$user->email}) défini comme administrateur.");

        // Assigner le rôle Admin
        $adminRole = Role::where('name', 'Admin')->first();
        
        if ($adminRole) {
            $hasAdminRole = $user->roles()->where('name', 'Admin')->exists();
            
            if (!$hasAdminRole) {
                $user->roles()->attach($adminRole->id);
                $this->command->info("✅ Rôle Admin assigné à {$user->name}.");
            } else {
                $this->command->info("ℹ️  L'utilisateur {$user->name} a déjà le rôle Admin.");
            }
        } else {
            $this->command->warn("⚠️  Le rôle Admin n'existe pas. Exécutez d'abord AdminRolePermissionsSeeder.");
        }

        $this->command->info("\n🎉 Configuration de l'utilisateur admin terminée !");
    }
}