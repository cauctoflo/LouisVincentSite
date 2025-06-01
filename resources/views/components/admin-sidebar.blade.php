<!-- Main sidebar layout -->
<div class="flex h-full overflow-hidden bg-gray-50">
    <!-- Narrow icon sidebar -->
    <div class="w-16 bg-white border-r border-gray-100 flex-shrink-0 flex flex-col">
        <div class="h-16 flex items-center justify-center border-b border-gray-100">
            <a href="{{ route('personnels.index') }}" class="text-blue-600">
                <i class="fas fa-school text-xl"></i>
            </a>
        </div>
        
        <nav class="flex-1 flex flex-col mt-6">
            <button type="button" id="dashboard-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }} sidebar-toggle" data-section="dashboard">
                <i class="fa-solid fa-table-columns"></i>
            </button>
            <button type="button" id="administration-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="administration">
                <i class="fas fa-user-tie"></i>
            </button>
            <button type="button" id="personnels-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="personnels">
                <i class="fas fa-users"></i>
            </button>
            <button type="button" id="logs-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="logs">
                <i class="fas fa-history"></i>
            </button>
            <button type="button" id="internat-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="internat">
                <i class="fas fa-home"></i>
            </button>
            <button type="button" id="content-btn" class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" data-section="content">
                <i class="fas fa-newspaper"></i>
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
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">TABLEAU DE BORD</span>
            
            <nav class="mt-4 space-y-1.5">
                <a href="{{ route('personnels.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="far fa-chart-bar mr-3 text-{{ request()->routeIs('personnels.index') ? 'blue-500' : 'gray-400 group-hover:text-gray-500' }}"></i>
                    Vue d'ensemble
                </a>
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-calendar-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Calendrier
                </a>
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-bell mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Notifications
                </a>
            </nav>
        </div>
        
        <!-- Administration Section -->
        <div id="administration-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">ADMINISTRATION</span>
            
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
                    <button type="button" class="submenu-toggle group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50" data-submenu="modules-admin">
                        <div class="flex items-center">
                            <i class="fas fa-puzzle-piece mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Modules
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-200 chevron-icon"></i>
                    </button>
                    <div id="modules-admin-submenu" class="submenu ml-6 mt-1 space-y-1 hidden">
                        <a href="{{ route('personnels.modules.index') }}" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-list text-xs mr-2 text-gray-400"></i>
                            Liste des modules
                        </a>

                    </div>
                </div>
                
                <a href="{{ route('personnels.settings.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Paramètres
                </a>
            </nav>
        </div>

        <!-- Personnels Section -->
        <div id="personnels-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">PERSONNELS</span>
            
            <nav class="mt-4 space-y-1.5">
                <a href="{{ route('personnels.personnels.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('personnels.personnels.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-users mr-3 text-{{ request()->routeIs('personnels.personnels.index') ? 'blue-500' : 'gray-400 group-hover:text-gray-500' }}"></i>
                    Liste des utilisateurs
                </a>
                <a href="{{ route('personnels.personnels.create') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('personnels.personnels.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-user-plus mr-3 text-{{ request()->routeIs('personnels.personnels.create') ? 'blue-500' : 'gray-400 group-hover:text-gray-500' }}"></i>
                    Ajouter un utilisateur
                </a>
                
                <div class="submenu-container">
                    <button type="button" class="submenu-toggle group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50" data-submenu="roles-permissions">
                        <div class="flex items-center">
                            <i class="fas fa-user-shield mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Rôles et permissions
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-200 chevron-icon"></i>
                    </button>
                    <div id="roles-permissions-submenu" class="submenu ml-6 mt-1 space-y-1 hidden">
                        <a href="{{ route('personnels.roles.index') }}" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800 {{ request()->routeIs('personnels.roles.index') ? 'text-blue-600' : '' }}">
                            <i class="fas fa-id-badge text-xs mr-2 text-gray-400"></i>
                            Gestion des rôles
                        </a>
                        <a href="{{ route('personnels.permissions.index') }}" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800 {{ request()->routeIs('personnels.permissions.index') ? 'text-blue-600' : '' }}">
                            <i class="fas fa-key text-xs mr-2 text-gray-400"></i>
                            Gestion des permissions
                        </a>

                    </div>
                </div>
                

                </a>
            </nav>
        </div>

        <!-- Internat Section -->
        <div id="internat-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">INTERNAT</span>
            
            <nav class="mt-4 space-y-1.5">
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-home mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Gestion de l'internat
                </a>
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-bed mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Chambres
                </a>
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-user-graduate mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Élèves internes
                </a>
            </nav>
        </div>
        
        <!-- Content Section -->
        <div id="content-section" class="section-content px-6 py-5 hidden">
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
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-newspaper mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Actualités
                </a>
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-calendar-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Événements
                </a>
            </nav>
        </div>
        
        <!-- Logs Section -->
        <div id="logs-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">JOURNAUX D'ACTIVITÉ</span>
            
            <nav class="mt-4 space-y-1.5">
                <a href="{{ route('personnels.Log.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('personnels.Log.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-list mr-3 text-{{ request()->routeIs('personnels.Log.index') ? 'blue-500' : 'gray-400 group-hover:text-gray-500' }}"></i>
                    Tous les logs
                </a>

                <div class="submenu-container">
                    <button type="button" class="submenu-toggle group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50" data-submenu="logs-categories">
                        <div class="flex items-center">
                            <i class="fas fa-filter mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Catégories
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-200 chevron-icon"></i>
                    </button>
                    <div id="logs-categories-submenu" class="submenu ml-6 mt-1 space-y-1 hidden">
                        <a href="{{ route('personnels.Log.index', ['action' => 'login']) }}" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-sign-in-alt text-xs mr-2 text-gray-400"></i>
                            Connexions
                        </a>
                        <a href="{{ route('personnels.Log.index', ['action' => 'role_change']) }}" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-user-tag text-xs mr-2 text-gray-400"></i>
                            Changements de rôles
                        </a>
                        <a href="{{ route('personnels.Log.index', ['action' => 'create']) }}" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-plus-circle text-xs mr-2 text-gray-400"></i>
                            Créations
                        </a>
                        <a href="{{ route('personnels.Log.index', ['action' => 'update']) }}" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-edit text-xs mr-2 text-gray-400"></i>
                            Modifications
                        </a>
                        <a href="{{ route('personnels.Log.index', ['action' => 'delete']) }}" class="group flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                            <i class="fas fa-trash text-xs mr-2 text-gray-400"></i>
                            Suppressions
                        </a>
                    </div>
                </div>

                @if(auth()->check() && auth()->user()->hasPermission('personnels.Log.clear'))
                <button type="button" onclick="confirmClearLogs()" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50">
                    <i class="fas fa-trash-alt mr-3 text-red-500"></i>
                    Vider les logs
                </button>
                @endif
            </nav>
        </div>
        
        <!-- Settings Section -->
        <div id="settings-section" class="section-content px-6 py-5 hidden">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">PARAMÈTRES</span>
            
            <nav class="mt-4 space-y-1.5">
                <a href="{{ route('personnels.settings.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Paramètres généraux
                </a>
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-paint-brush mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Apparence
                </a>
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-shield-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Sécurité
                </a>
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-history mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Logs système
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
        
        {{ $slot ?? '' }}
    </div>
</div>


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
    });

    function confirmClearLogs() {
        if (confirm('Êtes-vous sûr de vouloir vider tous les logs ? Cette action est irréversible.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('personnels.Log.clear') }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>