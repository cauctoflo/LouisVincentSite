<?php

namespace App\Modules\Pages\Services;

use App\Modules\Pages\Models\Page;
use App\Modules\Pages\Models\Section;
use App\Modules\Pages\Models\Folder;
use App\Modules\Log\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageService
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Crée une nouvelle page
     */
    public function createPage(array $data)
    {
        DB::beginTransaction();
        
        try {
            $page = Page::create([
                'title' => $data['title'],
                'slug' => $data['slug'] ?? \Str::slug($data['title']),
                'content' => $data['content'] ?? null,
                'excerpt' => $data['excerpt'] ?? null,
                'section_id' => $data['section_id'],
                'folder_id' => $data['folder_id'] ?? null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_published' => $data['is_published'] ?? false,
                'published_at' => ($data['is_published'] ?? false) ? now() : null,
                'order_index' => $data['order_index'] ?? 0,
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'tags' => $this->processTagsInput($data['tags'] ?? null),
            ]);

            // Créer une révision initiale
            $page->createRevision();

            // Log la création
            $this->logService->log('page_create', $page, [
                'message' => "Création de la page '{$page->title}'",
                'description' => "Nouvelle page créée dans la section {$page->section->name}",
                'details' => [
                    'titre' => $page->title,
                    'section' => $page->section->name,
                    'dossier' => $page->folder ? $page->folder->name : 'Aucun',
                    'statut' => $page->is_published ? 'Publiée' : 'Brouillon'
                ]
            ]);

            DB::commit();
            return $page;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Met à jour une page
     */
    public function updatePage(Page $page, array $data)
    {
        DB::beginTransaction();
        
        try {
            $oldData = $page->toArray();
            
            $page->update([
                'title' => $data['title'] ?? $page->title,
                'slug' => $data['slug'] ?? $page->slug,
                'content' => $data['content'] ?? $page->content,
                'excerpt' => $data['excerpt'] ?? $page->excerpt,
                'folder_id' => $data['folder_id'] ?? $page->folder_id,
                'updated_by' => Auth::id(),
                'order_index' => $data['order_index'] ?? $page->order_index,
                'meta_title' => $data['meta_title'] ?? $page->meta_title,
                'meta_description' => $data['meta_description'] ?? $page->meta_description,
                'tags' => $this->processTagsInput($data['tags'] ?? $page->tags),
            ]);

            // Créer une révision si le contenu a changé
            if ($oldData['content'] !== $page->content || $oldData['title'] !== $page->title) {
                $page->createRevision();
            }

            // Log la modification
            $this->logService->log('page_update', $page, [
                'message' => "Modification de la page '{$page->title}'",
                'description' => "Page mise à jour",
                'details' => $this->getChangedFields($oldData, $page->toArray())
            ]);

            DB::commit();
            return $page;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Publie une page
     */
    public function publishPage(Page $page)
    {
        DB::beginTransaction();
        
        try {
            $page->update([
                'is_published' => true,
                'published_at' => now(),
                'updated_by' => Auth::id()
            ]);

            // Créer une révision de publication
            $page->revisions()->create([
                'title' => $page->title,
                'content' => $page->content,
                'excerpt' => $page->excerpt,
                'created_by' => Auth::id(),
                'revision_type' => 'publish',
                'changes_summary' => 'Publication de la page'
            ]);

            // Log la publication
            $this->logService->log('page_publish', $page, [
                'message' => "Publication de la page '{$page->title}'",
                'description' => "Page publiée et visible publiquement",
                'details' => [
                    'titre' => $page->title,
                    'section' => $page->section->name,
                    'date_publication' => $page->published_at->format('d/m/Y H:i')
                ]
            ]);

            DB::commit();
            return $page;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Dépublie une page
     */
    public function unpublishPage(Page $page)
    {
        DB::beginTransaction();
        
        try {
            $page->update([
                'is_published' => false,
                'published_at' => null,
                'updated_by' => Auth::id()
            ]);

            // Log la dépublication
            $this->logService->log('page_unpublish', $page, [
                'message' => "Dépublication de la page '{$page->title}'",
                'description' => "Page retirée de l'affichage public",
                'details' => [
                    'titre' => $page->title,
                    'section' => $page->section->name
                ]
            ]);

            DB::commit();
            return $page;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Supprime une page
     */
    public function deletePage(Page $page)
    {
        DB::beginTransaction();
        
        try {
            $pageData = [
                'titre' => $page->title,
                'section' => $page->section->name,
                'dossier' => $page->folder ? $page->folder->name : 'Aucun'
            ];

            $page->delete();

            // Log la suppression
            $this->logService->log('page_delete', null, [
                'message' => "Suppression de la page '{$pageData['titre']}'",
                'description' => "Page supprimée définitivement",
                'details' => $pageData
            ]);

            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Sauvegarde automatique d'une page
     */
    public function autoSavePage(Page $page, array $data)
    {
        try {
            // Créer une révision de sauvegarde automatique
            $page->revisions()->create([
                'title' => $data['title'] ?? $page->title,
                'content' => $data['content'] ?? $page->content,
                'excerpt' => $data['excerpt'] ?? $page->excerpt,
                'created_by' => Auth::id(),
                'revision_type' => 'auto_save',
                'changes_summary' => 'Sauvegarde automatique'
            ]);

            return true;
            
        } catch (\Exception $e) {
            // En cas d'erreur, on ne bloque pas l'utilisateur
            \Log::error('Auto-save failed for page ' . $page->id, ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Restaure une révision de page
     */
    public function restoreRevision(Page $page, $revisionId)
    {
        DB::beginTransaction();
        
        try {
            $revision = $page->revisions()->findOrFail($revisionId);
            
            $page->update([
                'title' => $revision->title,
                'content' => $revision->content,
                'excerpt' => $revision->excerpt,
                'updated_by' => Auth::id()
            ]);

            // Créer une nouvelle révision pour la restauration
            $page->revisions()->create([
                'title' => $revision->title,
                'content' => $revision->content,
                'excerpt' => $revision->excerpt,
                'created_by' => Auth::id(),
                'revision_type' => 'restore',
                'changes_summary' => "Restauration de la révision du {$revision->created_at->format('d/m/Y H:i')}"
            ]);

            // Log la restauration
            $this->logService->log('page_restore', $page, [
                'message' => "Restauration de la page '{$page->title}'",
                'description' => "Page restaurée à partir d'une révision antérieure",
                'details' => [
                    'titre' => $page->title,
                    'revision_date' => $revision->created_at->format('d/m/Y H:i'),
                    'revision_author' => $revision->creator->name
                ]
            ]);

            DB::commit();
            return $page;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Recherche de pages
     */
    public function searchPages($term, $sectionId = null, $folderId = null, $publishedOnly = true)
    {
        $query = Page::search($term);

        if ($publishedOnly) {
            $query->published();
        }

        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        if ($folderId) {
            $query->where('folder_id', $folderId);
        }

        return $query->with(['section', 'folder', 'creator'])
                     ->orderBy('title')
                     ->get();
    }

    /**
     * Traite les tags depuis le formulaire et les convertit en tableau
     * 
     * @param mixed $tags
     * @return array|null
     */
    protected function processTagsInput($tags)
    {
        // Si null, retourner null
        if ($tags === null) {
            return null;
        }
        
        // Si déjà un tableau, retourner tel quel
        if (is_array($tags)) {
            return array_map('trim', array_filter($tags));
        }
        
        // Si c'est une chaîne, la diviser en tableau
        if (is_string($tags)) {
            if (empty(trim($tags))) {
                return null;
            }
            // Diviser par virgule et supprimer les espaces
            return array_map('trim', array_filter(explode(',', $tags)));
        }
        
        return null;
    }

    /**
     * Récupère les changements entre deux versions
     */
    private function getChangedFields(array $oldData, array $newData)
    {
        $changes = [];
        $fieldsToCheck = ['title', 'content', 'excerpt', 'folder_id', 'order_index'];

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
