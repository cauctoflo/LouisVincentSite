@extends('layouts.app')

@php
use App\Modules\WebTv\Controllers\WebTvController;
use App\Modules\Internat\Controllers\InternatController;

$wtc = new WebTvController();
$liveId = $wtc->getLive();

$internatController = new InternatController();
$internatConfig = $internatController->getConfig();

$config = app(App\Modules\Internat\Controllers\InternatController::class)->getConfig();

// Fonction pour extraire l'ID YouTube d'une URL
function getYoutubeId($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    preg_match($pattern, $url ?? '', $matches);
    return $matches[1] ?? null;
}

// Récupérer l'ID de la vidéo
$videoId = getYoutubeId($config['video']['url'] ?? '');
$thumbnailUrl = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : "https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80";
@endphp

@section('content')


    <div class="custom-cursor hidden"></div>
    <div class="cursor-follower hidden"></div>

    <div class="fixed inset-0 z-[-2] overflow-hidden video-container">

        <div class="absolute inset-0 vignette z-[3]"></div>
        
        <div class="absolute inset-0 video-overlay z-[2]"></div>
        
        <div class="absolute inset-0 bg-mesh-gradient opacity-30 z-[1]"></div>
        
        <video class="min-w-full h-full object-cover absolute filter brightness-110 contrast-105 saturate-105" autoplay muted loop playsinline>
            <source src="{{ asset('storage/assets/video/videotest.webm') }}" type="video/webm">
            Votre navigateur ne prend pas en charge la lecture de vidéos.
        </video>
    </div>


    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-500 overlay"></div>

    <style>
        .video-container {
            top: var(--navbar-height, 0); /* Utilise une variable CSS pour la hauteur de la navbar */
            height: calc(100vh - var(--navbar-height, 0));
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calcule la hauteur de la navbar et la définit comme variable CSS
            const updateNavbarHeight = () => {
                const navbar = document.querySelector('header');
                if (navbar) {
                    const navbarHeight = navbar.offsetHeight;
                    document.documentElement.style.setProperty('--navbar-height', navbarHeight + 'px');
                }
            };
            
            // Mettre à jour lors du chargement et du redimensionnement
            updateNavbarHeight();
            window.addEventListener('resize', updateNavbarHeight);
        });
    </script>

    <section class="relative min-h-screen flex items-center px-6 md:px-16 lg:px-24 -mt-24 before:content-[''] before:absolute before:inset-0 before:bg-black/60 before:z-0" id="accueil">
        <div class="relative z-10 max-w-7xl mx-auto w-full">
            <div class="max-w-3xl pt-24">
                <div class="inline-block px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/30 text-white text-sm font-semibold mb-6 mt-6 opacity-0 animate-fadeIn delay-200 shadow-lg">
                    <span class="mr-2 text-secondary-light">•</span> Établissement d'excellence depuis 1892
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-display font-extrabold text-white leading-tight mb-2 opacity-0 animate-fadeUp delay-400 drop-shadow-lg">
                    Construisez votre avenir au 
                    <span class="relative inline-block">
                        <span class="relative z-10 text-secondary-light">Lycée Louis Vincent</span>
                        <span class="absolute -bottom-3 left-0 right-0 h-1 bg-secondary-light opacity-50"></span>
                    </span>
                </h1>
                
                <p class="text-white text-lg md:text-xl max-w-2xl mb-10 mt-10 opacity-0 animate-fadeUp delay-600 drop-shadow-md">
                    Un établissement qui allie tradition et innovation pour former les talents de demain dans un environnement stimulant et inspirant.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-5 mb-16 opacity-0 animate-fadeUp delay-800">
                    <a href="#visite" class="relative overflow-hidden px-8 py-4 bg-white/10 backdrop-blur-sm text-white border-2 border-white/50 rounded-full font-display font-bold text-center transition-all duration-300 group hover:border-secondary-light hover:bg-secondary-light/10">
                        <span class="relative z-10 transition-colors duration-300 group-hover:text-secondary-light">Visite virtuelle</span>
                        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-0 bg-gradient-to-r from-secondary-light/10 to-transparent transition-transform duration-300 ease-out"></div>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">
                    <div class="glass rounded-2xl p-6 transform -rotate-2 opacity-0 animate-fadeIn delay-1000 transition-all duration-300 hover:rotate-0 hover:scale-105 hover:shadow-xl border border-white/20 bg-white/10 backdrop-blur-md group hover:border-white/40 hover:bg-white/20">
                        <span class="text-4xl font-display font-extrabold text-white mb-2 block group-hover:text-secondary-light transition-colors drop-shadow-md">97.9%</span>
                        <span class="text-white text-sm">Réussite au baccalauréat</span>
                    </div>
                    
                    <div class="glass rounded-2xl p-6 transform rotate-1 opacity-0 animate-fadeIn delay-1100 transition-all duration-300 hover:rotate-0 hover:scale-105 hover:shadow-xl border border-white/20 bg-white/10 backdrop-blur-md group hover:border-white/40 hover:bg-white/20">
                        <span class="text-4xl font-display font-extrabold text-white mb-2 block group-hover:text-secondary-light transition-colors drop-shadow-md">72%</span>
                        <span class="text-white text-sm">Taux de mention</span>
                    </div>
                    
                    <div class="glass rounded-2xl p-6 transform -rotate-1 opacity-0 animate-fadeIn delay-1200 transition-all duration-300 hover:rotate-0 hover:scale-105 hover:shadow-xl border border-white/20 bg-white/10 backdrop-blur-md group hover:border-white/40 hover:bg-white/20">
                        <span class="text-4xl font-display font-extrabold text-white mb-2 block group-hover:text-secondary-light transition-colors drop-shadow-md">430</span>
                        <span class="text-white text-sm">Élèves en terminale</span>
                    </div>
                    
                    <div class="glass rounded-2xl p-6 transform rotate-2 opacity-0 animate-fadeIn delay-1300 transition-all duration-300 hover:rotate-0 hover:scale-105 hover:shadow-xl border border-white/20 bg-white/10 backdrop-blur-md group hover:border-white/40 hover:bg-white/20">
                        <span class="text-4xl font-display font-extrabold text-white mb-2 block group-hover:text-secondary-light transition-colors drop-shadow-md">1892</span>
                        <span class="text-white text-sm">Année de fondation</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    <section class="relative py-24 overflow-hidden" id="lycee">
        <div class="absolute inset-0 bg-gradient-to-br from-accent-blue via-white to-secondary/5"></div>
        
        <div class="absolute inset-0 bg-wave-pattern opacity-60"></div>
        
        <div class="absolute top-0 left-0 w-96 h-96 bg-primary/10 rounded-full filter blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary/10 rounded-full filter blur-3xl translate-x-1/2 translate-y-1/2"></div>
        
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

    <section class="relative py-24 bg-gray-50" id="agenda">
        <div class="relative max-w-6xl mx-auto px-6">

            <!-- Menu déroulant vue calendrier -->
          

            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-6">
                        <h2 class="text-2xl" id="calendar-title">
                            <!-- Mois/année dynamiques -->
                        </h2>
                        <div class="flex gap-2">
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors" id="prev-period">
                                <i class="fas fa-chevron-left text-gray-400"></i>
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors" id="next-period">
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </button>
                        </div>
                    </div> 
                    <div class="flex items-center justify-end mb-4">
                        <label for="calendar-view" class="mr-4 text-blue-800 font-semibold">Vue :</label>
                        <select id="calendar-view" class="min-w-[120px] border border-blue-200 rounded-lg px-3 py-2 text-blue-800 font-semibold bg-white shadow-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            <option value="month">Mois</option>
                            <option value="week">Semaine</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-hidden">
                    <div id="calendar-header" class="grid grid-cols-7 mb-4"></div>
                    <div id="calendar-grid" class="grid grid-cols-7 gap-px bg-gray-200"></div>
                </div>
            </div>
    </section>

    <!-- Transition ligne en dégradé bleu -->
        <div class="w-3/4 h-px bg-gradient-to-r from-blue-50 via-blue-500 to-blue-50"></div>

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
                    @if (isset($liveId))
                        <!-- Titre avec badge -->
                        <div class="inline-flex items-center mb-4 px-3 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse mr-2"></span>
                            <span class="text-white text-xs font-medium tracking-wide uppercase">En direct</span>
                        </div>
                    @endif
                    
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
                            <img src="https://images.unsplash.com/photo-1498050108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
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

    <div class="flex items-center justify-center">
        <div class="w-full h-px bg-gradient-to-r from-blue-50 via-blue-500 to-blue-50"></div>
    </div>
   
    <section class="relative py-20 bg-gradient-to-br from-blue-50 via-white to-sky-50 overflow-hidden" id="internat">
        <div class="absolute inset-0 opacity-5 bg-pattern-grid"></div>
        <div class="absolute -top-40 -left-40 w-80 h-80 bg-blue-300/20 rounded-full filter blur-[100px]"></div>
        <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-sky-300/20 rounded-full filter blur-[100px]"></div>
        
        <div class="absolute left-0 right-0 top-40 h-px bg-gradient-to-r from-transparent via-blue-200 to-transparent"></div>
        <div class="absolute right-10 top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-blue-200 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-6 sm:px-10">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8 mb-16">
                <div class="md:max-w-2xl">
                    <div class="inline-flex items-center mb-4 px-3 py-1.5 bg-gradient-to-r from-blue-500 to-sky-600 rounded-full">
                        <span class="text-white text-xs font-medium tracking-wide uppercase">Cadre de vie</span>
                    </div>
                    
                    <h2 class="text-4xl sm:text-5xl font-bold text-blue-900 leading-tight mb-6">
                        Internat de l'<span class="text-blue-600">excellence</span>
                    </h2>
                    
                    <p class="text-blue-800/70 text-lg">
                        {{ $config['description'] ?? 'Un espace de vie et d\'études privilégié offrant aux élèves un environnement propice à la réussite scolaire et à l\'épanouissement personnel.' }}
                    </p>
                </div>
            </div>

            <!-- Disposition centrale avec vidéo au milieu et statistiques autour -->
            <div class="relative mb-24">
                <!-- Cercle décoratif central -->
                <div class="absolute -z-50 inset-0 rounded-full bg-blue-50/50 transform scale-105 blur-sm"></div>
                
                <!-- Conteneur flex pour disposition en croix -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center z-50">
                    <!-- Colonne gauche avec statistiques 1 et 3 -->
                    <div class="space-y-8 order-1">
                        <!-- Statistique 1 -->
                        <div class="bg-white overflow-hidden hover:border-1 border-blue-200 rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all duration-300 relative group ">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text- group-hover:bg-blue-500 transition-all duration-300 flex-shrink-0">
                                    <i class="{{ $config['informations'][0]['icon'] ?? 'fas fa-home' }} text-2xl"></i>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold text-blue-600 mb-1">{{ $config['informations'][0]['key_number'] ?? '150' }}</div>
                                    <div class="text-blue-700/70">{{ $config['informations'][0]['phrase'] ?? 'places disponibles' }}</div>
                                </div>
                            </div>
                            <div class="absolute top-1/2 -translate-y-1/2 -rotate-90 -right-44 w-64 h-48 bg-gradient-to-r from-blue-200/80 to-blue-200/60 rounded-t-full transform translate-x-full group-hover:translate-x-0 transition-transform duration-500">
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-100/70 via-blue-200/50 to-transparent blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-100/50 via-blue-200/30 to-transparent blur-[120px] opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                            </div>
                        </div>

                        <!-- Statistique 2 -->
                        <div class="bg-white overflow-hidden hover:border-1 border-blue-200 rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all duration-300 relative group ">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text- group-hover:bg-blue-500 transition-all duration-300 flex-shrink-0">
                                    <i class="{{ $config['informations'][1]['icon'] ?? 'fas fa-clock' }} text-2xl"></i>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold text-blue-600 mb-1">{{ $config['informations'][1]['key_number'] ?? '3h' }}</div>
                                    <div class="text-blue-700/70">{{ $config['informations'][1]['phrase'] ?? 'd\'études encadrées par jour' }}</div>
                                </div>
                            </div>
                            <div class="absolute top-1/2 -translate-y-1/2 -rotate-90 -right-44 w-64 h-48 bg-gradient-to-r from-blue-200/80 to-blue-200/60 rounded-t-full transform translate-x-full group-hover:translate-x-0 transition-transform duration-500">
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-100/70 via-blue-200/50 to-transparent blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-100/50 via-blue-200/30 to-transparent blur-[120px] opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne centrale avec vidéo -->
                    <div class="order-1 lg:order-2 space-y-6 ">
                        <!-- Bloc vidéo -->
                        <div class="relative rounded-2xl overflow-hidden shadow-xl max-w-xl mx-auto">
                            <div class="aspect-video overflow-hidden relative bg-white">
                                @if($videoId)
                                    <iframe 
                                        src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1&showinfo=0" 
                                        title="{{ $config['video']['title'] ?? 'Vidéo de présentation' }}"
                                        class="w-full h-full absolute inset-0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                        allowfullscreen>
                                    </iframe>
                                @else
                                    <div class="relative w-full h-full">
                                        <img src="{{ $thumbnailUrl }}" 
                                             alt="Internat du Lycée Louis Vincent" 
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 via-transparent to-blue-900/10">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="text-white text-center">
                                                    <i class="fas fa-video-slash text-4xl mb-4"></i>
                                                    <p class="text-lg">Aucune vidéo disponible</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($videoId)
                        <!-- Bloc description -->
                        <div class="bg-white rounded-xl shadow-sm max-w-xl mx-auto overflow-hidden">
                            <div class="px-6 text-center py-4 border-b border-gray-100">
                                <h3 class="text-blue-900 font-semibold text-lg">
                                    {{ $config['video']['title'] ?? 'Découvrez notre internat' }}
                                </h3>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ $config['video']['description'] ?? 'Visite guidée des installations et témoignages d\'élèves' }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                    

                        
                    <!-- Colonne droite avec statistiques 3 et 4 -->
                    <div class="space-y-8 order-3">
                        <!-- Statistique 3 -->
                        <div class="bg-white overflow-hidden hover:border-1 border-blue-200 rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all duration-300 relative group flex justify-between items-center">
                            <div class="ml-auto text-right pl-10">
                                <div class="text-3xl font-bold text-blue-600 mb-1">{{ $config['informations'][2]['key_number'] ?? '100%' }}</div>
                                <div class="text-blue-700/70">{{ $config['informations'][2]['phrase'] ?? 'repas équilibrés' }}</div>
                            </div>
                            <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text- group-hover:bg-blue-500 transition-all duration-300 flex-shrink-0 ml-4">
                                <i class="{{ $config['informations'][2]['icon'] ?? 'fas fa-utensils' }} text-2xl"></i>
                            </div>
                            <div class="absolute top-1/2 -translate-y-1/2 rotate-90 -left-44 w-64 h-48 bg-gradient-to-r from-blue-200/80 to-blue-200/60 rounded-t-full transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500">
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-100/70 via-blue-200/50 to-transparent blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-100/50 via-blue-200/30 to-transparent blur-[120px] opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                            </div>
                        </div>
                        
                        <!-- Statistique 4 -->
                        <div class="bg-white overflow-hidden hover:border-1 border-blue-200 rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all duration-300 relative group flex justify-between items-center">
                            <div class="ml-auto text-right pl-10">
                                <div class="text-3xl font-bold text-blue-600 mb-1">{{ $config['informations'][3]['key_number'] ?? '94%' }}</div>
                                <div class="text-blue-700/70">{{ $config['informations'][3]['phrase'] ?? 'de réussite au bac' }}</div>
                            </div>
                            <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text- group-hover:bg-blue-500 transition-all duration-300 flex-shrink-0 ml-4">
                                <i class="{{ $config['informations'][3]['icon'] ?? 'fas fa-graduation-cap' }} text-2xl"></i>
                            </div>
                            <div class="absolute top-1/2 -translate-y-1/2 rotate-90 -left-44 w-64 h-48 bg-gradient-to-r from-blue-200/80 to-blue-200/60 rounded-t-full transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500">
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-100/70 via-blue-200/50 to-transparent blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-100/50 via-blue-200/30 to-transparent blur-[120px] opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Points forts de l'internat -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ count($config['points_forts'] ?? [1]) > 3 ? '4' : '3' }} gap-6 mt-16">
                @forelse($config['points_forts'] ?? [] as $point_fort)
                    <div class="group bg-white rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                                <i class="{{ $point_fort['icon'] }} text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-blue-800 font-semibold text-lg mb-2">{{ $point_fort['titre'] }}</h4>
                                <p class="text-blue-700/70">{{ $point_fort['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Points forts par défaut si aucun n'est configuré -->
                    <div class="group bg-white rounded-xl p-6 border border-blue-100 shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-lg bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white shadow-md shadow-blue-300/20">
                                <i class="fas fa-bed text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-blue-800 font-semibold text-lg mb-2">Chambres confortables</h4>
                                <p class="text-blue-700/70">Espaces individuels et collectifs modernes pour un confort optimal.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Témoignages d'élèves -->
            <div class="mt-16">
                <h3 class="text-center text-2xl font-bold text-blue-900 mb-10">Ce qu'en disent nos élèves</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 {{ count($config['temoignages'] ?? []) > 2 ? 'lg:grid-cols-3' : '' }} gap-8">
                    @forelse($config['temoignages'] ?? [] as $temoignage)
                        <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl p-6 shadow-sm border border-blue-100 relative transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-blue-200 group">
                            <!-- Icône de citation -->
                            <div class="absolute top-4 right-4 text-blue-200 opacity-50 transition-transform duration-300 group-hover:scale-110 group-hover:opacity-70">
                                <i class="fas fa-quote-right text-5xl"></i>
                            </div>
                            
                            <div class="relative">
                                <p class="text-blue-800/90 italic mb-6 transition-colors duration-300 group-hover:text-blue-900">"{{ $temoignage['texte'] }}"</p>
                                
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 mr-4 transition-colors duration-300 group-hover:bg-blue-300 group-hover:text-blue-700">
                                        <span class="font-bold">{{ substr($temoignage['nom'], 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-blue-900">{{ $temoignage['nom'] }}</div>
                                        <div class="text-blue-700/70 text-sm">{{ $temoignage['statut'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Témoignages par défaut si aucun n'est configuré -->
                        <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl p-6 shadow-sm border border-blue-100 relative transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-blue-200 group">
                            <div class="absolute top-4 right-4 text-blue-200 opacity-50 transition-transform duration-300 group-hover:scale-110 group-hover:opacity-70">
                                <i class="fas fa-quote-right text-5xl"></i>
                            </div>
                            <div class="relative">
                                <p class="text-blue-800/90 italic mb-6 transition-colors duration-300 group-hover:text-blue-900">"L'internat m'a permis de trouver un équilibre parfait entre études et vie sociale. L'encadrement bienveillant et les conditions de travail optimales m'ont aidé à progresser considérablement."</p>
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 mr-4 transition-colors duration-300 group-hover:bg-blue-300 group-hover:text-blue-700">
                                        <span class="font-bold">ML</span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-blue-900">Marie L.</div>
                                        <div class="text-blue-700/70 text-sm">Élève en Terminale</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Footer avec Google Maps - Version allégée -->
    <footer class="relative bg-gradient-to-br from-blue-900 to-blue-800 text-white overflow-hidden" id="contact">           
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
                    © @php echo(date("Y")); @endphp Lycée Louis Vincent - Tous droits réservés
                </div>
                <div class="flex gap-4">
                    <a href="#" class="text-blue-200/80 hover:text-white transition-colors">Mentions légales</a>
                    <a href="#" class="text-blue-200/80 hover:text-white transition-colors">Confidentialité</a>
                    <a href="#" class="text-blue-200/80 hover:text-white transition-colors">Accessibilité</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function playVideo(element) {
            const container = element.closest('.aspect-video');
            const videoUrl = element.dataset.videoUrl;
            const iframeContainer = container.querySelector('.video-iframe-container');
            const preview = container.querySelector('.video-preview');

            // Créer l'URL de l'embed
            const embedUrl = videoUrl.replace('watch?v=', 'embed/');
            
            // Créer l'iframe
            const iframe = document.createElement('iframe');
            iframe.src = `${embedUrl}?autoplay=1`;
            iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
            iframe.allowFullscreen = true;
            iframe.className = "w-full h-full absolute inset-0";
            
            // Cacher la prévisualisation et afficher l'iframe
            preview.classList.add('hidden');
            iframeContainer.classList.remove('hidden');
            iframeContainer.appendChild(iframe);
        }
    </script>

    <script>
        window.agendaEvents = @json($evenements_json);
    </script>

    <script>
    // Utilitaires de date
    function getMonday(d) {
        d = new Date(d);
        var day = d.getDay();
        var diff = d.getDate() - (day === 0 ? 6 : day - 1);
        return new Date(d.setDate(diff));
    }
    function formatDate(date) {
        // Format YYYY-MM-DD en heure locale
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    function getWeekDates(date) {
        let monday = getMonday(date);
        let days = [];
        for (let i = 0; i < 7; i++) {
            let d = new Date(monday);
            d.setDate(monday.getDate() + i);
            days.push(new Date(d));
        }
        return days;
    }

    const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    function updateCalendarTitle(viewMode, refDate) {
        const title = document.getElementById('calendar-title');
        if (viewMode === 'week') {
            let weekDates = getWeekDates(refDate);
            let first = weekDates[0];
            let last = weekDates[6];
            let mois = monthNames[first.getMonth()];
            let annee = first.getFullYear();
            let mois2 = monthNames[last.getMonth()];
            let annee2 = last.getFullYear();
            if (mois === mois2 && annee === annee2) {
                title.innerHTML = `<span class='text-primary font-bold'>Semaine du ${first.getDate()} ${mois} ${annee}</span>`;
            } else {
                title.innerHTML = `<span class='text-primary font-bold'>Semaine du ${first.getDate()} ${mois} ${annee} au ${last.getDate()} ${mois2} ${annee2}</span>`;
            }
        } else {
            let mois = monthNames[refDate.getMonth()];
            let annee = refDate.getFullYear();
            title.innerHTML = `<span class='text-primary font-bold'>${mois}</span> <span class='text-gray-400 font-medium'>${annee}</span>`;
        }
    }

    function addMonths(date, n) {
        let d = new Date(date);
        d.setMonth(d.getMonth() + n);
        return d;
    }
    function addWeeks(date, n) {
        let d = new Date(date);
        d.setDate(d.getDate() + n*7);
        return d;
    }

    // Génération du calendrier
    function renderCalendar(viewMode, refDate) {
        updateCalendarTitle(viewMode, refDate);
        const header = document.getElementById('calendar-header');
        const grid = document.getElementById('calendar-grid');
        header.innerHTML = '';
        grid.innerHTML = '';
        const daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        daysOfWeek.forEach((d, i) => {
            header.innerHTML += `<div class="text-sm text-blue-700 font-semibold py-2 text-center">${d}</div>`;
        });
        let days = [];
        if (viewMode === 'week') {
            days = getWeekDates(refDate);
        } else {
            // Mois : on affiche toutes les cases du mois courant (avec décalage pour le 1er jour)
            let year = refDate.getFullYear();
            let month = refDate.getMonth();
            let firstDay = new Date(year, month, 1);
            let lastDay = new Date(year, month + 1, 0);
            let start = getMonday(firstDay);
            let end = new Date(lastDay);
            // Aller au dimanche de la dernière semaine
            let endDay = end.getDay();
            if (endDay !== 0) {
                end.setDate(end.getDate() + (7 - endDay));
            }
            let current = new Date(start);
            while (current <= end) {
                days.push(new Date(current));
                current.setDate(current.getDate() + 1);
            }
        }
        // Affichage des cases
        days.forEach(day => {
            let dateStr = formatDate(day);
            let events = (window.agendaEvents || []).filter(ev => ev.date === dateStr);
            let isCurrentMonth = day.getMonth() === refDate.getMonth();
            let cellClass = isCurrentMonth ? 'bg-white' : 'bg-gray-50 text-gray-400';
            let html = `<div class="min-h-[90px] p-2 ${cellClass} border border-gray-100 flex flex-col rounded-lg transition-all hover:shadow-lg cursor-pointer group">
                <div class="text-xs font-bold mb-1 text-blue-900">${day.getDate()}</div>`;
            events.forEach(ev => {
                const couleur = ev.couleur || '#2563eb';
                function pad2(n) { return n.toString().padStart(2, '0'); }
                function formatHeure(h) {
                    if (!h) return '';
                    const parts = h.split(':');
                    return pad2(parts[0]) + ':' + pad2(parts[1] || '00');
                }
                html += `<div class="rounded p-2 mb-1 flex flex-col shadow group-hover:opacity-90 transition-all cursor-pointer event-calendar-block" 
                    style="background-color:${couleur}22;border-left:4px solid ${couleur};"
                    data-titre="${ev.titre}"
                    data-description="${ev.description || ''}"
                    data-heure="${formatHeure(ev.heure_debut)} - ${formatHeure(ev.heure_fin)}\"
                    data-lieu="${ev.lieu || ''}">
                    <div class="font-semibold text-xs truncate" style="color:${couleur}">${ev.titre}</div>
                    <div class="text-xs" style="color:${couleur}">${formatHeure(ev.heure_debut)} - ${formatHeure(ev.heure_fin)}</div>
                </div>`;
            });
            html += `</div>`;
            grid.innerHTML += html;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        let viewMode = 'month';
        let refDate = new Date();
        const select = document.getElementById('calendar-view');
        select.addEventListener('change', function() {
            viewMode = this.value;
            renderCalendar(viewMode, refDate);
        });
        document.getElementById('prev-period').addEventListener('click', function() {
            if (viewMode === 'week') refDate = addWeeks(refDate, -1);
            else refDate = addMonths(refDate, -1);
            renderCalendar(viewMode, refDate);
        });
        document.getElementById('next-period').addEventListener('click', function() {
            if (viewMode === 'week') refDate = addWeeks(refDate, 1);
            else refDate = addMonths(refDate, 1);
            renderCalendar(viewMode, refDate);
        });
        renderCalendar(viewMode, refDate);
    });
    </script>

    <!-- Modale d'évènement -->
    <div id="calendarEventModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden flex flex-col">
            <div class="relative p-6 pb-4 border-b">
                <button class="absolute right-6 top-6 text-gray-400 hover:text-gray-600 transition-colors close-calendar-modal">
                    <i class="fas fa-times"></i>
                </button>
                <h3 id="calendar-modal-title" class="text-2xl font-display font-bold text-gray-900 mt-2 mb-2"></h3>
                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="far fa-clock mr-2"></i>
                        <span id="calendar-modal-heure"></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span id="calendar-modal-lieu"></span>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <p id="calendar-modal-description" class="text-gray-600 mb-2"></p>
            </div>
        </div>
    </div>

    <script>
    function openCalendarModal(titre, description, heure, lieu) {
        document.getElementById('calendar-modal-title').textContent = titre;
        document.getElementById('calendar-modal-description').textContent = description;
        document.getElementById('calendar-modal-heure').textContent = heure;
        document.getElementById('calendar-modal-lieu').textContent = lieu;
        document.getElementById('calendarEventModal').classList.remove('hidden');
        document.getElementById('calendarEventModal').classList.add('flex');
    }
    function closeCalendarModal() {
        document.getElementById('calendarEventModal').classList.add('hidden');
        document.getElementById('calendarEventModal').classList.remove('flex');
    }
    document.getElementById('calendarEventModal').querySelector('.close-calendar-modal').addEventListener('click', closeCalendarModal);

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('event-calendar-block') || e.target.closest('.event-calendar-block')) {
            const block = e.target.closest('.event-calendar-block');
            openCalendarModal(
                block.getAttribute('data-titre'),
                block.getAttribute('data-description'),
                block.getAttribute('data-heure'),
                block.getAttribute('data-lieu')
            );
        }
        if (e.target === document.getElementById('calendarEventModal')) {
            closeCalendarModal();
        }
    });
    </script>

@endsection
