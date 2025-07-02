<?php

namespace App\Modules\Pages\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Section;
use App\Modules\Pages\Models\Folder;
use App\Modules\Pages\Models\Page;
use App\Modules\Pages\Models\PageRevision;
use App\Modules\Pages\Services\PageService;
use App\Modules\Pages\Services\PageEditorService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $pageService;
    protected $pageEditorService;

    public function __construct(PageService $pageService, PageEditorService $pageEditorService)
    {
        $this->pageService = $pageService;
        $this->pageEditorService = $pageEditorService;
    }

    /**
     * Afficher la liste des pages
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $query = Page::with(['section', 'folder', 'creator']);

        // Filtres
        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->get('section_id'));
        }

        if ($request->filled('folder_id')) {
            $query->where('folder_id', $request->get('folder_id'));
        }

        if ($request->filled('status')) {
            if ($request->get('status') === 'published') {
                $query->published();
            } elseif ($request->get('status') === 'draft') {
                $query->drafts();
            }
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate(20);

        // Pour les filtres
        $sections = Section::active()->orderBy('name')->get();
        $folders = [];
        if ($request->filled('section_id')) {
            $folders = Folder::where('section_id', $request->get('section_id'))
                            ->active()
                            ->orderBy('name')
                            ->get();
        }

        return view('Pages::admin.pages.index', compact('pages', 'sections', 'folders'));
    }

    /**
     * Afficher le formulaire de création d'une page
     */
    public function create(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.create')) {
            abort(403, 'Accès non autorisé');
        }


        $sections = Section::active()->orderBy('name')->get();
        $folders = [];
        
        $sectionId = $request->get('section_id');
        $folderId = $request->get('folder_id');
        
        if ($sectionId) {
            $folders = Folder::where('section_id', $sectionId)
                            ->active()
                            ->orderBy('name')
                            ->get();
        }

        return view('Pages::admin.pages.create', compact('sections', 'folders', 'sectionId', 'folderId'));
    }

    /**
     * Stocker une nouvelle page
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.create')) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable|string', // Accepte le JSON sous forme de chaîne
            'excerpt' => 'nullable|string|max:500',
            'section_id' => 'required|exists:sections,id',
            'folder_id' => 'nullable|exists:folders,id',
            'is_published' => 'nullable|in:0,1', // Accepte "0" ou "1" comme chaîne
            'order_index' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'tags' => 'nullable|string', // Accepte une chaîne au lieu d'un tableau
        ]);


        // Vérifier que le dossier appartient à la section
        if ($request->filled('folder_id')) {
            $folder = Folder::find($request->folder_id);
            if ($folder->section_id != $request->section_id) {
                return back()->withErrors(['folder_id' => 'Le dossier doit appartenir à la section sélectionnée.']);
            }
        }

        try {
            $page = $this->pageService->createPage($request->all());

            if ($request->is_published) {
                return redirect()
                    ->route('admin.pages.pages.show', $page)
                    ->with('success', "Page '{$page->title}' créée et publiée avec succès.");
            } else {
                return redirect()
                    ->route('admin.pages.pages.edit', $page)
                    ->with('success', "Page '{$page->title}' créée comme brouillon. Vous pouvez continuer à l'éditer.");
            }

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher une page
     */
    public function show(Page $page)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $page->load(['section', 'folder', 'creator', 'updater', 'revisions.creator']);

        return view('Pages::admin.pages.show', compact('page'));
    }

    /**
     * Afficher le formulaire d'édition d'une page
     */
    public function edit(Page $page)
    {
        if (!auth()->user()->hasPermission('pages.edit')) {
            abort(403, 'Accès non autorisé');
        }

        $page->load(['section', 'folder']);
        $sections = Section::active()->orderBy('name')->get();
        $folders = Folder::where('section_id', $page->section_id)
                        ->active()
                        ->orderBy('name')
                        ->get();

        return view('Pages::admin.pages.edit', compact('page', 'sections', 'folders'));
    }

    /**
     * Mettre à jour une page
     */
    public function update(Request $request, Page $page)
    {
        if (!auth()->user()->hasPermission('pages.edit')) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable|string', // Accepte le JSON sous forme de chaîne
            'excerpt' => 'nullable|string|max:500',
            'folder_id' => 'nullable|exists:folders,id',
            'order_index' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'tags' => 'nullable|string', // Accepte une chaîne au lieu d'un tableau
        ]);

        // Vérifier que le dossier appartient à la section
        if ($request->filled('folder_id')) {
            $folder = Folder::find($request->folder_id);
            if ($folder->section_id != $page->section_id) {
                return back()->withErrors(['folder_id' => 'Le dossier doit appartenir à la section de la page.']);
            }
        }

        try {
            $this->pageService->updatePage($page, $request->all());

            return redirect()
                ->route('admin.pages.pages.show', $page)
                ->with('success', "Page '{$page->title}' mise à jour avec succès.");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer une page
     */
    public function destroy(Page $page)
    {
        if (!auth()->user()->hasPermission('pages.delete')) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $this->pageService->deletePage($page);

            return redirect()
                ->route('admin.pages.pages.index')
                ->with('success', "Page '{$page->title}' supprimée avec succès.");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }

    /**
     * Publier une page
     */
    public function publish(Page $page)
    {
        if (!auth()->user()->hasPermission('pages.publish')) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $this->pageService->publishPage($page);

            return response()->json([
                'success' => true,
                'message' => "Page '{$page->title}' publiée avec succès.",
                'published_at' => $page->published_at->format('d/m/Y H:i')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dépublier une page
     */
    public function unpublish(Page $page)
    {
        if (!auth()->user()->hasPermission('pages.publish')) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $this->pageService->unpublishPage($page);

            return response()->json([
                'success' => true,
                'message' => "Page '{$page->title}' dépubliée avec succès."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Auto-save d'une page
     */
    public function autosave(Request $request, Page $page = null)
    {
        if (!auth()->user()->hasPermission('pages.edit') && !auth()->user()->hasPermission('pages.create')) {
            return response()->json(['success' => false, 'message' => 'Permission refusée'], 403);
        }

        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'excerpt' => 'nullable|string',
                'section_id' => 'required|exists:sections,id',
                'folder_id' => 'nullable|exists:folders,id'
            ]);

            if ($page) {
                // Mise à jour d'une page existante
                $this->pageEditorService->autoSave($page, $data, auth()->user());
            } else {
                // Création d'une nouvelle page en auto-save
                $data['is_published'] = false;
                $page = $this->pageService->createPage($data);
            }

            return response()->json([
                'success' => true,
                'page_id' => $page->id,
                'message' => 'Sauvegarde automatique réussie'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prévisualiser une page
     */
    public function preview(Page $page)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        return view('Pages::public.pages.preview', compact('page'));
    }

    /**
     * Afficher l'historique des révisions
     */
    public function revisions(Page $page)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $revisions = $page->revisions()->with('creator')->paginate(20);

        return view('Pages::admin.pages.revisions', compact('page', 'revisions'));
    }

    /**
     * Restaurer une révision
     */
    public function restoreRevision(Page $page, PageRevision $revision)
    {
        if (!auth()->user()->hasPermission('pages.edit')) {
            abort(403, 'Accès non autorisé');
        }

        if ($revision->page_id !== $page->id) {
            abort(404);
        }

        try {
            $this->pageService->restoreRevision($page, $revision->id);

            return redirect()
                ->route('admin.pages.pages.edit', $page)
                ->with('success', "Page restaurée à partir de la révision du {$revision->created_at->format('d/m/Y H:i')}.");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la restauration : ' . $e->getMessage()]);
        }
    }

    /**
     * Recherche AJAX pour les dossiers d'une section
     */
    public function getFolders(Request $request)
    {
        $sectionId = $request->get('section_id');
        
        if (!$sectionId) {
            return response()->json([]);
        }

        $folders = Folder::where('section_id', $sectionId)
                        ->active()
                        ->orderBy('name')
                        ->get(['id', 'name', 'parent_id']);

        return response()->json($folders);
    }

    /**
     * Recherche de pages (AJAX)
     */
    public function search(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $term = $request->get('q');
        $sectionId = $request->get('section_id');
        $folderId = $request->get('folder_id');

        $pages = $this->pageService->searchPages($term, $sectionId, $folderId, false);

        return response()->json([
            'pages' => $pages->map(function($page) {
                return [
                    'id' => $page->id,
                    'title' => $page->title,
                    'excerpt' => $page->excerpt,
                    'section' => $page->section->name,
                    'folder' => $page->folder ? $page->folder->name : null,
                    'is_published' => $page->is_published,
                    'created_at' => $page->created_at->format('d/m/Y'),
                    'url' => route('admin.pages.pages.show', $page)
                ];
            })
        ]);
    }
}
