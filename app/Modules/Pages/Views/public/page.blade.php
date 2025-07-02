@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation breadcrumb -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li>
                            <a href="{{ route('pages.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                                <i class="fas fa-home"></i>
                                <span class="sr-only">Accueil</span>
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <a href="{{ route('pages.sections.show', $page->section->slug) }}" 
                                   class="text-gray-500 hover:text-gray-700 transition-colors">
                                    {{ $page->section->name }}
                                </a>
                            </div>
                        </li>
                        @if($page->folder)
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <a href="{{ route('pages.folders.show', [$page->section->slug, $page->folder->slug]) }}" 
                                       class="text-gray-500 hover:text-gray-700 transition-colors">
                                        {{ $page->folder->name }}
                                    </a>
                                </div>
                            </li>
                        @endif
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-gray-900 font-medium">{{ $page->title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <a href="{{ route('pages.sections.show', $page->section->slug) }}" 
                           class="hover:text-blue-600 transition-colors">
                            {{ $page->section->name }}
                        </a>
                    </h3>
                    
                    @if($page->folder)
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <h4 class="text-md font-medium text-gray-800 mb-2">
                                <a href="{{ route('pages.folders.show', [$page->section->slug, $page->folder->slug]) }}" 
                                   class="hover:text-blue-600 transition-colors flex items-center">
                                    <i class="fas fa-folder text-blue-500 mr-2"></i>
                                    {{ $page->folder->name }}
                                </a>
                            </h4>
                        </div>
                    @endif

                    <!-- Navigation des pages du même contexte -->
                    <nav class="space-y-2">
                        @if($page->folder)
                            <!-- Autres pages du même dossier -->
                            @foreach($page->folder->pages()->where('is_published', true)->where('id', '!=', $page->id)->orderBy('order_index')->orderBy('title')->get() as $sibling)
                                <a href="{{ route('pages.show', [$page->section->slug, $page->folder->slug, $sibling->slug]) }}" 
                                   class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-file-alt text-gray-400 mr-3"></i>
                                    {{ $sibling->title }}
                                </a>
                            @endforeach
                        @else
                            <!-- Autres pages de la section (racine) -->
                            @foreach($page->section->pages()->whereNull('folder_id')->where('is_published', true)->where('id', '!=', $page->id)->orderBy('order_index')->orderBy('title')->get() as $sibling)
                                <a href="{{ route('pages.section-pages.show', [$page->section->slug, $sibling->slug]) }}" 
                                   class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-file-alt text-gray-400 mr-3"></i>
                                    {{ $sibling->title }}
                                </a>
                            @endforeach
                        @endif

                        <!-- Séparateur si il y a d'autres dossiers -->
                        @if($page->section->folders()->where('is_active', true)->count() > 0)
                            <hr class="my-4">
                            
                            <!-- Autres dossiers de la section -->
                            @foreach($page->section->folders()->where('is_active', true)->orderBy('order_index')->orderBy('name')->get() as $folder)
                                @if(!$page->folder || $folder->id !== $page->folder->id)
                                    <a href="{{ route('pages.folders.show', [$page->section->slug, $folder->slug]) }}" 
                                       class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-folder text-blue-500 mr-3"></i>
                                        {{ $folder->name }}
                                        @if($folder->pages()->where('is_published', true)->count() > 0)
                                            <span class="ml-auto text-xs text-gray-500">
                                                {{ $folder->pages()->where('is_published', true)->count() }}
                                            </span>
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </nav>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="lg:col-span-3">
                <article class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- En-tête de l'article -->
                    <header class="px-6 py-8 border-b border-gray-200">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                        
                        @if($page->excerpt)
                            <div class="text-lg text-gray-600 mb-6 leading-relaxed">
                                {{ $page->excerpt }}
                            </div>
                        @endif

                        <!-- Métadonnées -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Publié le {{ $page->published_at ? $page->published_at->format('d/m/Y à H:i') : $page->created_at->format('d/m/Y à H:i') }}
                            </div>
                            
                            @if($page->updated_at && $page->updated_at != $page->created_at)
                                <div class="flex items-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Modifié le {{ $page->updated_at->format('d/m/Y à H:i') }}
                                </div>
                            @endif

                            <div class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                {{ $page->creator->name }}
                            </div>
                        </div>

                        <!-- Tags -->
                        @if($page->tags && count($page->tags) > 0)
                            <div class="mt-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($page->tags as $tag)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </header>

                    <!-- Contenu de l'article -->
                    <div class="px-6 py-8">
                        <div class="prose prose-lg max-w-none">
                            @if($page->content)
                                <div class="page-content">
                                    {!! $page->content_html !!}
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Contenu en cours de rédaction</h3>
                                    <p class="text-gray-500">Cette page est en cours de rédaction et sera bientôt disponible.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Navigation entre pages -->
                    @php
                        $prevPage = null;
                        $nextPage = null;
                        
                        if($page->folder) {
                            $siblings = $page->folder->pages()->where('is_published', true)->orderBy('order_index')->orderBy('title')->get();
                        } else {
                            $siblings = $page->section->pages()->whereNull('folder_id')->where('is_published', true)->orderBy('order_index')->orderBy('title')->get();
                        }
                        
                        $currentIndex = $siblings->search(function($item) use ($page) {
                            return $item->id === $page->id;
                        });
                        
                        if($currentIndex !== false) {
                            $prevPage = $siblings->get($currentIndex - 1);
                            $nextPage = $siblings->get($currentIndex + 1);
                        }
                    @endphp

                    @if($prevPage || $nextPage)
                        <footer class="px-6 py-6 border-t border-gray-200 bg-gray-50">
                            <div class="flex justify-between items-center">
                                @if($prevPage)
                                    <div class="flex-1 mr-4">
                                        @if($page->folder)
                                            <a href="{{ route('pages.show', [$page->section->slug, $page->folder->slug, $prevPage->slug]) }}" 
                                               class="group flex items-center p-4 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all">
                                        @else
                                            <a href="{{ route('pages.section-pages.show', [$page->section->slug, $prevPage->slug]) }}" 
                                               class="group flex items-center p-4 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all">
                                        @endif
                                            <div class="flex-shrink-0 mr-3">
                                                <i class="fas fa-arrow-left text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-sm text-gray-500 group-hover:text-blue-600 transition-colors">Page précédente</div>
                                                <div class="text-sm font-medium text-gray-900 group-hover:text-blue-700 transition-colors truncate">
                                                    {{ $prevPage->title }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @else
                                    <div class="flex-1"></div>
                                @endif

                                @if($nextPage)
                                    <div class="flex-1 ml-4">
                                        @if($page->folder)
                                            <a href="{{ route('pages.show', [$page->section->slug, $page->folder->slug, $nextPage->slug]) }}" 
                                               class="group flex items-center p-4 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all">
                                        @else
                                            <a href="{{ route('pages.section-pages.show', [$page->section->slug, $nextPage->slug]) }}" 
                                               class="group flex items-center p-4 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all">
                                        @endif
                                            <div class="min-w-0 flex-1 text-right">
                                                <div class="text-sm text-gray-500 group-hover:text-blue-600 transition-colors">Page suivante</div>
                                                <div class="text-sm font-medium text-gray-900 group-hover:text-blue-700 transition-colors truncate">
                                                    {{ $nextPage->title }}
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ml-3">
                                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                                            </div>
                                        </a>
                                    </div>
                                @else
                                    <div class="flex-1"></div>
                                @endif
                            </div>
                        </footer>
                    @endif
                </article>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour le contenu de page */
.page-content h1 { font-size: 2rem; font-weight: bold; margin: 2rem 0 1rem; line-height: 1.2; }
.page-content h2 { font-size: 1.75rem; font-weight: bold; margin: 1.75rem 0 1rem; line-height: 1.3; }
.page-content h3 { font-size: 1.5rem; font-weight: bold; margin: 1.5rem 0 0.75rem; line-height: 1.4; }
.page-content h4 { font-size: 1.25rem; font-weight: bold; margin: 1.25rem 0 0.5rem; line-height: 1.4; }
.page-content h5 { font-size: 1.125rem; font-weight: bold; margin: 1rem 0 0.5rem; line-height: 1.5; }
.page-content h6 { font-size: 1rem; font-weight: bold; margin: 1rem 0 0.5rem; line-height: 1.5; }

.page-content p { margin: 1rem 0; line-height: 1.7; color: #374151; }
.page-content ul, .page-content ol { margin: 1rem 0; padding-left: 2rem; }
.page-content li { margin: 0.5rem 0; line-height: 1.6; }
.page-content ul li { list-style-type: disc; }
.page-content ol li { list-style-type: decimal; }

.page-content blockquote { 
    border-left: 4px solid #3b82f6; 
    margin: 1.5rem 0; 
    padding: 1rem 1.5rem; 
    background: #f8fafc; 
    font-style: italic; 
    color: #64748b; 
    border-radius: 0 0.5rem 0.5rem 0;
}

.page-content code { 
    background: #f1f5f9; 
    padding: 0.25rem 0.5rem; 
    border-radius: 0.25rem; 
    font-family: 'Monaco', 'Courier New', monospace; 
    font-size: 0.9em;
    color: #be185d;
}

.page-content pre { 
    background: #1e293b; 
    color: #e2e8f0; 
    padding: 1.5rem; 
    border-radius: 0.5rem; 
    overflow-x: auto; 
    margin: 1.5rem 0;
    line-height: 1.5;
}

.page-content pre code {
    background: transparent;
    padding: 0;
    color: inherit;
}

.page-content img { 
    max-width: 100%; 
    height: auto; 
    border-radius: 0.5rem; 
    margin: 1.5rem 0; 
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.page-content a { 
    color: #3b82f6; 
    text-decoration: underline; 
    transition: color 0.2s;
}

.page-content a:hover {
    color: #1d4ed8;
}

.page-content strong { font-weight: 700; }
.page-content em { font-style: italic; }

.page-content table {
    width: 100%;
    margin: 1.5rem 0;
    border-collapse: collapse;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    overflow: hidden;
}

.page-content th, .page-content td {
    padding: 0.75rem 1rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.page-content th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
}

.page-content hr {
    margin: 2rem 0;
    border: none;
    border-top: 1px solid #e5e7eb;
}
</style>
@endsection
