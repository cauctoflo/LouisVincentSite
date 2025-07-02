<?php

namespace App\Modules\Pages\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Section;
use App\Modules\Pages\Models\Folder;
use App\Modules\Log\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SimpleFolderController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Afficher la liste de tous les dossiers
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $query = Folder::with(['section', 'creator', 'pages']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('section', function($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $folders = $query->orderBy('order_index')->orderBy('name')->paginate(20);
        $sections = Section::orderBy('name')->get();

        return view('Pages::admin.folders.index', compact('folders', 'sections'));
    }

    /**
     * Afficher le formulaire de création d'un dossier
     */
    public function create()
    {
        if (!auth()->user()->hasPermission('pages.create')) {
            abort(403, 'Accès non autorisé');
        }

        $sections = Section::where('is_active', true)->orderBy('name')->get();

        return view('Pages::admin.folders.create', compact('sections'));
    }

    /**
     * Enregistrer un nouveau dossier
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.create')) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'section_id' => 'required|exists:sections,id',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Générer le slug s'il n'est pas fourni
        $slug = $request->slug ?: \Str::slug($request->name);

        // Vérifier l'unicité du slug dans la section
        $existingSlug = Folder::where('section_id', $request->section_id)
            ->where('slug', $slug)
            ->exists();

        if ($existingSlug) {
            return back()->withErrors(['slug' => 'Ce slug existe déjà dans cette section.'])->withInput();
        }

        DB::beginTransaction();
        
        try {
            $section = Section::findOrFail($request->section_id);
            
            $folder = Folder::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'section_id' => $request->section_id,
                'order_index' => $request->order_index ?? 0,
                'is_active' => $request->has('is_active'),
                'created_by' => auth()->id(),
            ]);

            // Log la création
            $this->logService->log('folder_create', $folder, [
                'message' => "Création du dossier '{$folder->name}'",
                'description' => "Nouveau dossier créé dans la section {$section->name}",
                'details' => [
                    'nom' => $folder->name,
                    'section' => $section->name,
                    'ordre' => $folder->order_index
                ]
            ]);

            DB::commit();

            return redirect()
                ->route('admin.pages.folders.index')
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
    public function show(Folder $folder)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $folder->load(['section', 'creator', 'pages.creator']);

        return view('Pages::admin.folders.show', compact('folder'));
    }

    /**
     * Afficher le formulaire d'édition d'un dossier
     */
    public function edit(Folder $folder)
    {
        if (!auth()->user()->hasPermission('pages.edit')) {
            abort(403, 'Accès non autorisé');
        }

        $sections = Section::where('is_active', true)->orderBy('name')->get();

        return view('Pages::admin.folders.edit', compact('folder', 'sections'));
    }

    /**
     * Mettre à jour un dossier
     */
    public function update(Request $request, Folder $folder)
    {
        if (!auth()->user()->hasPermission('pages.edit')) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:folders,slug,' . $folder->id . ',id,section_id,' . $request->section_id,
            'description' => 'nullable|string|max:1000',
            'section_id' => 'required|exists:sections,id',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        DB::beginTransaction();
        
        try {
            $oldData = $folder->toArray();
            
            // Générer le slug s'il n'est pas fourni
            $slug = $request->slug ?: \Str::slug($request->name);
            
            $folder->update([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'section_id' => $request->section_id,
                'order_index' => $request->order_index ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            // Log la modification
            $this->logService->log('folder_update', $folder, [
                'message' => "Modification du dossier '{$folder->name}'",
                'description' => "Dossier modifié dans la section {$folder->section->name}",
                'details' => [
                    'ancien_nom' => $oldData['name'],
                    'nouveau_nom' => $folder->name,
                    'section' => $folder->section->name
                ]
            ]);

            DB::commit();

            return redirect()
                ->route('admin.pages.folders.show', $folder)
                ->with('success', "Dossier '{$folder->name}' modifié avec succès.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la modification : ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer un dossier
     */
    public function destroy(Folder $folder)
    {
        if (!auth()->user()->hasPermission('pages.delete')) {
            abort(403, 'Accès non autorisé');
        }

        DB::beginTransaction();
        
        try {
            $folderName = $folder->name;
            $pagesCount = $folder->pages()->count();
            
            // Supprimer toutes les pages du dossier
            $folder->pages()->delete();
            
            // Log la suppression
            $this->logService->log('folder_delete', $folder, [
                'message' => "Suppression du dossier '{$folderName}'",
                'description' => "Dossier supprimé avec {$pagesCount} page(s)",
                'details' => [
                    'nom' => $folderName,
                    'section' => $folder->section->name,
                    'pages_supprimees' => $pagesCount
                ]
            ]);
            
            $folder->delete();

            DB::commit();

            return redirect()
                ->route('admin.pages.folders.index')
                ->with('success', "Dossier '{$folderName}' supprimé avec succès.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }

    /**
     * API pour récupérer la liste des dossiers
     */
    public function getFolders(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $query = Folder::with('section');

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $folders = $query->where('is_active', true)
            ->orderBy('order_index')
            ->orderBy('name')
            ->get();

        return response()->json($folders);
    }

    /**
     * API pour récupérer les dossiers d'une section spécifique
     */
    public function getSectionFolders(Request $request, Section $section)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $folders = $section->folders()
            ->where('is_active', true)
            ->orderBy('order_index')
            ->orderBy('name')
            ->get();

        return response()->json($folders);
    }
}
