@extends('layouts.app')

@section('content')


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
        {{-- <video class="min-w-full min-h-full object-cover absolute filter brightness-110 contrast-105 saturate-105" autoplay muted loop playsinline>
            <source src="{{ asset('storage/assets/video/videotest.webm') }}" type="video/webm">
            Votre navigateur ne prend pas en charge la lecture de vidéos.
        </video> --}}
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

    <!-- Transition minimaliste entre sections -->
    <div class="h-24 bg-gradient-to-b from-gray-50 to-blue-50"></div>

    <!-- Section WEBTV - Design moderne en light mode avec teintes bleues -->
    <section class="relative py-16 bg-gradient-to-br from-blue-50 via-white to-sky-100 overflow-hidden" id="webtv">
        <!-- Éléments décoratifs d'ambiance -->
        <div class="absolute inset-0 opacity-10 bg-grid-pattern"></div>
        
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-300/30 rounded-full filter blur-[100px]"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-300/30 rounded-full filter blur-[100px]"></div>

        <!-- Particules animées (effet discret) -->
        <div class="absolute inset-0 opacity-10">
            <div id="particles-webtv" class="w-full h-full"></div>
        </div>

        <!-- Conteneur principal -->
        <div class="relative max-w-7xl mx-auto px-6 sm:px-10">
            <!-- En-tête de section -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-12 lg:mb-20 gap-8">
                <div>
                    <!-- Titre avec badge -->
                    <div class="inline-flex items-center mb-4 px-3 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse mr-2"></span>
                        <span class="text-white text-xs font-medium tracking-wide uppercase">En direct</span>
                    </div>
                    
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-blue-900 leading-tight mb-6">
                        Web<span class="text-blue-600">TV</span>
                        <span class="block text-2xl sm:text-3xl lg:text-4xl mt-2 text-blue-700/80 font-normal">Studio Audiovisuel</span>
                    </h2>
                    
                    <p class="text-blue-800/70 text-lg max-w-2xl leading-relaxed">
                        La WebTV du Lycée Louis Vincent est un laboratoire audiovisuel où les élèves créent des contenus professionnels 
                        tout en développant des compétences techniques et créatives essentielles aux métiers de l'image et du son.
                    </p>
                </div>
                
                <!-- Statistiques -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5 lg:max-w-md w-full">
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl p-4 border border-blue-100 shadow-sm">
                        <div class="text-3xl font-bold text-blue-700 mb-1">24+</div>
                        <div class="text-sm text-blue-600/70">productions par an</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl p-4 border border-blue-100 shadow-sm">
                        <div class="text-3xl font-bold text-blue-700 mb-1">350+</div>
                        <div class="text-sm text-blue-600/70">élèves formés</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl p-4 border border-blue-100 shadow-sm">
                        <div class="text-3xl font-bold text-blue-700 mb-1">8+</div>
                        <div class="text-sm text-blue-600/70">équipements pros</div>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">
                <!-- Grande vidéo principale -->
                <div class="lg:col-span-8 order-2 lg:order-1">
                    <div class="relative rounded-3xl overflow-hidden group shadow-xl">
                        <!-- Overlay pour l'effet "glass" -->
                        <div class="absolute inset-px rounded-3xl z-10 bg-blue-500/5 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Image / Vidéo -->
                        <div class="aspect-video w-full rounded-3xl overflow-hidden relative">
                            <img src="https://images.unsplash.com/photo-1626379953824-12de3abfbc10?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                                alt="Studio WebTV Louis Vincent" class="w-full h-full object-cover">
                            
                            <!-- Overlay dégradé -->
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/50 via-transparent to-blue-900/20"></div>
                            
                            <!-- Bouton play -->
                            <button class="absolute inset-0 w-full h-full flex items-center justify-center group">
                                <div class="relative w-20 h-20 flex items-center justify-center">
                                    <!-- Cercle extérieur animé -->
                                    <div class="absolute inset-0 rounded-full bg-blue-500/30 blur-md transform scale-0 group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                                    <!-- Cercle principal -->
                                    <div class="absolute inset-0 rounded-full border-2 border-white/70 transform group-hover:scale-125 transition-transform"></div>
                                    <!-- Bouton central -->
                                    <div class="relative w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30 transform group-hover:scale-90 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </button>
                            
                            <!-- Info vidéo -->
                            <div class="absolute bottom-0 left-0 right-0 p-6 z-10">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                    <span class="text-white text-sm font-medium">En direct</span>
                                </div>
                                <h3 class="text-white text-xl sm:text-2xl font-bold mb-2">Dans les coulisses de notre studio</h3>
                                <p class="text-white/90 text-sm sm:text-base">Découvrez l'équipement professionnel et les techniques utilisées par nos élèves</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contrôleur vidéo moderne -->
                    <div class="mt-6 bg-white/90 backdrop-blur-md rounded-2xl p-4 border border-blue-100 shadow-sm">
                        <div class="flex items-center gap-4">
                            <button class="w-12 h-12 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 transition-colors">
                                <i class="fas fa-step-backward"></i>
                            </button>
                            
                            <button class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-300/30">
                                <i class="fas fa-play ml-0.5"></i>
                            </button>
                            
                            <button class="w-12 h-12 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 transition-colors">
                                <i class="fas fa-step-forward"></i>
                            </button>
                            
                            <div class="flex-1 ml-2">
                                <div class="relative h-2 bg-blue-100 rounded-full overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-1/3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full"></div>
                                    <div class="absolute left-1/3 top-1/2 w-3 h-3 -mt-1.5 -ml-1.5 bg-white rounded-full shadow border border-blue-300"></div>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-blue-700/70 text-xs">03:24</span>
                                    <span class="text-blue-700/70 text-xs">09:41</span>
                                </div>
                            </div>
                            
                            <div class="hidden sm:flex items-center gap-2">
                                <button class="w-10 h-10 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 transition-colors">
                                    <i class="fas fa-volume-up"></i>
                                </button>
                                
                                <button class="w-10 h-10 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 transition-colors">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-4 order-1 lg:order-2">
                    <!-- Activités proposées -->
                    <div class="space-y-4 mb-8">
                        <h3 class="text-blue-800 text-lg font-semibold mb-4 flex items-center">
                            <span class="w-1.5 h-6 bg-blue-500 mr-3 rounded-full"></span>
                            Nos activités
                        </h3>
                        
                        <!-- Activité 1 -->
                        <div class="group bg-white/90 backdrop-blur-md rounded-2xl p-5 border border-blue-100 hover:border-blue-300 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                                    <i class="fas fa-video text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-blue-800 font-medium text-lg mb-1">Réalisation vidéo</h4>
                                    <p class="text-blue-700/70">Production complète d'émissions et reportages avec techniques professionnelles</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activité 2 -->
                        <div class="group bg-white/90 backdrop-blur-md rounded-2xl p-5 border border-blue-100 hover:border-blue-300 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                                    <i class="fas fa-microphone text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-blue-800 font-medium text-lg mb-1">Podcasts & interviews</h4>
                                    <p class="text-blue-700/70">Création de contenus audio et développement des compétences en communication</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activité 3 -->
                        <div class="group bg-white/90 backdrop-blur-md rounded-2xl p-5 border border-blue-100 hover:border-blue-300 transition-colors shadow-sm hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                                    <i class="fas fa-sliders-h text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-blue-800 font-medium text-lg mb-1">Formation technique</h4>
                                    <p class="text-blue-700/70">Initiation aux métiers de l'audiovisuel : cadrage, son, montage et diffusion</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CTA Rejoindre l'équipe -->
                    <div class="relative mt-10 group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-3xl blur opacity-30 group-hover:opacity-60 transition-opacity"></div>
                        <a href="#rejoindre-webtv" class="relative flex items-center gap-6 bg-white/90 backdrop-blur-md rounded-2xl p-6 border border-blue-100 overflow-hidden shadow-sm hover:shadow-md">
                            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-300/30">
                                <i class="fas fa-user-plus text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-blue-800 font-bold text-lg mb-1">Rejoindre notre équipe</h4>
                                <p class="text-blue-700/70">Développez vos talents dans l'audiovisuel</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Productions récentes -->
            <div class="mt-16 lg:mt-24">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-blue-800 text-2xl font-bold flex items-center">
                        <span class="w-1.5 h-6 bg-blue-500 mr-3 rounded-full"></span>
                        Productions récentes
                    </h3>
                    
                    <a href="#toutes-videos" class="text-blue-600 flex items-center px-4 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors">
                        Voir tout
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Vidéo 1 -->
                    <div class="group">
                        <div class="aspect-video rounded-xl overflow-hidden relative mb-3 shadow-md">
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                alt="Vidéo sur l'orientation" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="w-14 h-14 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center">
                                    <i class="fas fa-play text-blue-600 ml-1"></i>
                                </div>
                            </div>
                            <!-- Badge catégorie -->
                            <div class="absolute top-3 left-3 px-3 py-1 bg-blue-500 rounded-full text-white text-xs font-medium">
                                Orientation
                            </div>
                        </div>
                        <h4 class="text-blue-800 font-medium mb-1">Guide des études supérieures</h4>
                        <p class="text-blue-600/70 text-sm">8.2K vues • il y a 2 semaines</p>
                    </div>
                    
                    <!-- Vidéo 2 -->
                    <div class="group">
                        <div class="aspect-video rounded-xl overflow-hidden relative mb-3 shadow-md">
                            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                alt="Vidéo sur la vie lycéenne" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="w-14 h-14 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center">
                                    <i class="fas fa-play text-blue-600 ml-1"></i>
                                </div>
                            </div>
                            <!-- Badge catégorie -->
                            <div class="absolute top-3 left-3 px-3 py-1 bg-indigo-500 rounded-full text-white text-xs font-medium">
                                Vie lycéenne
                            </div>
                        </div>
                        <h4 class="text-blue-800 font-medium mb-1">Journée d'intégration 2023</h4>
                        <p class="text-blue-600/70 text-sm">5.7K vues • il y a 1 mois</p>
                    </div>
                    
                    <!-- Vidéo 3 -->
                    <div class="group">
                        <div class="aspect-video rounded-xl overflow-hidden relative mb-3 shadow-md">
                            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                alt="Vidéo sur les séries technologiques" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="w-14 h-14 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center">
                                    <i class="fas fa-play text-blue-600 ml-1"></i>
                                </div>
                            </div>
                            <!-- Badge catégorie -->
                            <div class="absolute top-3 left-3 px-3 py-1 bg-sky-600 rounded-full text-white text-xs font-medium">
                                Formation
                            </div>
                        </div>
                        <h4 class="text-blue-800 font-medium mb-1">Découvrir la série STI2D</h4>
                        <p class="text-blue-600/70 text-sm">4.3K vues • il y a 2 mois</p>
                    </div>
                    
                    <!-- Vidéo 4 -->
                    <div class="group">
                        <div class="aspect-video rounded-xl overflow-hidden relative mb-3 shadow-md">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                alt="Vidéo podcast" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="w-14 h-14 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center">
                                    <i class="fas fa-play text-blue-600 ml-1"></i>
                                </div>
                            </div>
                            <!-- Badge catégorie -->
                            <div class="absolute top-3 left-3 px-3 py-1 bg-blue-600 rounded-full text-white text-xs font-medium">
                                Podcast
                            </div>
                        </div>
                        <h4 class="text-blue-800 font-medium mb-1">Paroles de lycéens - Épisode 3</h4>
                        <p class="text-blue-600/70 text-sm">3.8K vues • il y a 3 mois</p>
                    </div>
                </div>
            </div>
            
            <!-- Call to action principal -->
            <div class="mt-16 lg:mt-24 text-center">
                <div class="inline-block mx-auto mb-6 px-4 py-1.5 bg-blue-50 backdrop-blur-md rounded-full border border-blue-100">
                    <span class="text-blue-700 text-sm">Vous avez un projet audiovisuel ?</span>
                </div>
                
                <h3 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-blue-900 mb-6 max-w-4xl mx-auto">
                    Développez vos talents et rejoignez notre équipe WebTV
                </h3>
                
                <p class="text-blue-700/80 text-lg max-w-2xl mx-auto mb-10">
                    Que vous soyez intéressé par la réalisation, le montage, le journalisme ou la technique, 
                    notre studio offre l'environnement idéal pour développer vos compétences.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#rejoindre-webtv" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full text-lg font-medium hover:shadow-lg hover:shadow-blue-300/30 transition-shadow">
                        Rejoindre l'équipe
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    
                    <a href="#contactez-nous" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 rounded-full text-lg font-medium border border-blue-200 hover:bg-blue-50 transition-colors">
                        Contacter l'équipe
                    </a>
                </div>
            </div>
        </div>

        <!-- Ajouter un script pour les particules -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof particlesJS !== 'undefined') {
                    particlesJS('particles-webtv', {
                        particles: {
                            number: { value: 30, density: { enable: true, value_area: 800 } },
                            color: { value: "#4B79CF" },
                            shape: { type: "circle" },
                            opacity: { value: 0.2, random: true },
                            size: { value: 3, random: true },
                            line_linked: { enable: true, distance: 150, color: "#4B79CF", opacity: 0.1, width: 1 },
                            move: { enable: true, speed: 1, direction: "none", random: true, straight: false, out_mode: "out" }
                        }
                    });
                }
            });
        </script>
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

    <!-- Transition minimaliste entre sections -->
    <div class="h-24 bg-gradient-to-b from-blue-50 to-slate-50"></div>

    <!-- Section Internat de l'excellence -->
    <section class="relative py-20 bg-gradient-to-br from-blue-50 via-white to-sky-50 overflow-hidden" id="internat">
        <!-- Éléments décoratifs d'ambiance -->
        <div class="absolute inset-0 opacity-5 bg-pattern-grid"></div>
        <div class="absolute -top-40 -left-40 w-80 h-80 bg-blue-300/20 rounded-full filter blur-[100px]"></div>
        <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-sky-300/20 rounded-full filter blur-[100px]"></div>
        
        <!-- Ligne décorative courbe -->
        <div class="absolute left-0 right-0 top-40 h-px bg-gradient-to-r from-transparent via-blue-200 to-transparent"></div>
        <div class="absolute right-10 top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-blue-200 to-transparent"></div>

        <!-- Conteneur principal -->
        <div class="relative max-w-7xl mx-auto px-6 sm:px-10">
            <!-- En-tête de section avec disposition horizontale -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8 mb-16">
                <div class="md:max-w-2xl">
                    <!-- Badge décoratif -->
                    <div class="inline-flex items-center mb-4 px-3 py-1.5 bg-gradient-to-r from-blue-500 to-sky-600 rounded-full">
                        <span class="text-white text-xs font-medium tracking-wide uppercase">Cadre de vie</span>
                    </div>
                    
                    <h2 class="text-4xl sm:text-5xl font-bold text-blue-900 leading-tight mb-6">
                        Internat de l'<span class="text-blue-600">excellence</span>
                    </h2>
                    
                    <p class="text-blue-800/70 text-lg">
                        Un espace de vie et d'études privilégié offrant aux élèves un environnement propice 
                        à la réussite scolaire et à l'épanouissement personnel.
                    </p>
                </div>
                
                <!-- Call-to-action déplacé à droite -->
                <div class="md:self-end">
                    <a href="#internat-info" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-sky-600 text-white rounded-lg font-medium hover:shadow-lg hover:shadow-blue-300/30 transition-all whitespace-nowrap">
                        Demander des informations
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Disposition centrale avec vidéo au milieu et statistiques autour -->
            <div class="relative mb-24">
                <!-- Cercle décoratif central -->
                <div class="absolute inset-0 rounded-full bg-blue-50/50 transform scale-105 blur-sm"></div>
                
                <!-- Conteneur flex pour disposition en croix -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                    <!-- Colonne gauche avec statistiques 1 et 3 -->
                    <div class="space-y-8 order-2 lg:order-1">
                        <!-- Statistique 1 -->
                        <div class="bg-white rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <i class="fas fa-home text-2xl"></i>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold text-blue-600 mb-1">150</div>
                                    <div class="text-blue-700/70">places disponibles</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistique 3 -->
                        <div class="bg-white rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold text-blue-600 mb-1">3h</div>
                                    <div class="text-blue-700/70">d'études encadrées par jour</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Point fort 1 visible sur mobile -->
                        <div class="lg:hidden group bg-white rounded-xl p-5 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                                    <i class="fas fa-bed text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-blue-800 font-medium text-lg mb-1">Chambres confortables</h4>
                                    <p class="text-blue-700/70">Espaces individuels et collectifs modernes pour un confort optimal.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne centrale avec vidéo -->
                    <div class="order-1 lg:order-2">
                        <div class="relative rounded-2xl overflow-hidden shadow-xl max-w-xl mx-auto">
                            <!-- Cadre vidéo avec effet de superposition élégant -->
                            <div class="aspect-video overflow-hidden relative">
                                <!-- Placez ici la vidéo ou l'image temporaire -->
                                <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                                    alt="Internat du Lycée Louis Vincent" class="w-full h-full object-cover">
                                
                                <!-- Superposition avec dégradé subtil -->
                                <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 via-transparent to-blue-900/10"></div>
                                
                                <!-- Bouton de lecture -->
                                <button class="absolute inset-0 w-full h-full flex items-center justify-center group">
                                    <div class="relative w-20 h-20 flex items-center justify-center">
                                        <!-- Cercle extérieur animé -->
                                        <div class="absolute inset-0 rounded-full bg-blue-500/30 blur-md transform scale-0 group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                                        <!-- Cercle principal -->
                                        <div class="absolute inset-0 rounded-full border-2 border-white/70 transform group-hover:scale-125 transition-transform"></div>
                                        <!-- Bouton central -->
                                        <div class="relative w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center shadow-lg shadow-blue-500/30 transform group-hover:scale-90 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </button>
                                
                                <!-- Titre de la vidéo -->
                                <div class="absolute bottom-0 left-0 right-0 p-6 z-10">
                                    <h3 class="text-white text-xl font-bold mb-2">Découvrez notre internat</h3>
                                    <p class="text-white/90 text-sm">Visite guidée des installations et témoignages d'élèves</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite avec statistiques 2 et 4 -->
                    <div class="space-y-8 order-3">
                        <!-- Statistique 2 -->
                        <div class="bg-white rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <i class="fas fa-utensils text-2xl"></i>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold text-blue-600 mb-1">100%</div>
                                    <div class="text-blue-700/70">repas équilibrés</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistique 4 -->
                        <div class="bg-white rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <i class="fas fa-medal text-2xl"></i>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold text-blue-600 mb-1">94%</div>
                                    <div class="text-blue-700/70">de réussite au bac</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Point fort 2 visible sur mobile -->
                        <div class="lg:hidden group bg-white rounded-xl p-5 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                                    <i class="fas fa-book-open text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-blue-800 font-medium text-lg mb-1">Études encadrées</h4>
                                    <p class="text-blue-700/70">Accompagnement pédagogique quotidien pour la réussite de tous.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Atouts de l'internat - visible sur desktop -->
            <div class="hidden lg:grid grid-cols-3 gap-6 mt-16">
                <!-- Atout 1 -->
                <div class="group bg-white rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                            <i class="fas fa-bed text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-blue-800 font-semibold text-lg mb-2">Chambres confortables</h4>
                            <p class="text-blue-700/70">Espaces individuels et collectifs modernes pour un confort optimal et une ambiance propice aux études.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Atout 2 -->
                <div class="group bg-white rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-blue-800 font-semibold text-lg mb-2">Études encadrées</h4>
                            <p class="text-blue-700/70">Accompagnement pédagogique quotidien par des enseignants qualifiés pour la réussite de tous les élèves.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Atout 3 -->
                <div class="group bg-white rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-blue-800 font-semibold text-lg mb-2">Vie communautaire</h4>
                            <p class="text-blue-700/70">Activités culturelles et sportives pour développer l'esprit d'équipe et créer des liens durables.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Témoignages d'élèves -->
            <div class="mt-16">
                <h3 class="text-center text-2xl font-bold text-blue-900 mb-10">Ce qu'en disent nos élèves</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Témoignage 1 -->
                    <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl p-6 shadow-sm border border-blue-100 relative">
                        <!-- Icône de citation -->
                        <div class="absolute top-4 right-4 text-blue-200 opacity-50">
                            <i class="fas fa-quote-right text-5xl"></i>
                        </div>
                        
                        <div class="relative">
                            <p class="text-blue-800/90 italic mb-6">"L'internat m'a permis de trouver un équilibre parfait entre études et vie sociale. L'encadrement bienveillant et les conditions de travail optimales m'ont aidé à progresser considérablement dans mes résultats scolaires."</p>
                            
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 mr-4">
                                    <span class="font-bold">ML</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-blue-900">Marie L.</div>
                                    <div class="text-blue-700/70 text-sm">Élève en Terminale</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Témoignage 2 -->
                    <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl p-6 shadow-sm border border-blue-100 relative">
                        <!-- Icône de citation -->
                        <div class="absolute top-4 right-4 text-blue-200 opacity-50">
                            <i class="fas fa-quote-right text-5xl"></i>
                        </div>
                        
                        <div class="relative">
                            <p class="text-blue-800/90 italic mb-6">"Intégrer l'internat a été une décision déterminante pour mon parcours. J'y ai trouvé un cadre structurant, des amitiés solides et un accompagnement personnalisé qui m'ont permis de me dépasser."</p>
                            
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 mr-4">
                                    <span class="font-bold">TK</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-blue-900">Thomas K.</div>
                                    <div class="text-blue-700/70 text-sm">Ancien élève, aujourd'hui en prépa</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Transition minimaliste entre sections -->
    <div class="h-24 bg-gradient-to-b from-sky-50 to-blue-900/5"></div>

    <!-- Footer avec Google Maps - Version allégée -->
    <footer class="relative bg-gradient-to-br from-blue-900 to-blue-800 text-white overflow-hidden" id="contact">
        <!-- Éléments décoratifs minimaux -->
        <div class="absolute inset-0 bg-pattern-grid opacity-5"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-blue-500/20 rounded-full filter blur-[80px]"></div>
        
        <div class="max-w-7xl mx-auto px-6 sm:px-10 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <!-- Logo et infos - 2 colonnes -->
                <div class="lg:col-span-2">
                    <!-- Logo et slogan -->
                    <div class="flex items-center mb-5">
                        <div class="mr-3 w-10 h-10 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">LV</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Lycée Louis Vincent</h3>
                            <p class="text-blue-200/80 text-xs">Établissement d'excellence depuis 1892</p>
                        </div>
                    </div>
                    
                    <!-- Coordonnées simplifiées -->
                    <ul class="space-y-2.5 mb-6">
                        <li class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-blue-300 text-sm"></i>
                            </div>
                            <span class="text-blue-100">2 Rue de Verdun (près de la rue de Toul), 57000 Metz</span>
                        </li>
                        <li class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-phone text-blue-300 text-sm"></i>
                            </div>
                            <span class="text-blue-100">+33 3 87 38 13 30</span>
                        </li>
                        <li class="flex items-center text-sm">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-envelope text-blue-300 text-sm"></i>
                            </div>
                            <span class="text-blue-100">contact@lycee-louis-vincent.fr</span>
                        </li>
                    </ul>
                    
                    <!-- Réseaux sociaux compacts -->
                    <div class="flex gap-2">
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-blue-600 flex items-center justify-center transition-colors">
                            <i class="fab fa-facebook-f text-white text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-blue-400 flex items-center justify-center transition-colors">
                            <i class="fab fa-twitter text-white text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-red-600 flex items-center justify-center transition-colors">
                            <i class="fab fa-youtube text-white text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-purple-600 flex items-center justify-center transition-colors">
                            <i class="fab fa-instagram text-white text-sm"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Liens rapides - 1 colonne -->
                <div class="lg:col-span-1">
                    <h4 class="text-base font-semibold text-white mb-4">
                        Liens rapides
                    </h4>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="#lycee" class="text-blue-100 hover:text-white transition-colors text-sm flex items-center">
                                <i class="fas fa-chevron-right text-xs text-blue-400 mr-2"></i>
                                À propos du lycée
                            </a>
                        </li>
                        <li>
                            <a href="#agenda" class="text-blue-100 hover:text-white transition-colors text-sm flex items-center">
                                <i class="fas fa-chevron-right text-xs text-blue-400 mr-2"></i>
                                Agenda
                            </a>
                        </li>
                        <li>
                            <a href="#webtv" class="text-blue-100 hover:text-white transition-colors text-sm flex items-center">
                                <i class="fas fa-chevron-right text-xs text-blue-400 mr-2"></i>
                                WebTV
                            </a>
                        </li>
                        <li>
                            <a href="#internat" class="text-blue-100 hover:text-white transition-colors text-sm flex items-center">
                                <i class="fas fa-chevron-right text-xs text-blue-400 mr-2"></i>
                                Internat
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-blue-100 hover:text-white transition-colors text-sm flex items-center">
                                <i class="fas fa-chevron-right text-xs text-blue-400 mr-2"></i>
                                Espace élèves
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- OpenStreetMap - 2 colonnes -->
                <div class="lg:col-span-2">
                    <div class="h-[250px] rounded-xl overflow-hidden shadow-lg bg-white/5 p-1 backdrop-blur-sm">
                        <!-- Intégration OpenStreetMap avec zoom légèrement réduit -->
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0" 
                            src="https://www.openstreetmap.org/export/embed.html?bbox=6.166006326675415%2C49.10458417395074%2C6.169514656066895%2C49.10588082776709&amp;layer=mapnik&amp;marker=49.105185%2C6.167772" 
                            class="w-full h-full rounded-lg"
                            style="border:0;">
                        </iframe>
                        <div class="mt-1 text-blue-200/80 text-[10px] text-right">
                            <a href="https://www.openstreetmap.org/#map=17/49.105185/6.167772" target="_blank" class="hover:text-white">Voir en plein écran</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Séparateur fin -->
            <div class="h-px bg-gradient-to-r from-transparent via-blue-400/30 to-transparent my-6"></div>

            <!-- Copyright et mentions légales simplifiés -->
            <div class="flex flex-col sm:flex-row justify-between items-center text-xs">
                <div class="text-blue-200/70 mb-3 sm:mb-0">
                    © 2024 Lycée Louis Vincent - Tous droits réservés
                </div>
                <div class="flex gap-4">
                    <a href="#" class="text-blue-200/80 hover:text-white transition-colors">Mentions légales</a>
                    <a href="#" class="text-blue-200/80 hover:text-white transition-colors">Confidentialité</a>
                    <a href="#" class="text-blue-200/80 hover:text-white transition-colors">Accessibilité</a>
                </div>
            </div>
        </div>
    </footer>

@endsection