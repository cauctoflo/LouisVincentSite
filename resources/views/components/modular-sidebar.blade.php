<!-- Main sidebar layout -->
<div class="flex h-full min-h-screen overflow-hidden bg-gray-50">
    <!-- Narrow icon sidebar -->
    <div class="w-16 bg-white border-r border-gray-100 flex-shrink-0 flex flex-col">
        <div class="h-16 flex items-center justify-center border-b border-gray-100">
            <a href="{{ route('personnels.index') }}" class="text-blue-600">
                <i class="fas fa-school text-xl"></i>
            </a>
        </div>
        
        <nav class="flex-1 flex flex-col mt-6">
            @foreach($moduleRegistry as $moduleConfig)
                @if($moduleConfig['isActive'])
                    @permission($moduleConfig['permissions'][0] ?? null)
                        <button type="button" 
                                id="{{ strtolower($moduleConfig['name']) }}-btn" 
                                class="my-1 mx-auto w-10 h-10 rounded-xl flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-600 sidebar-toggle" 
                                data-section="{{ strtolower($moduleConfig['name']) }}"
                                title="{{ $moduleConfig['displayName'] }}">
                            <i class="{{ $moduleConfig['icon'] }}"></i>
                        </button>
                    @endpermission
                @endif
            @endforeach
        </nav>
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
        
        @foreach($moduleRegistry as $moduleConfig)
            @if($moduleConfig['isActive'])
                @permission($moduleConfig['permissions'][0] ?? null)
                    <!-- {{ $moduleConfig['displayName'] }} Section -->
                    <div id="{{ strtolower($moduleConfig['name']) }}-section" class="section-content px-6 py-5 {{ $loop->first ? '' : 'hidden' }}">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ strtoupper($moduleConfig['displayName']) }}</span>
                        
                        <nav class="mt-4 space-y-1.5">
                            @foreach($moduleConfig['routes'] as $route)
                                @if($route['permission'])
                                    @permission($route['permission'])
                                        @include('components.sidebar-item', $route)
                                    @endpermission
                                @else
                                    @include('components.sidebar-item', $route)
                                @endif
                            @endforeach
                        </nav>
                    </div>
                @endpermission
            @endif
        @endforeach
        
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
            const targetSection = document.getElementById(sectionId + '-section');
            if (targetSection) {
                targetSection.classList.remove('hidden');
            }
            
            // Highlight the active button
            sidebarToggles.forEach(btn => {
                btn.classList.remove('text-blue-600', 'bg-blue-50');
                btn.classList.add('text-gray-600');
            });
            
            this.classList.remove('text-gray-600');
            this.classList.add('text-blue-600', 'bg-blue-50');
        });
    });
    
    // Set initial active section (first visible module)
    const firstToggle = document.querySelector('.sidebar-toggle');
    if (firstToggle) {
        firstToggle.click();
    }
});
</script>