<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Pages\Models\Section;
use App\Models\User;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();
        
        if (!$admin) {
            $this->command->error('Aucun administrateur trouvé. Créez d\'abord un utilisateur administrateur.');
            return;
        }

        $sections = [
            [
                'name' => 'Actualités',
                'slug' => 'actualites',
                'description' => 'Toutes les dernières nouvelles et événements du lycée',
                'icon' => 'fas fa-newspaper',
                'color' => 'blue',
                'display_order' => 1,
                'is_active' => true,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Section Européenne',
                'slug' => 'section-europeenne',
                'description' => 'Développez vos compétences linguistiques et culturelles avec notre section européenne reconnue',
                'icon' => 'fas fa-flag',
                'color' => 'indigo',
                'display_order' => 2,
                'is_active' => true,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Sciences',
                'slug' => 'sciences',
                'description' => 'Découvrez les filières scientifiques et les projets de recherche',
                'icon' => 'fas fa-flask',
                'color' => 'green',
                'display_order' => 3,
                'is_active' => true,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Arts & Culture',
                'slug' => 'arts-culture',
                'description' => 'Explorez le monde artistique et culturel du lycée',
                'icon' => 'fas fa-palette',
                'color' => 'purple',
                'display_order' => 4,
                'is_active' => true,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Toutes les activités sportives et compétitions',
                'icon' => 'fas fa-running',
                'color' => 'orange',
                'display_order' => 5,
                'is_active' => true,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Orientation',
                'slug' => 'orientation',
                'description' => 'Informations sur l\'orientation et les études supérieures',
                'icon' => 'fas fa-compass',
                'color' => 'teal',
                'display_order' => 6,
                'is_active' => true,
                'created_by' => $admin->id,
            ]
        ];

        foreach ($sections as $sectionData) {
            $existingSection = Section::where('slug', $sectionData['slug'])->first();
            
            if (!$existingSection) {
                $section = Section::create($sectionData);
                $this->command->info("Section '{$section->name}' créée avec succès.");
            } else {
                // Mettre à jour avec les nouveaux champs si ils n'existent pas
                $existingSection->update([
                    'icon' => $existingSection->icon ?: $sectionData['icon'],
                    'color' => $existingSection->color ?: $sectionData['color'],
                    'display_order' => $existingSection->display_order ?: $sectionData['display_order'],
                ]);
                $this->command->info("Section '{$existingSection->name}' mise à jour avec les nouveaux champs.");
            }
        }
    }
}
