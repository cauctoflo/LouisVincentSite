@extends('layouts.admin')

@section('title', 'Gestion des Modules')

@section('content')
    <div class="py-8 px-6 max-w-[1600px] mx-auto">
        <!-- Header section with search -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Modules</h1>
                <p class="text-sm text-gray-500">Gérez et configurez les modules du système</p>
            </div>
            
            <div class="flex gap-4 flex-wrap">
                <!-- Barre de recherche -->
                <div class="relative">
                    <input type="text" placeholder="Rechercher..." 
                        class="w-72 pl-11 pr-4 py-3 rounded-full border border-gray-200 shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Menu filtres -->
                <div class="relative">
                    <button class="group inline-flex items-center justify-center gap-2 bg-white px-5 py-3 rounded-full border border-gray-200 shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Filtres</span>
                    </button>
                    <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-64 rounded-2xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-10 overflow-hidden border border-gray-100">
                        <div class="px-5 py-4">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Statut</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded-sm focus:ring-blue-500 transition-all">
                                    <span class="ml-2 text-sm text-gray-700">Actifs</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded-sm focus:ring-blue-500 transition-all">
                                    <span class="ml-2 text-sm text-gray-700">Inactifs</span>
                                </label>
                            </div>
                        </div>
                        <div class="px-5 py-4">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Catégorie</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded-sm focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Communication</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded-sm focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Gestion</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded-sm focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Pédagogie</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded-sm focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Organisation</span>
                                </label>
                            </div>
                        </div>
                        <div class="px-5 py-4 bg-gray-50">
                            <button class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-sm">
                                Appliquer
                            </button>
                        </div>
                    </div>
                </div>
                
                <button class="inline-flex items-center gap-2 bg-blue-600 px-5 py-3 rounded-full shadow-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Nouveau module</span>
                </button>
            </div>
        </div>
        
        <!-- Catégories -->
        <div class="flex overflow-x-auto py-2 mb-8 gap-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <button class="bg-blue-600 text-white px-5 py-2.5 rounded-full text-sm font-medium whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                Tous
            </button>
            <button class="bg-white text-gray-700 px-5 py-2.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 shadow-sm border border-gray-200 transition-all">
                Communication
            </button>
            <button class="bg-white text-gray-700 px-5 py-2.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 shadow-sm border border-gray-200 transition-all">
                Gestion
            </button>
            <button class="bg-white text-gray-700 px-5 py-2.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 shadow-sm border border-gray-200 transition-all">
                Pédagogie
            </button>
            <button class="bg-white text-gray-700 px-5 py-2.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 shadow-sm border border-gray-200 transition-all">
                Évaluation
            </button>
            <button class="bg-white text-gray-700 px-5 py-2.5 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 shadow-sm border border-gray-200 transition-all">
                Organisation
            </button>
        </div>
        
        <!-- Module Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6">
            @forelse($modules as $module)
                <div class="group bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 transition-all hover:shadow-xl hover:translate-y-[-4px] duration-300">
                    <!-- En-tête de la carte avec dégradé -->
                    <div class="h-3 bg-gradient-to-r {{ $module['status'] === 'active' ? 'from-blue-500 to-blue-600' : 'from-red-300 to-red-500 ' }}"></div>
                    
                    <div class="p-6">
                        <!-- Statut et catégorie -->
                        <div class="flex justify-between items-center mb-5">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Gestion
                            </span>
                            
                            @if($module['status'] === 'active')
                            <span class="inline-flex items-center text-xs font-medium text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span>
                                Actif
                            </span>
                            @else
                            <span class="inline-flex items-center text-xs font-medium text-gray-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1"></span>
                                Inactif
                            </span>
                            @endif
                        </div>
                        
                        <!-- Contenu principal -->
                        <div class="flex items-start">
                            <!-- Icône -->
                            <div class="w-12 h-12 flex-shrink-0 rounded-xl flex items-center justify-center mr-4 {{ $module['status'] === 'active' ? 'bg-gradient-to-br from-blue-50 to-blue-100' : 'bg-gradient-to-br from-gray-50 to-gray-100' }} border {{ $module['status'] === 'active' ? 'border-blue-200' : 'border-gray-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $module['status'] === 'active' ? 'text-blue-600' : 'text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                </svg>
                            </div>
                            
                            <!-- Informations -->
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">{{ $module['name'] }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">Description du module {{ $module['name'] }}. Cette description peut contenir plusieurs lignes décrivant les fonctionnalités du module.</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md">
                                        @if(isset($module['version']) && $module['version'])
                                            v{{ $module['version'] }}
                                        @else 
                                            v1.0
                                        @endif
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span>Mis à jour récemment</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="mt-8 flex items-center">
                            <div class="flex-1">
                                <button class="text-gray-400 hover:text-blue-600 p-2 rounded-full hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                                <button class="text-gray-400 hover:text-blue-600 p-2 rounded-full hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Bouton principal -->
                            @if($module['status'] == "active")
                                @if (Route::has('personnels.' . $module['name'] . '.settings'))
                                    <a href="{{ route('personnels.' . $module['name'] . '.settings') }}" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Paramètres
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('personnels.modules.active', ['module' => $module['name']]) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-gradient-to-br from-blue-600 to-blue-600 hover:from-blue-700 hover:to-blue-700 shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Activer
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 px-4 rounded-2xl bg-white border border-gray-100 shadow-md">
                    <div class="mb-6 w-20 h-20 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun module disponible</h3>
                    <p class="text-gray-500 mb-8 text-center max-w-md">Commencez par ajouter des modules pour étendre les fonctionnalités de votre application.</p>
                    <button class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-gradient-to-br from-blue-600 to-blue-600 hover:from-blue-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Installer un module
                    </button>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-12 flex items-center justify-between">
            <div class="text-sm text-gray-700 hidden md:block">
                Affichage de <span class="font-medium">1</span> à <span class="font-medium">10</span> sur <span class="font-medium">20</span> modules
            </div>
            <div class="flex justify-center mx-auto md:mx-0">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Précédent</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">1</a>
                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">2</a>
                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">3</a>
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Suivant</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Script pour le menu déroulant des filtres
        document.addEventListener('DOMContentLoaded', function() {
            const filterButton = document.querySelector('button:has(.text-gray-500)');
            const filterDropdown = document.getElementById('filterDropdown');
            
            if (filterButton && filterDropdown) {
                filterButton.addEventListener('click', function() {
                    filterDropdown.classList.toggle('hidden');
                });
                
                // Fermer le menu si on clique ailleurs
                document.addEventListener('click', function(event) {
                    if (!filterButton.contains(event.target) && !filterDropdown.contains(event.target)) {
                        filterDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
@endsection 