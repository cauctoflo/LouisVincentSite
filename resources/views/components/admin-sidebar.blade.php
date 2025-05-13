<!-- Main sidebar layout -->
<div class="flex h-screen overflow-hidden bg-gray-50">
    <!-- Narrow icon sidebar -->
    <div class="w-16 bg-white border-r border-gray-100 flex-shrink-0 flex flex-col">
        <div class="h-16 flex items-center justify-center border-b border-gray-100">
            <a href="{{ route('dashboard') }}" class="text-blue-600">
                <i class="fas fa-school text-xl"></i>
            </a>
        </div>
        
        <nav class="flex-1 flex flex-col mt-6">
            <button type="button" id="dashboard-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }} sidebar-toggle" data-section="dashboard">
                <i class="fas fa-th-large"></i>
            </button>
            <button type="button" id="sales-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="sales">
                <i class="fas fa-shopping-cart"></i>
            </button>
            <button type="button" id="inventory-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="inventory">
                <i class="fas fa-box"></i>
            </button>
            <button type="button" id="users-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="users">
                <i class="fas fa-users"></i>
            </button>
            <button type="button" id="tags-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="tags">
                <i class="fas fa-tag"></i>
            </button>
        </nav>
        
        <div class="mt-auto mb-6">
            <button type="button" id="settings-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="settings">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>
    
    <!-- Secondary expandable sidebar -->
    <div id="secondary-nav" class="w-60 bg-white flex-shrink-0 overflow-y-auto transition-all duration-300 ease-in-out">
        <div class="h-16 flex items-center px-6 border-b border-gray-100 justify-between">
            <span class="font-medium text-gray-800">
                <i class="fas fa-chevron-right text-xs text-gray-400"></i> Lycée Louis Vincent 
            </span>
            <button type="button" id="collapse-sidebar" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        
        <!-- Dashboard Section -->
        <div id="dashboard-section" class="section-content px-6 py-5">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">DASHBOARD</span>
            
            <nav class="mt-4 space-y-1.5">
                <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="far fa-chart-bar mr-3 text-{{ request()->routeIs('dashboard') ? 'blue-500' : 'gray-400 group-hover:text-gray-500' }}"></i>
                    Dashboard
                </a>
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="far fa-chart-line mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Statistiques
                </a>
            </nav>
        </div>
        
        <!-- Sales Section (renamed to Paramètres Généraux) -->
        <div id="sales-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Paramètres Généraux</span>
            
            <nav class="mt-4 space-y-1.5">
                <div class="submenu-container">
                    <button type="button" class="submenu-toggle group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50" data-submenu="config-generale">
                        <div class="flex items-center">
                            <i class="fas fa-sliders-h mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Configuration Générale
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-200 chevron-icon"></i>
                    </button>
                    <div id="config-generale-submenu" class="submenu ml-6 mt-1 space-y-1 hidden">
                        <a href="#" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-building text-xs mr-2 text-gray-400"></i>
                            Informations Établissement
                        </a>
                        <a href="#" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-palette text-xs mr-2 text-gray-400"></i>
                            Apparence
                        </a>
                        <a href="#" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-envelope text-xs mr-2 text-gray-400"></i>
                            Configuration Email
                        </a>
                    </div>
                </div>
                
                <div class="submenu-container">
                    <button type="button" class="submenu-toggle group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50" data-submenu="users-access">
                        <div class="flex items-center">
                            <i class="fas fa-users-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Utilisateurs & Accès
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-200 chevron-icon"></i>
                    </button>
                    <div id="users-access-submenu" class="submenu ml-6 mt-1 space-y-1 hidden">
                        <a href="#" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-user-plus text-xs mr-2 text-gray-400"></i>
                            Gestion des Utilisateurs
                        </a>
                        <a href="#" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-key text-xs mr-2 text-gray-400"></i>
                            Rôles & Permissions
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- Inventory Section -->
        <div id="inventory-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">CONTENU</span>
            
            <nav class="mt-4 space-y-1.5">
                <div class="submenu-container">
                    <button type="button" class="submenu-toggle group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50" data-submenu="pages">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Pages
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-200 chevron-icon"></i>
                    </button>
                    <div id="pages-submenu" class="submenu ml-6 mt-1 space-y-1 hidden">
                        <a href="#" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-list text-xs mr-2 text-gray-400"></i>
                            Toutes les pages
                        </a>
                        <a href="#" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-plus text-xs mr-2 text-gray-400"></i>
                            Ajouter une page
                        </a>
                    </div>
                </div>
                
                <div class="submenu-container">
                    <button type="button" class="submenu-toggle group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50" data-submenu="media">
                        <div class="flex items-center">
                            <i class="fas fa-images mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Médiathèque
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-200 chevron-icon"></i>
                    </button>
                    <div id="media-submenu" class="submenu ml-6 mt-1 space-y-1 hidden">
                        <a href="#" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-photo-video text-xs mr-2 text-gray-400"></i>
                            Bibliothèque
                        </a>
                        <a href="#" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-upload text-xs mr-2 text-gray-400"></i>
                            Importer
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- Users Section -->
        <div id="users-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">UTILISATEURS</span>
            
            <nav class="mt-4 space-y-1.5">
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="far fa-users mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Utilisateurs
                </a>
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="far fa-user-shield mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Roles
                </a>
            </nav>
        </div>
        
        <!-- Tags Section -->
        <div id="tags-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">TAGS</span>
            
            <nav class="mt-4 space-y-1.5">
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="far fa-tag mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Gérer les tags
                </a>
            </nav>
        </div>
        
        <!-- Settings Section -->
        <div id="settings-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">PARAMÈTRES</span>
            
            <nav class="mt-4 space-y-1.5">
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="far fa-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Paramètres généraux
                </a>
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="far fa-paint-brush mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Apparence
                </a>
            </nav>
        </div>
        
        <!-- User profile section -->
        <div class="mt-auto bottom-0 fixed w-auto">
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <span class="font-medium text-sm">{{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'A' }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user() ? Auth::user()->name : 'Admin' }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs text-gray-500 hover:text-blue-600">
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Main content will be rendered here -->
        {{ $slot ?? '' }}
    </div>
</div>

<!-- JavaScript for sidebar interactions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements
        const collapseBtn = document.getElementById('collapse-sidebar');
        const secondaryNav = document.getElementById('secondary-nav');
        const sidebarToggles = document.querySelectorAll('.sidebar-toggle');
        const sections = document.querySelectorAll('.section-content');
        
        // Handle sidebar collapse/expand
        collapseBtn.addEventListener('click', function() {
            if (secondaryNav.classList.contains('w-0')) {
                secondaryNav.classList.remove('w-0');
                secondaryNav.classList.add('w-60');
                this.innerHTML = '<i class="fas fa-chevron-left"></i>';
            } else {
                secondaryNav.classList.remove('w-60');
                secondaryNav.classList.add('w-0');
                this.innerHTML = '<i class="fas fa-chevron-right"></i>';
            }
        });
        
        // Handle section toggling
        sidebarToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-section');
                
                // If sidebar is collapsed, expand it first
                if (secondaryNav.classList.contains('w-0')) {
                    secondaryNav.classList.remove('w-0');
                    secondaryNav.classList.add('w-60');
                    collapseBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
                }
                
                // Hide all sections
                sections.forEach(section => {
                    section.classList.add('hidden');
                });
                
                // Show the selected section
                document.getElementById(sectionId + '-section').classList.remove('hidden');
                
                // Highlight the active button
                sidebarToggles.forEach(btn => {
                    btn.classList.remove('text-blue-600', 'bg-blue-50');
                    btn.classList.add('text-gray-600');
                });
                
                this.classList.remove('text-gray-600');
                this.classList.add('text-blue-600', 'bg-blue-50');
            });
        });
        
        // Set initial active section
        document.getElementById('dashboard-btn').click();
        
        // Submenu toggle functionality
        const submenuToggles = document.querySelectorAll('.submenu-toggle');
        
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const submenuId = this.getAttribute('data-submenu') + '-submenu';
                const submenu = document.getElementById(submenuId);
                const chevron = this.querySelector('.chevron-icon');
                
                if (submenu.classList.contains('hidden')) {
                    // Open submenu
                    submenu.classList.remove('hidden');
                    chevron.classList.add('rotate-180');
                } else {
                    // Close submenu
                    submenu.classList.add('hidden');
                    chevron.classList.remove('rotate-180');
                }
            });
        });
        
        // Pre-open some submenus if needed
        // For example, if we want "Configuration Générale" open by default:
        // document.querySelector('[data-submenu="config-generale"]').click();
    });
</script>