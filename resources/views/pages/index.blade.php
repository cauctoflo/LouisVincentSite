@extends('layouts.app')

@section('title', 'Toutes les pages disponibles')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 text-white">
        <div class="container mx-auto px-4 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Toutes nos pages</h1>
                <p class="text-xl text-blue-100 mb-8">Découvrez l'ensemble du contenu disponible</p>
                
                <!-- Statistiques -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                    <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                        <div class="text-3xl font-bold text-white">{{ $stats['total_sections'] }}</div>
                        <div class="text-blue-200 text-sm">Sections</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                        <div class="text-3xl font-bold text-white">{{ $stats['total_pages'] }}</div>
                        <div class="text-blue-200 text-sm">Pages</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                        <div class="text-3xl font-bold text-white">{{ $stats['total_folders'] }}</div>
                        <div class="text-blue-200 text-sm">Dossiers</div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                        <div class="text-3xl font-bold text-white">{{ $stats['recent_pages']->count() }}</div>
                        <div class="text-blue-200 text-sm">Récentes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pages récentes -->
    @if($stats['recent_pages']->count() > 0)
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h2 class="text-2xl font-bold text-blue-800 mb-6 flex items-center">
                <i class="fas fa-clock text-blue-600 mr-3"></i>
                Pages récemment publiées
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($stats['recent_pages'] as $page)
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 hover:shadow-md transition-all duration-300 group">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-blue-900 group-hover:text-blue-700 transition-colors">
                                @if($page->folder)
                                    <a href="{{ route('pages.show', [$page->section->slug, $page->folder->slug, $page->slug]) }}">
                                        {{ $page->title }}
                                    </a>
                                @else
                                    <a href="{{ route('pages.section-pages.show', [$page->section->slug, $page->slug]) }}">
                                        {{ $page->title }}
                                    </a>
                                @endif
                            </h3>
                            <div class="text-sm text-blue-600 mt-1">
                                {{ $page->section->name }}
                                @if($page->folder)
                                    <span class="text-blue-400"> / {{ $page->folder->name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-blue-400">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    @if($page->excerpt)
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($page->excerpt, 80) }}</p>
                    @endif
                    <div class="text-xs text-blue-500">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $page->published_at ? $page->published_at->diffForHumans() : $page->created_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Navigation par sections -->
    <div class="container mx-auto px-4 pb-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-blue-800 mb-4">Navigation par sections</h2>
            <p class="text-lg text-gray-600">Explorez le contenu organisé par thématique</p>
        </div>

        @if($sections->count() > 0)
            <div class="space-y-8">
                @foreach($sections as $section)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- En-tête de section -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold mb-2">
                                    <a href="{{ route('pages.sections.show', $section->slug) }}" 
                                       class="hover:text-blue-200 transition-colors">
                                        {{ $section->name }}
                                    </a>
                                </h3>
                                @if($section->description)
                                    <p class="text-blue-100">{{ $section->description }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold">
                                    {{ $section->pages->count() + $section->folders->sum(function($folder) { return $folder->pages->count(); }) }}
                                </div>
                                <div class="text-blue-200 text-sm">Pages</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Pages directes -->
                            @if($section->pages->count() > 0)
                            <div>
                                <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                                    Pages principales
                                </h4>
                                <div class="space-y-3">
                                    @foreach($section->pages as $page)
                                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                                        <div class="flex-1">
                                            <h5 class="font-medium text-blue-900 group-hover:text-blue-700">
                                                <a href="{{ route('pages.section-pages.show', [$section->slug, $page->slug]) }}">
                                                    {{ $page->title }}
                                                </a>
                                            </h5>
                                            @if($page->excerpt)
                                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($page->excerpt, 60) }}</p>
                                            @endif
                                        </div>
                                        <div class="text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Dossiers et leurs pages -->
                            @if($section->folders->count() > 0)
                            <div>
                                <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                                    <i class="fas fa-folder text-blue-600 mr-2"></i>
                                    Dossiers thématiques
                                </h4>
                                <div class="space-y-4">
                                    @foreach($section->folders as $folder)
                                    <div class="border border-blue-200 rounded-lg overflow-hidden">
                                        <div class="bg-blue-50 p-3 border-b border-blue-200">
                                            <h5 class="font-semibold text-blue-800 flex items-center">
                                                <i class="fas fa-folder-open text-blue-600 mr-2"></i>
                                                <a href="{{ route('pages.folders.show', [$section->slug, $folder->slug]) }}" 
                                                   class="hover:text-blue-600 transition-colors">
                                                    {{ $folder->name }}
                                                </a>
                                                <span class="ml-auto text-sm text-blue-600">
                                                    {{ $folder->pages->count() }} pages
                                                </span>
                                            </h5>
                                            @if($folder->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $folder->description }}</p>
                                            @endif
                                        </div>
                                        @if($folder->pages->count() > 0)
                                        <div class="p-3">
                                            <div class="space-y-2">
                                                @foreach($folder->pages->take(3) as $page)
                                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded group">
                                                    <div class="flex-1">
                                                        <h6 class="text-sm font-medium text-gray-800 group-hover:text-blue-700">
                                                            <a href="{{ route('pages.show', [$section->slug, $folder->slug, $page->slug]) }}">
                                                                {{ $page->title }}
                                                            </a>
                                                        </h6>
                                                        @if($page->excerpt)
                                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($page->excerpt, 50) }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <i class="fas fa-arrow-right text-xs"></i>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @if($folder->pages->count() > 3)
                                                <div class="text-center pt-2">
                                                    <a href="{{ route('pages.folders.show', [$section->slug, $folder->slug]) }}" 
                                                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                        Voir {{ $folder->pages->count() - 3 }} pages de plus →
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Bouton pour voir toute la section -->
                        <div class="mt-6 text-center">
                            <a href="{{ route('pages.sections.show', $section->slug) }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2"></i>
                                Voir toute la section "{{ $section->name }}"
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Message si aucune section -->
            <div class="text-center py-16">
                <div class="bg-white rounded-xl shadow-lg p-12 max-w-2xl mx-auto">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-alt text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Aucune page disponible</h3>
                    <p class="text-gray-600 mb-8">
                        Il n'y a actuellement aucune page publiée sur le site.
                        Revenez plus tard pour découvrir du nouveau contenu !
                    </p>
                    <a href="{{ url('/') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300">
                        <i class="fas fa-home mr-2"></i>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'apparition progressive des cartes
    const cards = document.querySelectorAll('.bg-white');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });

    cards.forEach((card) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endsection
