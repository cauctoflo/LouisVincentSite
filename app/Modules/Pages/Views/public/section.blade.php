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
                                <span class="text-gray-900 font-medium">{{ $section->name }}</span>
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $section->name }}</h3>
                    
                    @if($section->description)
                        <p class="text-sm text-gray-600 mb-6">{{ $section->description }}</p>
                    @endif

                    <!-- Navigation des dossiers et pages -->
                    <nav class="space-y-2">
                        <!-- Pages à la racine -->
                        @if($section->pages()->whereNull('folder_id')->where('is_published', true)->count() > 0)
                            <div class="space-y-1">
                                @foreach($section->pages()->whereNull('folder_id')->where('is_published', true)->orderBy('order_index')->orderBy('title')->get() as $page)
                                    <a href="{{ route('pages.section-pages.show', [$section->slug, $page->slug]) }}" 
                                       class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-file-alt text-gray-400 mr-3"></i>
                                        {{ $page->title }}
                                    </a>
                                @endforeach
                            </div>
                            
                            @if($section->folders()->where('is_active', true)->count() > 0)
                                <hr class="my-4">
                            @endif
                        @endif

                        <!-- Dossiers -->
                        @foreach($section->folders()->where('is_active', true)->orderBy('order_index')->orderBy('name')->get() as $folder)
                            <div>
                                <a href="{{ route('pages.folders.show', [$section->slug, $folder->slug]) }}" 
                                   class="flex items-center px-3 py-2 text-sm font-medium text-gray-900 rounded-lg hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-folder text-blue-500 mr-3"></i>
                                    {{ $folder->name }}
                                    @if($folder->pages()->where('is_published', true)->count() > 0)
                                        <span class="ml-auto text-xs text-gray-500">
                                            {{ $folder->pages()->where('is_published', true)->count() }}
                                        </span>
                                    @endif
                                </a>
                                
                                <!-- Pages du dossier -->
                                @if($folder->pages()->where('is_published', true)->count() > 0)
                                    <div class="ml-6 mt-2 space-y-1">
                                        @foreach($folder->pages()->where('is_published', true)->orderBy('order_index')->orderBy('title')->get() as $page)
                                            <a href="{{ route('pages.show', [$section->slug, $folder->slug, $page->slug]) }}" 
                                               class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                                                <i class="fas fa-file-alt text-gray-400 mr-3"></i>
                                                {{ $page->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- En-tête de section -->
                    <div class="px-6 py-8 border-b border-gray-200">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $section->name }}</h1>
                        @if($section->description)
                            <p class="text-lg text-gray-600">{{ $section->description }}</p>
                        @endif
                    </div>

                    <!-- Vue d'ensemble -->
                    <div class="p-6">
                        @if($folders->count() > 0 || $rootPages->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Dossiers -->
                                @foreach($folders as $folder)
                                    <div class="group">
                                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200 hover:border-blue-300 transition-colors">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                                        <i class="fas fa-folder text-blue-600 text-xl"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                                        <a href="{{ route('pages.folders.show', [$section->slug, $folder->slug]) }}" 
                                                           class="hover:text-blue-600 transition-colors">
                                                            {{ $folder->name }}
                                                        </a>
                                                    </h3>
                                                    @if($folder->description)
                                                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($folder->description, 120) }}</p>
                                                    @endif
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <i class="fas fa-file-alt mr-1"></i>
                                                        {{ $folder->pages()->where('is_published', true)->count() }} page(s)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Pages à la racine -->
                                @foreach($rootPages as $page)
                                    <div class="group">
                                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 hover:border-gray-300 transition-colors">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                                                        <i class="fas fa-file-alt text-gray-600 text-xl"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                                        <a href="{{ route('pages.section-pages.show', [$section->slug, $page->slug]) }}" 
                                                           class="hover:text-blue-600 transition-colors">
                                                            {{ $page->title }}
                                                        </a>
                                                    </h3>
                                                    @if($page->excerpt)
                                                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($page->excerpt, 120) }}</p>
                                                    @endif
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        Publié le {{ $page->published_at ? $page->published_at->format('d/m/Y') : $page->created_at->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- État vide -->
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun contenu disponible</h3>
                                <p class="text-gray-500">Cette section ne contient pas encore de pages ou de dossiers.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
