<?php

namespace App\Modules\Pages\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Section;
use App\Modules\Pages\Models\Folder;
use App\Modules\Pages\Models\Page;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    /**
     * Page d'accueil des sections
     */
    public function index()
    {
        $sections = Section::active()
                          ->with(['responsibles'])
                          ->withCount(['pages', 'folders'])
                          ->orderBy('name')
                          ->get();

        return view('Pages::public.index', compact('sections'));
    }

    /**
     * Afficher une section
     */
    public function showSection($sectionSlug)
    {
        $section = Section::where('slug', $sectionSlug)
                         ->active()
                         ->with(['folders' => function($query) {
                             $query->active()
                                   ->whereNull('parent_id')
                                   ->orderBy('order_index')
                                   ->orderBy('name')
                                   ->with(['children' => function($subQuery) {
                                       $subQuery->active()
                                               ->orderBy('order_index')
                                               ->orderBy('name');
                                   }]);
                         }, 'pages' => function($query) {
                             $query->published()
                                   ->whereNull('folder_id')
                                   ->orderBy('order_index')
                                   ->orderBy('title');
                         }])
                         ->firstOrFail();

        return view('Pages::public.sections.show', compact('section'));
    }

    /**
     * Afficher un dossier
     */
    public function showFolder($sectionSlug, $folderSlug)
    {
        $section = Section::where('slug', $sectionSlug)
                         ->active()
                         ->firstOrFail();

        $folder = Folder::where('slug', $folderSlug)
                       ->where('section_id', $section->id)
                       ->active()
                       ->with(['children' => function($query) {
                           $query->active()
                                 ->orderBy('order_index')
                                 ->orderBy('name');
                       }, 'pages' => function($query) {
                           $query->published()
                                 ->orderBy('order_index')
                                 ->orderBy('title');
                       }, 'parent'])
                       ->firstOrFail();

        // Récupérer les dossiers frères pour la navigation
        $siblingFolders = Folder::where('section_id', $section->id)
                               ->where('parent_id', $folder->parent_id)
                               ->where('id', '!=', $folder->id)
                               ->active()
                               ->orderBy('order_index')
                               ->orderBy('name')
                               ->get();

        return view('Pages::public.folders.show', compact('section', 'folder', 'siblingFolders'));
    }

    /**
     * Afficher une page
     */
    public function showPage($sectionSlug, $folderSlug = null, $pageSlug = null)
    {
        $section = Section::where('slug', $sectionSlug)
                         ->active()
                         ->firstOrFail();

        // Si pas de dossier, la page est directement dans la section
        if ($pageSlug === null) {
            $pageSlug = $folderSlug;
            $folderSlug = null;
        }

        $query = Page::where('slug', $pageSlug)
                    ->where('section_id', $section->id)
                    ->published();

        if ($folderSlug) {
            $folder = Folder::where('slug', $folderSlug)
                           ->where('section_id', $section->id)
                           ->active()
                           ->firstOrFail();
            
            $query->where('folder_id', $folder->id);
        } else {
            $query->whereNull('folder_id');
            $folder = null;
        }

        $page = $query->with(['creator', 'updater'])->firstOrFail();

        // Pages voisines pour la navigation
        $siblingPages = Page::where('section_id', $section->id)
                           ->where('folder_id', $page->folder_id)
                           ->where('id', '!=', $page->id)
                           ->published()
                           ->orderBy('order_index')
                           ->orderBy('title')
                           ->get(['id', 'title', 'slug']);

        // Navigation dans la section
        $navigation = $this->buildNavigation($section);

        return view('Pages::public.pages.show', compact('section', 'folder', 'page', 'siblingPages', 'navigation'));
    }

    /**
     * Recherche dans les pages
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100'
        ]);

        $term = $request->get('q');
        $sectionSlug = $request->get('section');

        $query = Page::search($term)->published()->with(['section', 'folder']);

        if ($sectionSlug) {
            $section = Section::where('slug', $sectionSlug)->active()->first();
            if ($section) {
                $query->where('section_id', $section->id);
            }
        }

        $pages = $query->orderBy('title')->paginate(15);

        $sections = Section::active()->orderBy('name')->get(['id', 'name', 'slug']);

        return view('Pages::public.search', compact('pages', 'term', 'sections', 'sectionSlug'));
    }

    /**
     * Flux RSS d'une section
     */
    public function rss($sectionSlug)
    {
        $section = Section::where('slug', $sectionSlug)
                         ->active()
                         ->firstOrFail();

        $pages = Page::where('section_id', $section->id)
                    ->published()
                    ->orderBy('published_at', 'desc')
                    ->limit(20)
                    ->get();

        return response()
            ->view('Pages::public.rss', compact('section', 'pages'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    /**
     * Plan du site XML
     */
    public function sitemap()
    {
        $sections = Section::active()->get();
        $folders = Folder::active()->get();
        $pages = Page::published()->get();

        return response()
            ->view('Pages::public.sitemap', compact('sections', 'folders', 'pages'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * Construit la navigation pour une section
     */
    private function buildNavigation(Section $section)
    {
        $navigation = [
            'section' => $section,
            'folders' => [],
            'pages' => []
        ];

        // Dossiers racine
        $rootFolders = Folder::where('section_id', $section->id)
                            ->whereNull('parent_id')
                            ->active()
                            ->orderBy('order_index')
                            ->orderBy('name')
                            ->with(['children' => function($query) {
                                $query->active()
                                      ->orderBy('order_index')
                                      ->orderBy('name');
                            }])
                            ->get();

        foreach ($rootFolders as $folder) {
            $navigation['folders'][] = [
                'folder' => $folder,
                'pages' => Page::where('folder_id', $folder->id)
                              ->published()
                              ->orderBy('order_index')
                              ->orderBy('title')
                              ->get(['id', 'title', 'slug']),
                'children' => $folder->children->map(function($child) {
                    return [
                        'folder' => $child,
                        'pages' => Page::where('folder_id', $child->id)
                                      ->published()
                                      ->orderBy('order_index')
                                      ->orderBy('title')
                                      ->get(['id', 'title', 'slug'])
                    ];
                })
            ];
        }

        // Pages directes dans la section
        $navigation['pages'] = Page::where('section_id', $section->id)
                                  ->whereNull('folder_id')
                                  ->published()
                                  ->orderBy('order_index')
                                  ->orderBy('title')
                                  ->get(['id', 'title', 'slug']);

        return $navigation;
    }
}
