@extends('layouts.app')

@section('content')

<div class="bg-gradient-to-r from-blue-700 to-indigo-800 text-white relative overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC40Ij48cGF0aCBkPSJNMzAgMzBoMzB2MzBoLTMweiIvPjwvZz48L2c+PC9zdmc+'); background-repeat: repeat;"></div>
    </div>
    
    <div class="container mx-auto px-4 py-16 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-3/5">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow">Actualités</h1>
                <p class="text-xl leading-relaxed mb-8">Bienvenue sur la page des actualités du Lycée Louis Vincent. Restez informé des derniers événements et informations importantes.</p>
                <div class="flex space-x-4">
                    <a href="#latest" class="bg-white text-blue-700 hover:bg-blue-50 transition duration-300 px-6 py-3 rounded-lg font-medium shadow-lg">Dernières nouvelles</a>
                    <a href="#calendar" class="bg-transparent hover:bg-white/10 border-2 border-white transition duration-300 px-6 py-3 rounded-lg font-medium">Calendrier</a>
                </div>
            </div>
            {{-- <div class="md:w-2/5 mt-8 md:mt-0">
                <div class="bg-white/20 backdrop-blur-sm p-6 rounded-xl shadow-xl border border-white/30">
                    <h2 class="text-2xl font-bold mb-4">À la une</h2>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-500 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span>Rentrée scolaire 2023-2024</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-500 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <span>Résultats des examens</span>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    
    <div class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-blue-900/50 to-transparent"></div>
</div>

<div class="bg-white py-10"> 
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold mb-6">Actualités</h1>

        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Nos sections spécialisées</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($sections as $section)
                @php 
                    $color = $section->valid_color ?? 'blue';
                @endphp

                <div class="bg-gradient-to-br from-{{ $color }}-50 to-{{ $color }}-100 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 border border-{{ $color }}-200 relative group">
                    
                    <!-- Header avec image, icône ou couleur -->
                    <div class="h-32 bg-gradient-to-br from-{{ $color }}-500 to-{{ $color }}-600 flex items-center justify-center relative overflow-hidden">
                        @if($section->hasImage())
                            <!-- Image personnalisée -->
                            <img src="{{ $section->image_url }}" 
                                 alt="{{ $section->name }}" 
                                 class="h-16 w-16 object-cover rounded-lg border-2 border-white/20 relative z-10 group-hover:scale-110 transition-transform duration-300">
                        @elseif($section->hasIcon())
                            <!-- Icône Font Awesome -->
                            <i class="{{ $section->icon }} text-4xl text-white relative z-10 group-hover:scale-110 transition-transform duration-300"></i>
                        @else
                            <!-- Icône par défaut -->
                            <i class="fas fa-folder text-4xl text-white relative z-10 group-hover:scale-110 transition-transform duration-300"></i>
                        @endif
                        
                        <!-- Effet de brillance au survol -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    </div>
                    
                    <!-- Contenu -->
                    <div class="p-6 relative z-10">
                        <h3 class="text-xl font-bold mb-2 text-{{ $color }}-800 group-hover:text-{{ $color }}-900 transition-colors">
                            {{ $section->name }}
                        </h3>
                        
                        @if($section->description)
                            <p class="text-gray-700 mb-4 text-sm leading-relaxed">
                                {{ Str::limit($section->description, 120) }}
                            </p>
                        @else
                            <p class="text-gray-700 mb-4 text-sm leading-relaxed">
                                Découvrez le contenu de cette section et restez informé des dernières actualités.
                            </p>
                        @endif
                        
                        <a href="{{ route('pages.sections.show', $section->slug) }}" 
                           class="inline-flex items-center px-4 py-2 bg-{{ $color }}-600 text-white rounded-md hover:bg-{{ $color }}-700 transition-all duration-200 text-sm font-medium group/btn">
                            En savoir plus 
                            <i class="fas fa-arrow-right ml-2 transform group-hover/btn:translate-x-1 transition-transform duration-200"></i>
                        </a>
                    </div>
                    
                    <!-- Badge du nombre de pages (optionnel) -->
                    @if($section->pages->count() > 0)
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-full text-xs font-medium text-{{ $color }}-800 border border-white/50">
                            {{ $section->pages->count() }} {{ Str::plural('page', $section->pages->count()) }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Section Actualités avec design moderne -->
<div class="bg-gradient-to-br from-slate-50 to-blue-50 py-16 relative overflow-hidden">
    <!-- Pattern de fond subtil -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMwMDAiIGZpbGwtb3BhY2l0eT0iMC4zIj48cGF0aCBkPSJNMzAgMzBoMzB2MzBoLTMweiIvPjwvZz48L2c+PC9zdmc+'); background-repeat: repeat;"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- En-tête de section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
            <h2 class="text-4xl font-bold mb-4 bg-gradient-to-r from-blue-800 to-indigo-800 bg-clip-text text-transparent">Dernières Nouvelles</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Restez informé des derniers événements et actualités du Lycée Louis Vincent</p>
        </div>

        <!-- Grille d'actualités -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Actualité 1 -->
            <article class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-blue-200 hover:-translate-y-1">
                <div class="h-48 bg-gradient-to-br from-blue-500 to-indigo-600 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        15 Juin 2025
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-3 text-gray-800 group-hover:text-blue-600 transition-colors">Rentrée scolaire 2025-2026</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">Découvrez toutes les informations importantes concernant la prochaine rentrée scolaire et les nouveautés du lycée.</p>
                    <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium group-hover:translate-x-1 transition-all">
                        Lire la suite 
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </article>

            <!-- Actualité 2 -->
            <article class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-green-200 hover:-translate-y-1">
                <div class="h-48 bg-gradient-to-br from-green-500 to-emerald-600 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium text-green-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        10 Juin 2025
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-3 text-gray-800 group-hover:text-green-600 transition-colors">Résultats du Baccalauréat 2025</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">Félicitations à nos élèves pour leurs excellents résultats au baccalauréat 2025 avec un taux de réussite exceptionnel.</p>
                    <a href="#" class="inline-flex items-center text-green-600 hover:text-green-800 font-medium group-hover:translate-x-1 transition-all">
                        Voir les résultats
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </article>

            <!-- Actualité 3 -->
            <article class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-purple-200 hover:-translate-y-1">
                <div class="h-48 bg-gradient-to-br from-purple-500 to-pink-600 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium text-purple-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        5 Juin 2025
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-3 text-gray-800 group-hover:text-purple-600 transition-colors">Voyage culturel en Europe</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">Les élèves de la section européenne partent découvrir les capitales européennes dans le cadre de leur projet pédagogique.</p>
                    <a href="#" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium group-hover:translate-x-1 transition-all">
                        Découvrir le projet
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </article>
        </div>

        <!-- Bouton pour voir toutes les actualités -->
        <div class="text-center mt-12">
            <a href="#" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                Voir toutes les actualités
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </div>
</div>


@endsection