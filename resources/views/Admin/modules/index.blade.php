@extends('layouts.admin')

@section('title', 'Gestion des Modules')

@section('content')
<div class="py-8 px-6 max-w-[1600px] mx-auto">
    <!-- Header section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestion des Modules</h1>
            <p class="text-sm text-gray-500">Activez ou désactivez les modules pour personnaliser votre système</p>
        </div>
        
        <div class="flex gap-4 flex-wrap">
            <!-- Filtres par catégorie -->
            <div class="relative">
                <select id="categoryFilter" class="w-48 pl-4 pr-10 py-3 rounded-lg border border-gray-200 shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">Toutes les catégories</option>
                    <option value="Système">Système</option>
                    <option value="Gestion">Gestion</option>
                    <option value="Contenu">Contenu</option>
                    <option value="Médias">Médias</option>
                    <option value="Organisation">Organisation</option>
                    <option value="Administration">Administration</option>
                </select>
            </div>
            
            <!-- Filtre par statut -->
            <div class="relative">
                <select id="statusFilter" class="w-40 pl-4 pr-10 py-3 rounded-lg border border-gray-200 shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">Tous les statuts</option>
                    <option value="active">Actifs</option>
                    <option value="inactive">Inactifs</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div id="success-message" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error-message" class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total des modules</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $modules->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-puzzle-piece text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Modules actifs</p>
                    <p class="text-2xl font-bold text-green-600">{{ $modules->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Modules inactifs</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $modules->where('status', 'inactive')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-pause-circle text-orange-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Modules système</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $modules->where('category', 'Système')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cogs text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Module Grid -->
    <div id="modules-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modules as $module)
            <div class="module-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md hover:border-gray-200" 
                 data-category="{{ $module['category'] }}" 
                 data-status="{{ $module['status'] }}">
                
                <!-- Header avec dégradé -->
                <div class="h-2 {{ $module['status'] === 'active' ? 'bg-gradient-to-r from-green-400 to-green-600' : 'bg-gradient-to-r from-gray-300 to-gray-400' }}"></div>
                
                <div class="p-6">
                    <!-- En-tête -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-3 {{ $module['status'] === 'active' ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-500' }}">
                                <i class="{{ $module['icon'] }} text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $module['displayName'] }}</h3>
                                <p class="text-xs text-gray-500">{{ $module['category'] }} • v{{ $module['version'] }}</p>
                            </div>
                        </div>
                        
                        <!-- Toggle Switch -->
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only module-toggle" 
                                       data-module="{{ $module['name'] }}"
                                       {{ $module['status'] === 'active' ? 'checked' : '' }}
                                       {{ in_array($module['name'], ['Core', 'Personnels', 'Settings']) ? 'disabled' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $module['description'] }}</p>
                    
                    <!-- Permissions -->
                    @if(!empty($module['permissions']))
                        <div class="mb-4">
                            <p class="text-xs font-medium text-gray-500 mb-2">Permissions requises:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($module['permissions'], 0, 3) as $permission)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                        {{ $permission }}
                                    </span>
                                @endforeach
                                @if(count($module['permissions']) > 3)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                        +{{ count($module['permissions']) - 3 }} autres
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Routes -->
                    @if(!empty($module['routes']))
                        <div class="mb-4">
                            <p class="text-xs font-medium text-gray-500 mb-2">Fonctionnalités:</p>
                            <div class="text-xs text-gray-600">
                                {{ count($module['routes']) }} page{{ count($module['routes']) > 1 ? 's' : '' }} disponible{{ count($module['routes']) > 1 ? 's' : '' }}
                            </div>
                        </div>
                    @endif
                    
                    <!-- Statut -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($module['status'] === 'active')
                                <span class="status-badge inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                    <span class="status-text">Actif</span>
                                </span>
                            @else
                                <span class="status-badge inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                    <span class="status-text">Inactif</span>
                                </span>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center space-x-1">
                            @if(in_array($module['name'], ['Core', 'Personnels', 'Settings']))
                                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded">
                                    <i class="fas fa-lock mr-1"></i>Système
                                </span>
                            @endif
                            
                            <button class="module-info-btn text-gray-400 hover:text-blue-600 p-1 rounded transition-colors" 
                                    data-module="{{ $module['name'] }}"
                                    title="Informations du module">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-50 flex items-center justify-center">
                    <i class="fas fa-puzzle-piece text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun module disponible</h3>
                <p class="text-gray-500">Les modules apparaîtront ici une fois enregistrés dans le système.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal pour informations du module -->
<div id="module-info-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modal-title">Informations du module</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modal-content">
                <!-- Contenu dynamique -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtres
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const moduleCards = document.querySelectorAll('.module-card');
    
    function applyFilters() {
        const selectedCategory = categoryFilter.value;
        const selectedStatus = statusFilter.value;
        
        moduleCards.forEach(card => {
            const category = card.dataset.category;
            const status = card.dataset.status;
            
            const categoryMatch = !selectedCategory || category === selectedCategory;
            const statusMatch = !selectedStatus || status === selectedStatus;
            
            if (categoryMatch && statusMatch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    categoryFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    
    // Toggle des modules
    const moduleToggles = document.querySelectorAll('.module-toggle');
    
    moduleToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const moduleName = this.dataset.module;
            const isChecked = this.checked;
            
            // Désactiver le toggle pendant la requête
            this.disabled = true;
            
            fetch(`/personnels/modules/${moduleName}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour l'UI sans reload
                    const card = this.closest('.module-card');
                    const statusBadge = card.querySelector('.status-badge');
                    const statusText = card.querySelector('.status-text');
                    const statusDot = statusBadge.querySelector('span:first-child');
                    
                    if (data.status === 'active') {
                        statusBadge.className = 'status-badge inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                        statusDot.className = 'w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5';
                        statusText.textContent = 'Actif';
                        card.dataset.status = 'active';
                    } else {
                        statusBadge.className = 'status-badge inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600';
                        statusDot.className = 'w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5';
                        statusText.textContent = 'Inactif';
                        card.dataset.status = 'inactive';
                    }
                    
                    // Afficher un message de succès temporaire
                    showSuccessMessage(data.message);
                } else {
                    // Revenir à l'état précédent en cas d'erreur
                    this.checked = !isChecked;
                    showErrorMessage(data.message || 'Erreur lors du changement de statut');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                this.checked = !isChecked;
                showErrorMessage('Erreur lors du changement de statut');
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
    
    // Modal informations
    const modal = document.getElementById('module-info-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content');
    const closeModal = document.getElementById('close-modal');
    const infoButtons = document.querySelectorAll('.module-info-btn');
    
    infoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const moduleName = this.dataset.module;
            
            fetch(`/personnels/modules/${moduleName}/info`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const module = data.module;
                    modalTitle.textContent = `${module.displayName}`;
                    
                    modalContent.innerHTML = `
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium text-gray-900">Description</h4>
                                <p class="text-sm text-gray-600">${module.description}</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Version</h4>
                                <p class="text-sm text-gray-600">${module.version}</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Catégorie</h4>
                                <p class="text-sm text-gray-600">${module.category}</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Statut</h4>
                                <p class="text-sm ${module.isActive ? 'text-green-600' : 'text-gray-600'}">${module.isActive ? 'Actif' : 'Inactif'}</p>
                            </div>
                            ${module.permissions.length > 0 ? `
                            <div>
                                <h4 class="font-medium text-gray-900">Permissions requises</h4>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    ${module.permissions.map(perm => `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">${perm}</span>`).join('')}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    `;
                    
                    modal.classList.remove('hidden');
                } else {
                    alert('Erreur lors de la récupération des informations du module');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la récupération des informations du module');
            });
        });
    });
    
    closeModal.addEventListener('click', function() {
        modal.classList.add('hidden');
    });
    
    // Fermer le modal en cliquant en dehors
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
    
    // Auto-hide messages
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 5000);
    }
    
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 8000);
    }
    
    // Fonctions utilitaires pour afficher les messages
    function showSuccessMessage(message) {
        const container = document.querySelector('.py-8.px-6 > div');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center';
        messageDiv.innerHTML = `
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            ${message}
        `;
        container.insertBefore(messageDiv, container.firstChild.nextSibling);
        setTimeout(() => messageDiv.remove(), 3000);
    }
    
    function showErrorMessage(message) {
        const container = document.querySelector('.py-8.px-6 > div');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center';
        messageDiv.innerHTML = `
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            ${message}
        `;
        container.insertBefore(messageDiv, container.firstChild.nextSibling);
        setTimeout(() => messageDiv.remove(), 5000);
    }
});
</script>
@endsection