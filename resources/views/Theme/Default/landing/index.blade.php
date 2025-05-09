@extends('layouts.app')

@section('content')

    @include('Theme.Default.Assets.Timer.index')

    <div class="custom-cursor hidden"></div>
    <div class="cursor-follower hidden"></div>

    <!-- Arrière-plan vidéo amélioré -->
    <div class="fixed inset-0 z-[-2] overflow-hidden">
        <!-- Effet de vignette pour ajouter de la profondeur -->
        <div class="absolute inset-0 vignette z-[3]"></div>
        
        <!-- Superposition de dégradé animé qui améliore la vidéo sans l'obscurcir -->
        <div class="absolute inset-0 video-overlay z-[2]"></div>
        
        <!-- Dégradé maillé léger pour la texture -->
        <div class="absolute inset-0 bg-mesh-gradient opacity-30 z-[1]"></div>
        
        <!-- Élément vidéo avec des paramètres de qualité améliorés -->
        <video class="min-w-full min-h-full object-cover absolute filter brightness-110 contrast-105 saturate-105" autoplay muted loop playsinline>
            <source src="{{ asset('storage/assets/video/videotest.webm') }}" type="video/webm">
            Votre navigateur ne prend pas en charge la lecture de vidéos.
        </video>
    </div>


    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-500 overlay"></div>

    <!-- Hero Section avec espacement corrigé et meilleure intégration du séparateur -->
    <section class="relative min-h-screen flex items-center px-6 md:px-16 lg:px-24 -mt-32 mb-5" id="accueil">
        <div class="max-w-7xl mx-auto w-full">
            <div class="max-w-3xl pt-24">
                <div class="inline-block px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white text-sm font-semibold mb-6 opacity-0 animate-fadeIn delay-200">
                    <span class="mr-2 text-secondary-light">•</span> Établissement d'excellence depuis 1892
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-display font-extrabold text-white leading-tight mb-6 opacity-0 animate-fadeUp delay-400">
                    Construisez votre avenir au 
                    <span class="relative inline-block">
                        <span class="relative z-10 text-secondary-light">Lycée Louis Vincent</span>
                        <span class="absolute -bottom-3 left-0 right-0 h-1 bg-secondary-light opacity-30"></span>
                    </span>
                </h1>
                
                <p class="text-white/90 text-lg md:text-xl max-w-2xl mb-10 opacity-0 animate-fadeUp delay-600">
                    Un établissement qui allie tradition et innovation pour former les talents de demain dans un environnement stimulant et inspirant.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-5 mb-16 opacity-0 animate-fadeUp delay-800">
   
                    
                    <a href="#visite" class="relative px-8 py-4 bg-transparent text-white border-2 border-white/50 rounded-full font-display font-bold text-center transition-all group hover:border-white hover:bg-white/5">
                        <span class="relative z-10 group-hover:text-secondary-light transition-colors">Visite virtuelle</span>
                    </a>
                </div>
                
                <!-- Stats cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">
                    <div class="glass rounded-2xl p-6 transform -rotate-2 opacity-0 animate-fadeIn delay-1000 transition-all duration-300 hover:rotate-0 hover:scale-105 hover:shadow-xl border border-white/10 group hover:border-white/30 hover:bg-white/10">
                        <span class="text-4xl font-display font-extrabold text-white mb-2 block group-hover:text-secondary-light transition-colors">97.9%</span>
                        <span class="text-white/80 text-sm">Réussite au baccalauréat</span>
                    </div>
                    
                    <div class="glass rounded-2xl p-6 transform rotate-1 opacity-0 animate-fadeIn delay-1100 transition-all duration-300 hover:rotate-0 hover:scale-105 hover:shadow-xl border border-white/10 group hover:border-white/30 hover:bg-white/10">
                        <span class="text-4xl font-display font-extrabold text-white mb-2 block group-hover:text-secondary-light transition-colors">72%</span>
                        <span class="text-white/80 text-sm">Taux de mention</span>
                    </div>
                    
                    <div class="glass rounded-2xl p-6 transform -rotate-1 opacity-0 animate-fadeIn delay-1200 transition-all duration-300 hover:rotate-0 hover:scale-105 hover:shadow-xl border border-white/10 group hover:border-white/30 hover:bg-white/10">
                        <span class="text-4xl font-display font-extrabold text-white mb-2 block group-hover:text-secondary-light transition-colors">430</span>
                        <span class="text-white/80 text-sm">Élèves en terminale</span>
                    </div>
                    
                    <div class="glass rounded-2xl p-6 transform rotate-2 opacity-0 animate-fadeIn delay-1300 transition-all duration-300 hover:rotate-0 hover:scale-105 hover:shadow-xl border border-white/10 group hover:border-white/30 hover:bg-white/10">
                        <span class="text-4xl font-display font-extrabold text-white mb-2 block group-hover:text-secondary-light transition-colors">1892</span>
                        <span class="text-white/80 text-sm">Année de fondation</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-20 left-1/2 transform -translate-x-1/2 text-white opacity-0 animate-bounce-custom delay-1200 animate-fadeIn z-10">
            <div class="relative w-10 h-10 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-2 border-white/30 animate-pulse"></div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <!-- Séparateur redessiné avec meilleure intégration -->
        <div class="absolute bottom-0 left-0 w-full z-10 overflow-hidden mt-10>
            <svg class="w-full h-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path fill="#ffffff" fill-opacity="1" d="M0,64L60,69.3C120,75,240,85,360,90.7C480,96,600,96,720,85.3C840,75,960,53,1080,48C1200,43,1320,53,1380,58.7L1440,64L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"></path>
            </svg>
        </div>
    </section>
    

    <!-- À propos du lycée section -->
    <section class="relative py-24 overflow-hidden" id="lycee">
        <!-- Fond principal avec dégradé -->
        <div class="absolute inset-0 bg-gradient-to-br from-accent-blue via-white to-secondary/5"></div>
        
        <!-- Motif de vagues -->
        <div class="absolute inset-0 bg-wave-pattern opacity-60"></div>
        
        <!-- Cercles décoratifs avec effet de flou -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-primary/10 rounded-full filter blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary/10 rounded-full filter blur-3xl translate-x-1/2 translate-y-1/2"></div>
        
        <!-- Effet de brillance -->
        <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-white/30 rounded-full filter blur-2xl"></div>
        
        <div class="relative max-w-7xl mx-auto px-6 md:px-16 z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-12">
                <div class="md:w-1/2">
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-primary-dark mb-6">À propos du <span class="gradient-text">Lycée Louis Vincent</span></h2>
                    <p class="text-gray-700 mb-6">Le Lycée Louis Vincent, fondé en 1892, est un établissement qui a su maintenir sa réputation d'excellence tout en évoluant avec son temps. Située à Metz, notre institution offre un environnement propice à l'apprentissage et au développement personnel.</p>
                    <p class="text-gray-700 mb-8">Nos valeurs reposent sur l'excellence académique, l'innovation pédagogique et l'épanouissement personnel. Notre objectif est de préparer nos élèves à réussir dans un monde en constante évolution.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div class="bg-primary/5 p-5 rounded-xl border border-primary/10 hover-lift hover:shadow-xl">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white mb-4">
                                <i class="fas fa-medal text-xl"></i>
                            </div>
                            <h3 class="text-lg font-display font-semibold text-primary-dark mb-2">Excellence académique</h3>
                            <p class="text-gray-600 text-sm">Des résultats exceptionnels aux examens nationaux année après année.</p>
                        </div>
                        
                        <div class="bg-primary/5 p-5 rounded-xl border border-primary/10 hover-lift hover:shadow-xl">
                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white mb-4">
                                <i class="fas fa-microscope text-xl"></i>
                            </div>
                            <h3 class="text-lg font-display font-semibold text-primary-dark mb-2">Innovation</h3>
                            <p class="text-gray-600 text-sm">Des équipements modernes et des approches pédagogiques innovantes.</p>
                        </div>
                    </div>
                    
                    <a href="#decouvrir" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-full font-display font-semibold hover:-translate-y-1.5 hover:shadow-xl transition-all group relative overflow-hidden">
                        <span class="relative z-10">En savoir plus</span>
                        <i class="fas fa-arrow-right ml-2 relative z-10 group-hover:translate-x-1 transition-transform"></i>
                        <span class="absolute inset-0 bg-primary-light transform scale-x-0 origin-left transition-transform duration-300 group-hover:scale-x-100"></span>
                    </a>
                </div>
                
                <div class="md:w-1/2 relative">
                    <div class="relative z-10 rounded-2xl overflow-hidden shadow-xl">
                        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Lycée Louis Vincent" class="w-full h-auto">
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-secondary/20 rounded-full"></div>
                    <div class="absolute -top-6 -left-6 w-36 h-36 bg-primary/10 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Agenda -->
    <section class="relative py-24 bg-gray-50" id="agenda">
        <div class="relative max-w-6xl mx-auto px-6">

            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center justify-between mb-6">

                    <div class="flex items-center gap-6">
                        <h2 class="text-2xl">
                            <span class="text-primary font-bold">Mai</span>
                            <span class="text-gray-400 font-medium">2024</span>
                        </h2>
                        <div class="flex gap-2">
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors" id="prev-month">
                                <i class="fas fa-chevron-left text-gray-400"></i>
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors" id="next-month">
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </button>
                        </div>
                    </div>

                   
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            <span class="text-sm text-gray-600">Live</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                            <span class="text-sm text-gray-600">Réunion</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-sm text-gray-600">Événement</span>
                        </div>
                    </div>
                </div>

                <!-- Calendrier -->
                <div class="overflow-hidden">
                    <!-- Jours de la semaine -->
                    <div class="grid grid-cols-7 mb-4">
                        <div class="text-sm text-gray-600 font-medium py-2 text-center">Lundi</div>
                        <div class="text-sm text-gray-600 font-medium py-2 text-center">Mardi</div>
                        <div class="text-sm text-gray-600 font-medium py-2 text-center">Mercredi</div>
                        <div class="text-sm text-gray-600 font-medium py-2 text-center">Jeudi</div>
                        <div class="text-sm text-gray-600 font-medium py-2 text-center">Vendredi</div>
                        <div class="text-sm text-primary font-medium py-2 text-center">Samedi</div>
                        <div class="text-sm text-primary font-medium py-2 text-center">Dimanche</div>
                    </div>

                    <!-- Grille des jours -->
                    <div class="grid grid-cols-7 gap-px bg-gray-200" id="calendar-grid">
                        <!-- Les jours seront générés dynamiquement par JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Modal des détails d'événement -->
            <div id="event-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 overflow-hidden">
                    <!-- En-tête du modal -->
                    <div class="relative p-6 pb-4 border-b">
                        <button class="absolute right-6 top-6 text-gray-400 hover:text-gray-600 transition-colors" onclick="closeEventModal()">
                            <i class="fas fa-times"></i>
                        </button>
                        <span id="event-tag" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"></span>
                        <h3 id="event-title" class="text-2xl font-display font-bold text-gray-900 mt-4"></h3>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="far fa-calendar mr-2"></i>
                                <span id="event-date"></span>
                            </div>
                            <div class="flex items-center">
                                <i class="far fa-clock mr-2"></i>
                                <span id="event-time"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Corps du modal -->
                    <div class="p-6">
                        <p id="event-description" class="text-gray-600 mb-6"></p>
                        
                        <!-- Informations supplémentaires -->
                        <div class="grid grid-cols-3 gap-6 mb-6">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Lieu</h4>
                                    <p id="event-location" class="text-sm text-gray-500"></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-users text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Places</h4>
                                    <p id="event-capacity" class="text-sm text-gray-500"></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-purple-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Durée</h4>
                                    <p id="event-duration" class="text-sm text-gray-500"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3">
                            <button id="event-primary-action" class="flex-1 bg-primary text-white px-6 py-3 rounded-xl font-medium hover:bg-primary-dark transition-colors">
                            </button>
                            <button class="px-6 py-3 border border-gray-200 rounded-xl text-gray-600 font-medium hover:bg-gray-50 transition-colors">
                                <i class="far fa-bell mr-2"></i>
                                Me le rappeler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- YouTube notification badge -->
    <a href="https://www.youtube.com/watch?v=vUfvwIjv9c0" class="fixed bottom-24 right-6 group z-50" aria-label="Nouvelle vidéo YouTube">
        <div class="relative flex items-center">
            <!-- Badge container -->
            <div class="relative transform transition-all duration-300 ease-out group-hover:scale-110">
                <!-- Pulse animation ring -->
                <div class="absolute -inset-4 bg-red-500/20 rounded-full animate-pulse-ring"></div>
                
                <!-- Badge -->
                <div class="relative bg-red-600 w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition-all duration-300">
                    <i class="fab fa-youtube text-white text-2xl"></i>
                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <div class="text-red-600 text-xs font-bold">1</div>
                    </div>
                </div>
            </div>

           
            <div class="absolute right-20 top-1/2 -translate-y-1/2 pointer-events-none">
                <div class="bg-white rounded-xl shadow-2xl transform transition-all duration-300 ease-out opacity-0 translate-x-10 invisible group-hover:opacity-100 group-hover:translate-x-0 group-hover:visible group-hover:pointer-events-auto"
                     style="width: 320px;">
                    <!-- Video thumbnail container -->
                    <div class="relative rounded-t-xl overflow-hidden">
                        <div class="relative pt-[56.25%]"> <!-- 16:9 aspect ratio -->
                            <img src="https://img.youtube.com/vi/vUfvwIjv9c0/maxresdefault.jpg" 
                                 alt="Aperçu de la vidéo" 
                                 class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <!-- Play button overlay -->
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <div class="w-14 h-14 rounded-full bg-red-600/90 flex items-center justify-center transform transition-transform duration-300 hover:scale-110">
                                <i class="fas fa-play text-white text-xl pl-1"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Video info -->
                    <div class="p-4">
                        <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">
                            Nouvelle vidéo du Lycée Louis Vincent
                        </h4>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-clock mr-1.5"></i>
                            Nouvelle vidéo
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </a>


@endsection