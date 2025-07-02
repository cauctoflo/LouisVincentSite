<?php

namespace App\Modules\Pages\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Section;
use App\Modules\Pages\Models\Folder;
use App\Modules\Log\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FolderController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Afficher la liste des dossiers d'une section
     */
    public function index(Request $request, Section $section)
    {
        if (!auth()->user()->hasPermission('folders.view') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        $query = $section->folders()->with(['creator', 'parent', 'children']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $folders = $query->orderBy('order_index')->orderBy('name')->get();

        // Organiser en arbre
        $rootFolders = $folders->whereNull('parent_id');

        return view('Pages::admin.folders.index', compact('section', 'folders', 'rootFolders'));
    }

    /**
     * Afficher le formulaire de création d'un dossier
     */
    public function create(Section $section)
    {
        if (!auth()->user()->hasPermission('folders.create') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        $parentFolders = $section->folders()->whereNull('parent_id')->orderBy('name')->get();

        return view('Pages::admin.folders.create', compact('section', 'parentFolders'));
    }

    /**
     * Stocker un nouveau dossier
     */
    public function store(Request $request, Section $section)
    {
        if (!auth()->user()->hasPermission('folders.create') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:folders,id',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Vérifier que le parent appartient à la même section
        if ($request->filled('parent_id')) {
            $parent = Folder::find($request->parent_id);
            if ($parent->section_id !== $section->id) {
                return back()->withErrors(['parent_id' => 'Le dossier parent doit appartenir à la même section.']);
            }
        }

        DB::beginTransaction();
        
        try {
            $folder = Folder::create([
                'name' => $request->name,
                'slug' => $request->slug ?? \Str::slug($request->name),
                'description' => $request->description,
                'section_id' => $section->id,
                'parent_id' => $request->parent_id,
                'created_by' => Auth::id(),
                'order_index' => $request->order_index ?? 0,
                'is_active' => $request->is_active ?? true,
            ]);

            // Log la création
            $this->logService->log('folder_create', $folder, [
                'message' => "Création du dossier '{$folder->name}'",
                'description' => "Nouveau dossier créé dans la section {$section->name}",
                'details' => [
                    'nom' => $folder->name,
                    'section' => $section->name,
                    'parent' => $folder->parent ? $folder->parent->name : 'Aucun',
                    'ordre' => $folder->order_index
                ]
            ]);

            DB::commit();

            return redirect()
                ->route('admin.pages.sections.folders.index', $section)
                ->with('success', "Dossier '{$folder->name}' créé avec succès.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher un dossier
     */
    public function show(Section $section, Folder $folder)
    {
        if (!auth()->user()->hasPermission('folders.view') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        if ($folder->section_id !== $section->id) {
            abort(404);
        }

        $folder->load(['creator', 'parent', 'children.pages', 'pages.creator']);

        // Statistiques
        $stats = [
            'total_children' => $folder->children()->count(),
            'total_pages' => $folder->pages()->count(),
            'published_pages' => $folder->pages()->published()->count(),
            'recent_pages' => $folder->pages()->where('created_at', '>=', now()->subDays(7))->count()
        ];

        return view('Pages::admin.folders.show', compact('section', 'folder', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition d'un dossier
     */
    public function edit(Section $section, Folder $folder)
    {
        if (!auth()->user()->hasPermission('folders.edit') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        if ($folder->section_id !== $section->id) {
            abort(404);
        }

        // Parents possibles (excluant le dossier lui-même et ses descendants)
        $parentFolders = $section->folders()
            ->whereNull('parent_id')
            ->where('id', '!=', $folder->id)
            ->whereNotIn('id', $this->getDescendantIds($folder))
            ->orderBy('name')
            ->get();

        return view('Pages::admin.folders.edit', compact('section', 'folder', 'parentFolders'));
    }

    /**
     * Mettre à jour un dossier
     */
    public function update(Request $request, Section $section, Folder $folder)
    {
        if (!auth()->user()->hasPermission('folders.edit') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        if ($folder->section_id !== $section->id) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:folders,slug,' . $folder->id . ',id,section_id,' . $section->id,
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:folders,id',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Vérifier que le parent est valide
        if ($request->filled('parent_id')) {
            $parent = Folder::find($request->parent_id);
            if ($parent->section_id !== $section->id) {
                return back()->withErrors(['parent_id' => 'Le dossier parent doit appartenir à la même section.']);
            }
            
            // Éviter les boucles
            if ($parent->id === $folder->id || $folder->isAncestorOf($parent)) {
                return back()->withErrors(['parent_id' => 'Un dossier ne peut pas être son propre parent ou celui de ses ancêtres.']);
            }
        }

        DB::beginTransaction();
        
        try {
            $oldData = $folder->toArray();
            
            $folder->update([
                'name' => $request->name,
                'slug' => $request->slug ?? \Str::slug($request->name),
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'order_index' => $request->order_index ?? $folder->order_index,
                'is_active' => $request->is_active ?? $folder->is_active,
            ]);

            // Log la modification
            $this->logService->log('folder_update', $folder, [
                'message' => "Modification du dossier '{$folder->name}'",
                'description' => "Dossier mis à jour",
                'details' => $this->getChangedFields($oldData, $folder->toArray())
            ]);

            DB::commit();

            return redirect()
                ->route('admin.pages.sections.folders.show', [$section, $folder])
                ->with('success', "Dossier '{$folder->name}' mis à jour avec succès.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer un dossier
     */
    public function destroy(Section $section, Folder $folder)
    {
        if (!auth()->user()->hasPermission('folders.delete')) {
            abort(403, 'Accès non autorisé');
        }

        if ($folder->section_id !== $section->id) {
            abort(404);
        }

        // Vérifier s'il y a des pages ou sous-dossiers
        $pagesCount = $folder->pages()->count();
        $childrenCount = $folder->children()->count();
        
        if ($pagesCount > 0 || $childrenCount > 0) {
            return back()->withErrors([
                'error' => "Impossible de supprimer le dossier '{$folder->name}' car il contient {$pagesCount} page(s) et {$childrenCount} sous-dossier(s)."
            ]);
        }

        DB::beginTransaction();
        
        try {
            $folderData = [
                'nom' => $folder->name,
                'section' => $section->name,
                'parent' => $folder->parent ? $folder->parent->name : 'Aucun'
            ];

            $folder->delete();

            // Log la suppression
            $this->logService->log('folder_delete', null, [
                'message' => "Suppression du dossier '{$folderData['nom']}'",
                'description' => "Dossier supprimé définitivement",
                'details' => $folderData
            ]);

            DB::commit();

            return redirect()
                ->route('admin.pages.sections.folders.index', $section)
                ->with('success', "Dossier '{$folderData['nom']}' supprimé avec succès.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }

    /**
     * Réorganiser les dossiers (AJAX)
     */
    public function reorder(Request $request, Section $section)
    {
        if (!auth()->user()->hasPermission('folders.edit') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'folders' => 'required|array',
            'folders.*.id' => 'required|exists:folders,id',
            'folders.*.order_index' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        
        try {
            foreach ($request->folders as $folderData) {
                $folder = Folder::where('id', $folderData['id'])
                               ->where('section_id', $section->id)
                               ->first();
                
                if ($folder) {
                    $folder->update(['order_index' => $folderData['order_index']]);
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Ordre des dossiers mis à jour.']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Erreur lors de la réorganisation.'], 500);
        }
    }

    /**
     * Récupère les IDs des descendants d'un dossier
     */
    private function getDescendantIds(Folder $folder, $descendants = [])
    {
        $children = $folder->children;
        
        foreach ($children as $child) {
            $descendants[] = $child->id;
            $descendants = $this->getDescendantIds($child, $descendants);
        }
        
        return $descendants;
    }

    /**
     * Récupère les changements entre deux versions
     */
    private function getChangedFields(array $oldData, array $newData)
    {
        $changes = [];
        $fieldsToCheck = ['name', 'slug', 'description', 'parent_id', 'order_index', 'is_active'];

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
