@vite(['resources/css/app.css', 'resources/js/app.js'])


@php
use App\Modules\WebTv\Controllers\WebTvController;

$wtc = new WebTvController();
$liveId = $wtc->getLive();
@endphp

<header class="fixed top-0 left-0 w-full z-50 transition-all duration-500" x-data="{ mobileMenuOpen: false, scrolled: false }">
    @if (isset($liveId))
<div class="w-full bg-red-600 text-white text-center py-3 top-0 left-0 z-50 flex items-center justify-center gap-3 shadow-lg">
    <span class="inline-block">
        <svg class="w-5 h-5 text-white animate-ping inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
            <circle cx="10" cy="10" r="8" />
        </svg>
    </span>
    <span class="font-semibold uppercase tracking-wide">La chaîne YouTube est en direct !</span>
    <a href="https://www.youtube.com/watch?v={{ $liveId }}" target="_blank" rel="noopener" class="ml-4 inline-flex items-center px-4 py-1.5 rounded-full bg-white text-red-600 font-bold hover:bg-red-100 transition">
        <i class="fa-brands fa-youtube mr-2"></i> Regarder le live
    </a>
</div>

@endif
    <!-- Top micro-banner avec gradient subtil -->
    <div class="h-1 bg-gradient-to-r from-blue-500 via-secondary to-primary"></div>
    
    <!-- Contact & Social Bar -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-700 text-white/90">
        <div class="container mx-auto px-4 py-1.5 flex flex-wrap justify-between items-center text-xs">
            <!-- Quick links -->
            <div class="flex space-x-4 md:space-x-6">
                <a href="tel:0387664822" class="group flex items-center space-x-1.5">
                    <span class="w-5 h-5 flex items-center justify-center rounded-full bg-white/10 group-hover:bg-primary transition-all">
                        <i class="fas fa-phone text-[10px]"></i>
                    </span>
                    <span class="group-hover:text-white transition-colors">03 87 66 48 22</span>
                </a>
                <a href="mailto:contact@louis-vincent.fr" class="group flex items-center space-x-1.5">
                    <span class="w-5 h-5 flex items-center justify-center rounded-full bg-white/10 group-hover:bg-primary transition-all">
                        <i class="fas fa-envelope text-[10px]"></i>
                    </span>
                    <span class="group-hover:text-white transition-colors">contact@louis-vincent.fr</span>
                </a>
            </div>
            
            <!-- Portal links & social -->
            <div class="flex items-center divide-x divide-white/10">
                <div class="hidden sm:flex items-center space-x-4 pr-4">
                    <a href="#" class="hover:text-white transition-colors flex items-center">
                        <i class="fas fa-user-graduate mr-1.5 text-[10px]"></i>
                        <span>Espace élèves</span>
                    </a>
                    <a href="#" class="hover:text-white transition-colors flex items-center">
                        <i class="fas fa-desktop mr-1.5 text-[10px]"></i>
                        <span>ENT</span>
                    </a>
                </div>
                <div class="flex items-center space-x-2 pl-4">
                    <a href="#" aria-label="Facebook" class="w-5 h-5 rounded-full flex items-center justify-center bg-white/10 hover:bg-blue-600 transition-all">
                        <i class="fab fa-facebook-f text-[10px]"></i>
                    </a>
                    <a href="#" aria-label="Twitter" class="w-5 h-5 rounded-full flex items-center justify-center bg-white/10 hover:bg-blue-400 transition-all">
                        <i class="fab fa-twitter text-[10px]"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="w-5 h-5 rounded-full flex items-center justify-center bg-white/10 hover:bg-pink-600 transition-all">
                        <i class="fab fa-instagram text-[10px]"></i>
                    </a>
                </div>
                </div>
            </div>
        </div>
        
    <!-- Main Navbar with Background -->
    <nav class="bg-gradient-to-r from-blue-50/90 via-white/90 to-blue-50/90 backdrop-blur-md shadow-md transition-all duration-300" :class="{'py-4': !scrolled, 'py-2': scrolled}">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="#" class="group flex items-center space-x-4 relative z-10">
                    <div class="overflow-hidden rounded-lg transition-all duration-300 shadow-md flex-shrink-0">
                        <div class="w-14 h-14 bg-white flex items-center justify-center text-primary-dark p-1.5 relative z-10 group-hover:scale-105 transition-transform duration-300">
                            <img src="{{ asset('storage/assets/images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                            <div class="absolute inset-0 bg-gradient-to-tr from-blue-600/20 via-transparent to-primary/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-blue-900 font-display font-extrabold text-xl tracking-tight leading-none">LYCÉE LOUIS VINCENT</span>
                        <div class="flex flex-wrap items-center text-blue-600/80 text-xs tracking-tight">
                            <span>EXCELLENCE</span>
                            <span class="mx-1.5">•</span>
                            <span>INNOVATION</span>
                            <span class="mx-1.5">•</span>
                            <span>TRADITION</span>
                            <span class="ml-3 pl-3 border-l border-blue-200 flex items-center">
                                <span class="font-medium text-blue-700/75">GÉNÉRAL</span>
                                <span class="mx-1.5 text-blue-400/60">|</span>
                                <span class="font-medium text-blue-700/75">TECHNOLOGIQUE</span>
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden xl:flex items-center space-x-5">
                    <a href="#accueil" class="nav-link group">
                        <span class="nav-text">Accueil</span>
                        <span class="nav-dot"></span>
                    </a>
                    
                    <a href="#lycee" class="nav-link group">
                        <span class="nav-text">Le lycée</span>
                        <span class="nav-dot"></span>
                    </a>

                    
                    <div class="relative group/menu">
                        <a href="#formation" class="nav-link group">
                            <span class="nav-text">Formation</span>
                            <i class="fas fa-chevron-down text-[10px] ml-1.5 text-blue-400"></i>
                            <span class="nav-dot"></span>
                        </a>
                        <div class="absolute left-0 top-full pt-2 w-[500px] invisible group-hover/menu:visible opacity-0 group-hover/menu:opacity-100 z-50">
                            <div class="bg-white rounded-md shadow-xl overflow-hidden">
                                <div class="h-1 bg-gradient-to-r from-blue-500 to-primary"></div>
                                <div class="p-5">
                                    <!-- En-tête -->
                                    <h2 class="text-xl font-bold text-blue-800 mb-4 text-center">Nos formations</h2>
                                    
                                    <!-- Contenu avec nouvelle disposition -->
                                    <div class="grid grid-cols-2 grid-rows-2 gap-5">
                                        <!-- Voie Générale (prend toute la hauteur à gauche) -->
                                        <div class="bg-blue-50/50 rounded-lg p-3 row-span-2">
                                            <h3 class="font-bold text-blue-700 text-base mb-3 pb-2 border-b border-blue-200">VOIE GÉNÉRALE</h3>
                                            <div class="space-y-2">
                                                <a href="#histoire-geo" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Histoire-géographie, géopolitique et sciences politiques</span>
                                                </a>
                                                <a href="#anglais" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Anglais monde contemporain</span>
                                                </a>
                                                <a href="#mathematiques" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Mathématiques</span>
                                                </a>
                                                <a href="#numerique" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Numérique et sciences informatiques</span>
                                                </a>
                                                <a href="#physique-chimie" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Physique-Chimie</span>
                                                </a>
                                                <a href="#svt" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Sciences de la vie et de la Terre</span>
                                                </a>
                                                <a href="#ingenieur" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Sciences de l'Ingénieur</span>
                                                </a>
                                                <a href="#eco-social" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Sciences économiques et sociales</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Voie Technologique (en haut à droite) -->
                                        <div class="bg-blue-50/50 rounded-lg p-3">
                                            <h3 class="font-bold text-blue-700 text-base mb-3 pb-2 border-b border-blue-200">VOIE TECHNOLOGIQUE</h3>
                                            <div class="space-y-2">
                                                <a href="#sti2d" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Sciences et Technologies de l'Industrie et du Développement Durable</span>
                                                </a>
                                                <a href="#stl" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Sciences et Technologies de Laboratoire</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Post-Bac CPGE (en bas à droite) -->
                                        <div class="bg-blue-50/50 rounded-lg p-3">
                                            <h3 class="font-bold text-blue-700 text-base mb-3 pb-2 border-b border-blue-200">POST-BAC | CPGE</h3>
                                            <div class="space-y-2">
                                                <a href="#tsi" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Technologie et Sciences Industrielles</span>
                                                </a>
                                                <a href="#tsi-info" class="flex items-center p-1.5 bg-gradient-to-r from-blue-50/70 to-blue-100/50 rounded">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 flex-shrink-0"></span>
                                                    <span class="text-sm text-blue-600">Classes préparatoires</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="#vie-lyceenne" class="nav-link group">
                        <span class="nav-text">Vie lycéenne</span>
                        <span class="nav-dot"></span>
                    </a>
                    
                    <a href="#international" class="nav-link group">
                        <span class="nav-text">International</span>
                        <span class="nav-dot"></span>
                    </a>
                    
                    <a href="#actualites" class="nav-link group">
                        <span class="nav-text">Actualités</span>
                        <span class="nav-dot"></span>
                    </a>
                    
                    <div class="ml-3 pl-3 border-l border-blue-200">
                        <a href="#contact" class="relative overflow-hidden">
                            <span class="relative block px-6 py-2.5 bg-gradient-to-r from-blue-600 to-primary text-white font-semibold rounded-md z-10">
                                Contact
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="xl:hidden relative z-50 flex items-center p-2">
                    <div class="relative flex overflow-hidden items-center justify-center rounded-full w-[50px] h-[50px] bg-blue-50">
                        <div class="flex flex-col justify-between w-[20px] h-[20px] overflow-hidden">
                            <div class="bg-blue-700 h-[2px] w-7"></div>
                            <div class="bg-blue-700 h-[2px] w-7 rounded"></div>
                            <div class="bg-blue-700 h-[2px] w-7"></div>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Hero background element (subtle pattern) -->
    <div class="absolute inset-0 w-full h-64 bg-gradient-to-b from-blue-100 to-transparent opacity-20 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMyMjIiIGZpbGwtb3BhY2l0eT0iLjA1Ij48cGF0aCBkPSJNMzYgMzBoLTZWMGg2djMwem0tNiAwaC02VjBoNnYzMHpNMzAgMzBoLTZWMGg2djMweiIvPjwvZz48L2c+PC9zdmc+')]"></div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div class="xl:hidden fixed inset-0 z-40 bg-black/80 backdrop-blur-sm" x-show="mobileMenuOpen" x-cloak></div>
    
    <!-- Mobile Menu Panel -->
    <div class="xl:hidden fixed inset-y-0 right-0 z-40 w-full max-w-sm bg-gradient-to-b from-blue-50 via-blue-100/90 to-white shadow-xl" x-show="mobileMenuOpen" x-cloak>
        <div class="flex flex-col h-full p-6 overflow-y-auto">
            <!-- Mobile menu header -->
            <div class="mb-8 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-md bg-primary flex items-center justify-center">
                        <i class="fas fa-school text-white text-lg"></i>
                    </div>
                    <div class="text-lg font-bold text-blue-800">Menu principal</div>
                </div>
                <button @click="mobileMenuOpen = false" class="p-2 text-blue-800 hover:text-primary">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Mobile menu items -->
            <div class="space-y-6 text-lg font-medium text-blue-800">
                <a href="#accueil" @click="mobileMenuOpen = false" class="mobile-link">
                    <i class="fas fa-home w-8 opacity-60"></i>
                    <span>Accueil</span>
                </a>
                
                <div x-data="{open: false}" class="space-y-3">
                    <button @click="open = !open" class="mobile-link w-full flex justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-building w-8 opacity-60"></i>
                            <span>Le lycée</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    <div x-show="open" class="pl-8 space-y-2 text-base text-blue-700/80">
                        <a href="#presentation" @click="mobileMenuOpen = false" class="block py-2">Présentation</a>
                        <a href="#equipe" @click="mobileMenuOpen = false" class="block py-2">Équipe pédagogique</a>
                        <a href="#infrastructure" @click="mobileMenuOpen = false" class="block py-2">Infrastructure</a>
                    </div>
                </div>
                
                <div x-data="{open: false}" class="space-y-3">
                    <button @click="open = !open" class="mobile-link w-full flex justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-graduation-cap w-8 opacity-60"></i>
                            <span>Formation</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    <div x-show="open" class="pl-8 space-y-5">
                        <!-- Voie Générale -->
                        <div x-data="{openGeneral: false}" class="space-y-2">
                            <button @click="openGeneral = !openGeneral" class="text-blue-800 font-semibold flex justify-between w-full">
                                <span>VOIE GÉNÉRALE</span>
                                <i class="fas fa-chevron-down text-xs text-blue-500"></i>
                            </button>
                            <div x-show="openGeneral" class="pl-4 pt-2 pb-1 space-y-2 text-sm text-blue-700/80">
                                <a href="#histoire-geo" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                    <span>Histoire-géographie, géopolitique et sciences politiques</span>
                                </a>
                                <a href="#anglais" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                    <span>Anglais monde contemporain</span>
                                </a>
                                <a href="#mathematiques" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                    <span>Mathématiques</span>
                                </a>
                                <a href="#numerique" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                    <span>Numérique et sciences informatiques</span>
                                </a>
                                <a href="#physique-chimie" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                    <span>Physique-Chimie</span>
                                </a>
                                <a href="#svt" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                    <span>Sciences de la vie et de la Terre</span>
                                </a>
                                <a href="#ingenieur" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                    <span>Sciences de l'Ingénieur</span>
                                </a>
                                <a href="#eco-social" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded hover:from-blue-100/50 hover:to-blue-200/30 transition-all">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                    <span>Sciences économiques et sociales</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Voie Technologique -->
                        <div x-data="{openTech: false}" class="space-y-2">
                            <button @click="openTech = !openTech" class="text-blue-800 font-semibold flex justify-between w-full">
                                <span>VOIE TECHNOLOGIQUE</span>
                                <i class="fas fa-chevron-down text-xs text-blue-500"></i>
                            </button>
                            <div x-show="openTech" class="pl-4 pt-2 pb-1 space-y-3 text-sm text-blue-700/80">
                                <!-- STI2D -->
                                <div class="space-y-2">
                                    <h4 class="text-xs uppercase text-blue-600 font-medium">STI2D</h4>
                                    <a href="#sti2d" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                        <span>Sciences et Technologies de l'Industrie et du Développement Durable</span>
                                    </a>
                                </div>
                                
                                <!-- STL -->
                                <div class="space-y-2">
                                    <h4 class="text-xs uppercase text-blue-600 font-medium">STL</h4>
                                    <a href="#stl" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                        <span>Sciences et Technologies de Laboratoire</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Post-Bac CPGE -->
                        <div x-data="{openCPGE: false}" class="space-y-2">
                            <button @click="openCPGE = !openCPGE" class="text-blue-800 font-semibold flex justify-between w-full">
                                <span>POST-BAC | CPGE</span>
                                <i class="fas fa-chevron-down text-xs text-blue-500"></i>
                            </button>
                            <div x-show="openCPGE" class="pl-4 pt-2 pb-1 space-y-3 text-sm text-blue-700/80">
                                <!-- TSI -->
                                <div class="space-y-2">
                                    <h4 class="text-xs uppercase text-blue-600 font-medium">TSI</h4>
                                    <a href="#tsi" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded hover:from-blue-100/50 hover:to-blue-200/30 transition-all">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                        <span>Technologie et Sciences Industrielles</span>
                                    </a>
                                </div>
                                
                                <!-- Classes préparatoires -->
                                <div class="space-y-2">
                                    <h4 class="text-xs uppercase text-blue-600 font-medium">Classes préparatoires</h4>
                                    <a href="#tsi-info" @click="mobileMenuOpen = false" class="flex items-center p-2 bg-gradient-to-r from-blue-50/50 to-blue-100/30 rounded hover:from-blue-100/50 hover:to-blue-200/30 transition-all">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                        <span>Classes préparatoires</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="#vie-lyceenne" @click="mobileMenuOpen = false" class="mobile-link">
                    <i class="fas fa-users w-8 opacity-60"></i>
                    <span>Vie lycéenne</span>
                </a>
                
                <a href="#international" @click="mobileMenuOpen = false" class="mobile-link">
                    <i class="fas fa-globe-europe w-8 opacity-60"></i>
                    <span>International</span>
                </a>
                
                <a href="#actualites" @click="mobileMenuOpen = false" class="mobile-link">
                    <i class="fas fa-newspaper w-8 opacity-60"></i>
                    <span>Actualités</span>
                </a>
            </div>
            
            <!-- Mobile Quick Access -->
            <div class="mt-8 pt-8 border-t border-blue-200/50">
                <h3 class="text-sm font-semibold text-blue-500 uppercase tracking-wider mb-4">Accès rapides</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="#" class="quick-access-btn">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Espace élèves
                    </a>
                    <a href="#" class="quick-access-btn">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        Pronote
                    </a>
                    <a href="#" class="quick-access-btn">
                        <i class="fas fa-desktop mr-2"></i>
                        ENT
                    </a>
                    <a href="#" class="quick-access-btn">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Plan d'accès
                    </a>
                </div>
            </div>
            
            <!-- Mobile Contact Button -->
            <div class="mt-auto pt-6">
                <a href="#contact" @click="mobileMenuOpen = false" class="flex items-center justify-center text-center w-full py-3 px-6 bg-gradient-to-r from-blue-600 to-primary text-white rounded-md font-semibold shadow-lg">
                    <i class="fas fa-envelope mr-2"></i>
                    Nous contacter
                </a>
                
                <div class="flex justify-between items-center mt-6 text-blue-700 text-sm">
                    <a href="tel:0387664822" class="flex items-center">
                        <i class="fas fa-phone mr-2"></i>
                        03 87 66 48 22
                    </a>
                    <span class="w-px h-4 bg-blue-200"></span>
                    <a href="mailto:contact@louis-vincent.fr" class="flex items-center">
                        <i class="fas fa-envelope mr-2"></i>
                        contact@louis-vincent.fr
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
/* Main Navigation Styles */
.nav-link {
    @apply relative flex items-center px-5 py-3 text-blue-700/80 font-medium;
}

/* Style de base pour les liens de navigation sans animation */
.hidden.xl\:flex a.nav-link.group {
    @apply relative isolation;
}

/* Style pour le texte des liens */
.nav-text {
    @apply relative;
}

/* Suppression complète de l'indicateur */
.nav-dot {
    @apply hidden;
}

/* Style de base pour le menu déroulant sans animation */
.relative.group\/menu {
    @apply relative;
}

/* Style de base pour les sous-menus sans animation */
.submenu-link {
    @apply block px-4 py-2 text-sm text-gray-700 relative;
}

.dropdown-link {
    @apply block py-1.5 px-2 text-sm text-blue-600 relative;
}

/* Style de base pour les liens du menu mobile sans animation */
.mobile-link {
    @apply flex items-center py-2.5 text-blue-700/80 rounded-lg px-2;
}

/* Style de base pour les boutons d'accès rapide sans animation */
.quick-access-btn {
    @apply flex items-center justify-center text-sm py-2 px-3 rounded-md bg-blue-100/80 text-blue-700;
}

/* Style de base pour le header sans animation de défilement */
header {
    @apply relative;
}

/* Style de base pour le bouton Contact sans animation */
.relative.group.overflow-hidden {
    @apply relative overflow-hidden;
}

/* Style de base pour les liens du header supérieur sans animation */
.group.flex.items-center.space-x-1.5 {
    @apply flex items-center;
}

/* Style de base pour les icônes sociales sans animation */
.w-5.h-5.rounded-full {
    @apply flex items-center justify-center;
}

/* Style de base pour le bouton hamburger sans animation */
.relative.flex.overflow-hidden.items-center.justify-center.rounded-full {
    @apply relative flex items-center justify-center;
}

/* Style de base pour les éléments du menu mobile sans animation */
[x-show="openGeneral"] a, [x-show="openTech"] a, [x-show="openCPGE"] a {
    @apply block;
}

/* Utility for Alpine.js */
[x-cloak] { 
    display: none !important; 
}
</style>

<script>

</script>