<div class="flex-1 flex flex-col overflow-hidden">
    
    <nav class="bg-white shadow-sm z-10 flex-shrink-0">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center lg:hidden">
                        <button id="mobile-sidebar-toggle" type="button" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                            <span class="sr-only">Ouvrir le menu</span>
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    
                    <div class="hidden lg:ml-6 lg:flex lg:items-center lg:space-x-4">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 text-sm font-medium text-gray-900 {{ request()->routeIs('dashboard') ? 'border-b-2 border-blue-500' : '' }}">
                            Dashboard
                        </a>
                        <a href="#" class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Actualités
                        </a>
                        <a href="#" class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Calendrier
                        </a>
                        <a href="#" class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Rapports
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                    </div>
                    
                    <div class="hidden md:ml-4 md:flex md:items-center md:space-x-4">
                        <button type="button" class="p-1 text-gray-400 hover:text-gray-500 relative">
                            <span class="sr-only">Voir les notifications</span>
                            <i class="far fa-bell"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <button type="button" class="p-1 text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Voir les messages</span>
                            <i class="far fa-envelope"></i>
                        </button>
                        
                        <span class="h-6 w-px bg-gray-200" aria-hidden="true"></span>
                        
                        <div class="relative ml-3">
                            <div>
                                <button type="button" class="flex items-center max-w-xs rounded-full text-sm focus:outline-none" id="user-menu-button">
                                    <span class="sr-only">Ouvrir le menu utilisateur</span>
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                        <span class="font-medium text-sm">{{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'A' }}</span>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700 hidden sm:block">{{ Auth::user() ? Auth::user()->name : 'Admin' }}</span>
                                    <i class="far fa-chevron-down ml-1 text-gray-400 hidden sm:block"></i>
                                </button>
                            </div>
                            
                            <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" id="user-dropdown" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Votre profil</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Paramètres</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 text-base font-medium {{ request()->routeIs('dashboard') ? 'text-blue-700 border-l-4 border-blue-500 bg-blue-50' : 'text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 border-l-4 border-transparent' }}">
                    Dashboard
                </a>
                <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 border-l-4 border-transparent">
                    Actualités
                </a>
                <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 border-l-4 border-transparent">
                    Calendrier
                </a>
                <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 border-l-4 border-transparent">
                    Rapports
                </a>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-sidebar-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // User dropdown toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close the dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Mobile sidebar toggle for small screens
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    const sidebar = document.querySelector('#secondary-nav');
                    if (sidebar) {
                        if (sidebar.classList.contains('translate-x-0')) {
                            sidebar.classList.remove('translate-x-0');
                            sidebar.classList.add('-translate-x-full');
                        } else {
                            sidebar.classList.remove('-translate-x-full');
                            sidebar.classList.add('translate-x-0');
                        }
                    }
                });
            }
        });
    </script>
</div>