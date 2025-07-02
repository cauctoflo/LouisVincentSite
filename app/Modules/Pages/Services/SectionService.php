<?php

namespace App\Modules\Pages\Services;

use App\Modules\Pages\Models\Section;
use App\Modules\Pages\Models\Folder;
use App\Modules\Log\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SectionService
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Crée une nouvelle section
     */
    public function createSection(array $data)
    {
        DB::beginTransaction();
        
        try {
            $section = Section::create([
                'name' => $data['name'],
                'slug' => $data['slug'] ?? \Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'created_by' => Auth::id(),
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Attribuer les responsables si fournis
            if (!empty($data['responsibles'])) {
                $section->responsibles()->attach($data['responsibles']);
            }

            // Log la création
            $this->logService->log('section_create', $section, [
                'message' => "Création de la section '{$section->name}'",
                'description' => "Nouvelle section créée",
                'details' => [
                    'nom' => $section->name,
                    'slug' => $section->slug,
                    'description' => $section->description ?: 'Non définie',
                    'responsables' => $section->responsibles->pluck('name')->toArray()
                ]
            ]);

            DB::commit();
            return $section;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Met à jour une section
     */
    public function updateSection(Section $section, array $data)
    {
        DB::beginTransaction();
        
        try {
            $oldData = $section->toArray();
            $oldResponsibles = $section->responsibles->pluck('name')->toArray();
            
            $section->update([
                'name' => $data['name'] ?? $section->name,
                'slug' => $data['slug'] ?? $section->slug,
                'description' => $data['description'] ?? $section->description,
                'is_active' => $data['is_active'] ?? $section->is_active,
            ]);

            // Mettre à jour les responsables
            if (isset($data['responsibles'])) {
                $section->responsibles()->sync($data['responsibles']);
            }

            // Log la modification
            $this->logService->log('section_update', $section, [
                'message' => "Modification de la section '{$section->name}'",
                'description' => "Section mise à jour",
                'details' => [
                    'changements' => $this->getChangedFields($oldData, $section->toArray()),
                    'anciens_responsables' => $oldResponsibles,
                    'nouveaux_responsables' => $section->fresh()->responsibles->pluck('name')->toArray()
                ]
            ]);

            DB::commit();
            return $section;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Supprime une section
     */
    public function deleteSection(Section $section)
    {
        DB::beginTransaction();
        
        try {
            // Vérifier s'il y a des pages ou dossiers
            $pagesCount = $section->pages()->count();
            $foldersCount = $section->folders()->count();
            
            if ($pagesCount > 0 || $foldersCount > 0) {
                throw new \Exception("Impossible de supprimer la section '{$section->name}' car elle contient {$pagesCount} page(s) et {$foldersCount} dossier(s).");
            }

            $sectionData = [
                'nom' => $section->name,
                'slug' => $section->slug,
                'responsables' => $section->responsibles->pluck('name')->toArray()
            ];

            $section->delete();

            // Log la suppression
            $this->logService->log('section_delete', null, [
                'message' => "Suppression de la section '{$sectionData['nom']}'",
                'description' => "Section supprimée définitivement",
                'details' => $sectionData
            ]);

            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Active/désactive une section
     */
    public function toggleSectionStatus(Section $section)
    {
        DB::beginTransaction();
        
        try {
            $newStatus = !$section->is_active;
            $section->update(['is_active' => $newStatus]);

            // Log le changement de statut
            $this->logService->log('section_status_change', $section, [
                'message' => "Changement de statut de la section '{$section->name}'",
                'description' => "Section " . ($newStatus ? 'activée' : 'désactivée'),
                'details' => [
                    'nom' => $section->name,
                    'nouveau_statut' => $newStatus ? 'Active' : 'Inactive'
                ]
            ]);

            DB::commit();
            return $section;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Récupère les changements entre deux versions
     */
    private function getChangedFields(array $oldData, array $newData)
    {
        $changes = [];
        $fieldsToCheck = ['name', 'slug', 'description', 'is_active'];

        foreach ($fieldsToCheck as $field) {
            if (isset($oldData[$field]) && isset($newData[$field]) && $oldData[$field] !== $newData[$field]) {
                $changes[$field] = [
                    'ancien' => $oldData[$field],
                    'nouveau' => $newData[$field]
                ];
            }
        }

        return $changes;
    }
}
