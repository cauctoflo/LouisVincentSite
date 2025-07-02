<?php

namespace App\Modules\Pages\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Section;
use App\Modules\Pages\Models\Folder;
use App\Modules\Pages\Models\Page;
use Illuminate\Http\Request;

class PagesManagerController extends Controller
{
    /**
     * Afficher l'interface centrale de gestion des pages
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer les statistiques globales
        $stats = [
            'sections' => Section::count(),
            'folders' => Folder::count(),
            'pages' => Page::count(),
            'published_pages' => Page::where('is_published', true)->count(),
            'draft_pages' => Page::where('is_published', false)->count(),
        ];

        // Récupérer les sections avec leurs dossiers et pages (pour la vue hiérarchique)
        $sections = Section::withCount(['folders', 'pages', 'directPages'])
            ->with(['folders' => function($query) {
                $query->withCount('pages')->orderBy('order_index')->orderBy('name')->limit(10);
            }, 'directPages' => function($query) {
                $query->orderBy('order_index')->orderBy('title')->limit(5);
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Pages récemment modifiées
        $recent_pages = Page::with(['folder.section', 'section', 'creator'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('Pages::admin.manager', compact('stats', 'sections', 'recent_pages'));
    }

    /**
     * Vue hiérarchique détaillée (AJAX)
     */
    public function hierarchy(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $sections = Section::with(['folders' => function($query) {
            $query->orderBy('sort_order')->orderBy('name');
        }, 'folders.pages' => function($query) {
            $query->orderBy('sort_order')->orderBy('title');
        }])->orderBy('sort_order')->orderBy('name')->get();

        if ($request->ajax()) {
            return response()->json($sections);
        }

        return view('Pages::admin.manager.hierarchy', compact('sections'));
    }

    /**
     * Recherche globale dans les pages
     */
    public function search(Request $request)
    {
        if (!auth()->user()->hasPermission('pages.view')) {
            abort(403, 'Accès non autorisé');
        }

        $query = $request->get('q', '');
        $results = [];

        if (strlen($query) >= 2) {
            // Rechercher dans les sections
            $sections = Section::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get();

            // Rechercher dans les dossiers
            $folders = Folder::with('section')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get();

            // Rechercher dans les pages
            $pages = Page::with(['folder.section'])
                ->where('title', 'LIKE', "%{$query}%")
                ->orWhere('excerpt', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get();

            $results = [
                'sections' => $sections,
                'folders' => $folders,
                'pages' => $pages,
                'query' => $query
            ];
        }

        if ($request->ajax()) {
            return response()->json($results);
        }

        return view('Pages::admin.manager.search', compact('results'));
    }
}
