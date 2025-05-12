  
    
@vite(['resources/css/app.css', 'resources/js/app.js'])

    
    <header class="fixed top-0 left-0 w-full z-50 transition-all duration-500">
        
        <!-- Bande d'information supérieure avec design élégant -->
        <div class="bg-gradient-to-r from-primary-dark to-primary py-1.5 border-b border-white/10">
            <div class="max-w-7xl mx-auto px-4 md:px-6 flex flex-wrap justify-between items-center">
                <div class="flex items-center space-x-4 text-white/90 text-xs">
                    <a href="tel:0387664822" class="flex items-center space-x-1.5 hover:text-white transition-colors">
                        <i class="fas fa-phone"></i>
                        <span>03 87 66 48 22</span>
                    </a>
                    <span class="hidden md:inline-block w-px h-3 bg-white/30"></span>
                    <a href="mailto:contact@louis-vincent.fr" class="flex items-center space-x-1.5 hover:text-white transition-colors">
                        <i class="fas fa-envelope"></i>
                        <span>contact@louis-vincent.fr</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="#" aria-label="Facebook" class="w-6 h-6 rounded-full flex items-center justify-center text-white/80 hover:text-white hover:bg-white/10 transition-all">
                        <i class="fab fa-facebook-f text-xs"></i>
                    </a>
                    <a href="#" aria-label="Twitter" class="w-6 h-6 rounded-full flex items-center justify-center text-white/80 hover:text-white hover:bg-white/10 transition-all">
                        <i class="fab fa-twitter text-xs"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="w-6 h-6 rounded-full flex items-center justify-center text-white/80 hover:text-white hover:bg-white/10 transition-all">
                        <i class="fab fa-instagram text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Navigation principale premium -->
        <nav class="premium-glass-nav transition-all duration-300 py-3.5 border-b border-white/10">
            <div class="max-w-7xl mx-auto px-4 md:px-6 flex items-center justify-between">
                <!-- Logo amélioré avec effet dynamique -->
                <a href="#" class="group flex items-center space-x-3 relative z-10">
                    <div class="relative">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-primary-dark font-bold text-xl shadow-lg transform transition-all duration-300 group-hover:shadow-primary/30 group-hover:scale-105">
                            <img src="{{ asset('storage/assets/images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                        </div>
                        <div class="absolute -inset-1 bg-white rounded-xl opacity-0 group-hover:opacity-20 blur-xl transition-all duration-300"></div>
                    </div>
                    
                    <div class="flex flex-col">
                        <span class="text-white font-display font-extrabold text-xl tracking-tight leading-none text-shadow-sm">LYCÉE LOUIS VINCENT</span>
                        <span class="text-white/70 text-xs">EXCELLENCE • INNOVATION • TRADITION</span>
                        <span class="text-white/70 text-xs tracking-tight leading-none">GENERAL ET TECHNOLOGIQUE</span>
                    </div>
                </a>

                <!-- Navigation desktop premium -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="#accueil" class="premium-nav-link text-white/90 font-medium px-4">Accueil</a>
                    <a href="#lycee" class="premium-nav-link text-white/90 font-medium px-4">Le lycée</a>
                    <a href="#formation" class="premium-nav-link text-white/90 font-medium px-4">Formation</a>
                    <a href="#vie-lyceenne" class="premium-nav-link text-white/90 font-medium px-4">Vie lycéenne</a>
                    <a href="#international" class="premium-nav-link text-white/90 font-medium px-4">International</a>
                    <a href="#actualites" class="premium-nav-link text-white/90 font-medium px-4">Actualités</a>
                    
                    <div class="w-px h-6 bg-white/20 mx-1"></div>
                    
                    <a href="#contact" class="ml-2 relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-secondary to-primary rounded-full blur-sm opacity-0 group-hover:opacity-100 transition duration-300"></div>
                        <button class="relative bg-white hover:bg-gray-50 text-primary-dark font-bold px-5 py-2 rounded-full transition-all transform group-hover:scale-105 group-hover:shadow-lg">
                            Contact
                        </button>
                    </a>
                </div>

                <!-- Bouton de menu mobile élégant -->
                <div class="block lg:hidden menu-toggle z-50 cursor-pointer p-2 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                    <span class="w-6"></span>
                    <span class="my-1.5 w-6"></span>
                    <span class="w-6"></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Navigation mobile améliorée -->
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 opacity-0 invisible transition-all duration-500 overlay"></div>
    <nav class="mobile-nav">
        <div class="flex flex-col h-full">
            <button class="absolute top-6 right-6 close-menu text-white/80 hover:text-white w-10 h-10 flex items-center justify-center rounded-full bg-white/10 backdrop-blur">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="space-y-6 mt-12">
                <ul class="flex flex-col space-y-6 transform translate-y-8 opacity-0 transition-all duration-300 text-lg font-medium">
                    <li><a href="#accueil" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-home mr-3 w-6 opacity-60"></i>Accueil</a></li>
                    <li><a href="#lycee" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-building mr-3 w-6 opacity-60"></i>Le lycée</a></li>
                    <li><a href="#formation" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-graduation-cap mr-3 w-6 opacity-60"></i>Formation</a></li>
                    <li><a href="#vie-lyceenne" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-users mr-3 w-6 opacity-60"></i>Vie lycéenne</a></li>
                    <li><a href="#international" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-globe-europe mr-3 w-6 opacity-60"></i>International</a></li>
                    <li><a href="#actualites" class="text-white/80 hover:text-white transition-colors flex items-center"><i class="fas fa-newspaper mr-3 w-6 opacity-60"></i>Actualités</a></li>
                </ul>
            </div>
            
            <div class="mt-auto mb-10 transform translate-y-8 opacity-0 transition-all duration-300">
                <a href="#contact" class="block w-full py-3 px-6 bg-white text-primary-dark rounded-full font-semibold text-center shadow-lg transition-all relative overflow-hidden group hover:shadow-xl hover:-translate-y-1">
                    <span class="relative z-10">Nous contacter</span>
                    <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100"></div>
                </a>
                
                <div class="flex flex-col space-y-4 mt-8 text-white/80">
                    <a href="tel:0387664822" class="flex items-center hover:text-white transition-colors">
                        <i class="fas fa-phone mr-3"></i>
                        03 87 66 48 22
                    </a>
                    <a href="mailto:contact@louis-vincent.fr" class="flex items-center hover:text-white transition-colors">
                        <i class="fas fa-envelope mr-3"></i>
                        contact@louis-vincent.fr
                    </a>
                </div>
            </div>
        </div>
    </nav>