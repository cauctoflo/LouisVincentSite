<?php

namespace App\Modules\Pages\Services;

use App\Modules\Pages\Models\Page;
use App\Modules\Pages\Models\PageRevision;
use App\Models\User;
use Illuminate\Support\Str;

class PageEditorService
{
    /**
     * Sauvegarde automatique d'une page
     */
    public function autoSave(Page $page, array $data, User $user)
    {
        // Créer une révision avec les données actuelles
        $this->createRevision($page, $data, $user, 'auto_save');
        
        // Mettre à jour les données de la page sans publier
        $page->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? null,
            'updated_by' => $user->id,
        ]);
        
        return $page;
    }
    
    /**
     * Sauvegarde manuelle d'une page
     */
    public function save(Page $page, array $data, User $user)
    {
        // Créer une révision avec les données précédentes
        $this->createRevision($page, $data, $user, 'manual');
        
        // Générer le slug si nécessaire
        if (isset($data['title']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $page->section_id, $page->folder_id, $page->id);
        }
        
        // Mettre à jour la page
        $page->update(array_merge($data, [
            'updated_by' => $user->id,
        ]));
        
        return $page;
    }
    
    /**
     * Publier une page
     */
    public function publish(Page $page, User $user)
    {
        // Créer une révision de publication
        $this->createRevision($page, $page->toArray(), $user, 'publish');
        
        $page->update([
            'is_published' => true,
            'published_at' => now(),
            'updated_by' => $user->id,
        ]);
        
        return $page;
    }
    
    /**
     * Dépublier une page
     */
    public function unpublish(Page $page, User $user)
    {
        $page->update([
            'is_published' => false,
            'published_at' => null,
            'updated_by' => $user->id,
        ]);
        
        return $page;
    }
    
    /**
     * Créer une nouvelle page
     */
    public function createPage(array $data, User $user)
    {
        // Générer le slug
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $data['section_id'], $data['folder_id'] ?? null);
        }
        
        // Créer la page
        $page = Page::create(array_merge($data, [
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'is_published' => false,
        ]));
        
        // Créer la première révision
        $this->createRevision($page, $data, $user, 'manual', 'Création initiale de la page');
        
        return $page;
    }
    
    /**
     * Créer une révision
     */
    public function createRevision(Page $page, array $data, User $user, string $type = 'manual', string $summary = null)
    {
        return PageRevision::create([
            'page_id' => $page->id,
            'title' => $data['title'] ?? $page->title,
            'content' => $data['content'] ?? $page->content,
            'excerpt' => $data['excerpt'] ?? $page->excerpt,
            'created_by' => $user->id,
            'revision_type' => $type,
            'changes_summary' => $summary ?? $this->generateChangesSummary($page, $data),
        ]);
    }
    
    /**
     * Restaurer une page depuis une révision
     */
    public function restoreFromRevision(Page $page, PageRevision $revision, User $user)
    {
        // Créer une révision avant la restauration
        $this->createRevision($page, $page->toArray(), $user, 'restore', "Restauration depuis la révision #{$revision->id}");
        
        // Restaurer les données
        $page->update([
            'title' => $revision->title,
            'content' => $revision->content,
            'excerpt' => $revision->excerpt,
            'updated_by' => $user->id,
        ]);
        
        return $page;
    }
    
    /**
     * Générer un slug unique
     */
    protected function generateUniqueSlug(string $title, int $sectionId, ?int $folderId = null, ?int $excludePageId = null)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;
        
        do {
            $query = Page::where('section_id', $sectionId)
                        ->where('folder_id', $folderId)
                        ->where('slug', $slug);
                        
            if ($excludePageId) {
                $query->where('id', '!=', $excludePageId);
            }
            
            $exists = $query->exists();
            
            if ($exists) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
        } while ($exists);
        
        return $slug;
    }
    
    /**
     * Générer un résumé des changements
     */
    protected function generateChangesSummary(Page $page, array $newData)
    {
        $changes = [];
        
        if (isset($newData['title']) && $newData['title'] !== $page->title) {
            $changes[] = "Titre modifié";
        }
        
        if (isset($newData['content']) && $newData['content'] !== $page->content) {
            $changes[] = "Contenu modifié";
        }
        
        if (isset($newData['excerpt']) && $newData['excerpt'] !== $page->excerpt) {
            $changes[] = "Extrait modifié";
        }
        
        return empty($changes) ? 'Aucun changement détecté' : implode(', ', $changes);
    }
    
    /**
     * Obtenir l'historique des révisions d'une page
     */
    public function getRevisionHistory(Page $page, int $limit = 20)
    {
        return $page->revisions()
                   ->with('creator')
                   ->latest()
                   ->limit($limit)
                   ->get();
    }
    
    /**
     * Nettoyer les anciennes révisions auto-save
     */
    public function cleanupOldAutoSaves(Page $page, int $keepLast = 5)
    {
        $autoSaves = $page->revisions()
                          ->where('revision_type', 'auto_save')
                          ->latest()
                          ->skip($keepLast)
                          ->pluck('id');
                          
        if ($autoSaves->isNotEmpty()) {
            PageRevision::whereIn('id', $autoSaves)->delete();
        }
    }
}
