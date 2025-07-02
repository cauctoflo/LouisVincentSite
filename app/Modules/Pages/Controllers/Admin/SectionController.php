<?php

namespace App\Modules\Pages\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Section;
use App\Modules\Pages\Services\SectionService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SectionController extends Controller
{
    protected $sectionService;

    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    /**
     * Afficher la liste des sections
     */
    public function index(Request $request)
    {
        // Vérifier les permissions
        if (!auth()->user()->hasPermission('sections.view')) {
            abort(403, 'Accès non autorisé');
        }

        $query = Section::with(['creator', 'responsibles']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        $sections = $query->orderBy('name')->paginate(15);

        return view('Pages::admin.sections.index', compact('sections'));
    }

    /**
     * Afficher le formulaire de création d'une section
     */
    public function create()
    {
        if (!auth()->user()->hasPermission('sections.create')) {
            abort(403, 'Accès non autorisé');
        }

        $users = User::where('is_admin', true)
                    ->orWhereJsonContains('permissions', 'sections.manage')
                    ->orderBy('name')
                    ->get();

        return view('Pages::admin.sections.create', compact('users'));
    }

    /**
     * Stocker une nouvelle section
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('sections.create')) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:sections,name',
            'slug' => 'nullable|string|max:255|unique:sections,slug',
            'description' => 'nullable|string|max:1000',
            'responsibles' => 'nullable|array',
            'responsibles.*' => 'exists:users,id',
            'is_active' => 'boolean'
        ]);

        try {
            $section = $this->sectionService->createSection($request->all());

            return redirect()
                ->route('admin.pages.sections.index')
                ->with('success', "Section '{$section->name}' créée avec succès.");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher une section
     */
    public function show(Section $section)
    {
        if (!auth()->user()->hasPermission('sections.view')) {
            abort(403, 'Accès non autorisé');
        }

        $section->load(['creator', 'responsibles', 'folders.pages', 'pages']);

        // Statistiques
        $stats = [
            'total_folders' => $section->folders()->count(),
            'total_pages' => $section->pages()->count() + $section->folders()->withCount('pages')->get()->sum('pages_count'),
            'published_pages' => $section->pages()->published()->count(),
            'recent_pages' => $section->pages()->where('created_at', '>=', now()->subDays(7))->count()
        ];

        return view('Pages::admin.sections.show', compact('section', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition d'une section
     */
    public function edit(Section $section)
    {
        if (!auth()->user()->hasPermission('sections.edit') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        $users = User::where('is_admin', true)
                    ->orWhereJsonContains('permissions', 'sections.manage')
                    ->orderBy('name')
                    ->get();

        $section->load('responsibles');

        return view('Pages::admin.sections.edit', compact('section', 'users'));
    }

    /**
     * Mettre à jour une section
     */
    public function update(Request $request, Section $section)
    {
        if (!auth()->user()->hasPermission('sections.edit') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:sections,name,' . $section->id,
            'slug' => 'nullable|string|max:255|unique:sections,slug,' . $section->id,
            'description' => 'nullable|string|max:1000',
            'responsibles' => 'nullable|array',
            'responsibles.*' => 'exists:users,id',
            'is_active' => 'boolean'
        ]);

        try {
            $this->sectionService->updateSection($section, $request->all());

            return redirect()
                ->route('admin.pages.sections.show', $section)
                ->with('success', "Section '{$section->name}' mise à jour avec succès.");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer une section
     */
    public function destroy(Section $section)
    {
        if (!auth()->user()->hasPermission('sections.delete')) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $this->sectionService->deleteSection($section);

            return redirect()
                ->route('admin.pages.sections.index')
                ->with('success', "Section '{$section->name}' supprimée avec succès.");

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Changer le statut d'une section
     */
    public function toggleStatus(Section $section)
    {
        if (!auth()->user()->hasPermission('sections.edit') && !$section->isResponsible(auth()->user())) {
            abort(403, 'Accès non autorisé');
        }

        try {
            $this->sectionService->toggleSectionStatus($section);

            $status = $section->is_active ? 'activée' : 'désactivée';
            
            return response()->json([
                'success' => true,
                'message' => "Section {$status} avec succès.",
                'status' => $section->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut : ' . $e->getMessage()
            ], 500);
        }
    }
}
