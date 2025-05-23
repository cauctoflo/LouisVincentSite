@extends('layouts.admin')

@section('content')
@if(auth()->check() && auth()->user()->hasPermission('roles.edit'))
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Modifier le rôle: {{ $role->name }}</h1>
            <p class="text-gray-500 mt-1">Ajustez les permissions et les détails de ce rôle</p>
        </div>
        <a href="{{ route('personnels.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('personnels.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du rôle <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-300 @enderror" 
                               value="{{ old('name', $role->name) }}" required placeholder="Ex: Administrateur, Éditeur, Utilisateur">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Le nom doit être unique et identifiable facilement.</p>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('description') border-red-300 @enderror" 
                                  rows="3" placeholder="Décrivez brièvement les responsabilités de ce rôle">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-base font-medium text-gray-700">Permissions</label>
                        <div class="text-sm">
                            <button type="button" id="selectAll" class="text-indigo-600 hover:text-indigo-800 mr-3">Tout sélectionner</button>
                            <button type="button" id="deselectAll" class="text-gray-600 hover:text-gray-800">Tout désélectionner</button>
                        </div>
                    </div>
                    
                    @php
                        $permissionController = app()->make('App\Modules\Personnels\Controllers\PermissionController');
                        $availablePermissions = $permissionController->getAvailablePermissions();
                        
                        // Group permissions by category (assuming permissions are named like "category.action")
                        $groupedPermissions = [];
                        foreach($availablePermissions as $key => $label) {
                            $parts = explode('.', $key);
                            $category = count($parts) > 1 ? ucfirst($parts[0]) : 'Général';
                            
                            if (!isset($groupedPermissions[$category])) {
                                $groupedPermissions[$category] = [];
                            }
                            
                            $groupedPermissions[$category][$key] = $label;
                        }
                    @endphp
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 gap-y-6" id="permissionsAccordion">
                            @foreach($groupedPermissions as $category => $permissions)
                                <div class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors" 
                                         onclick="toggleCategory('{{ $category }}')">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $category }}</h3>
                                            <span class="flex items-center">
                                                <span class="text-xs text-gray-500 mr-2 permission-counter" id="counter-{{ $category }}">0 sélectionnée(s)</span>
                                                <i class="fas fa-chevron-down text-gray-500 transition-transform" id="icon-{{ $category }}"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 hidden" id="category-{{ $category }}">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($permissions as $key => $label)
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input type="checkbox" name="permissions[]" class="permission-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                                               id="perm_{{ $key }}" value="{{ $key }}" data-category="{{ $category }}"
                                                               {{ in_array($key, old('permissions', $role->permissions ?? [])) ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="perm_{{ $key }}" class="font-medium text-gray-700">{{ $label }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    @error('permissions')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-5 border-t border-gray-200">
                    <button type="button" onclick="window.location.href='{{ route('personnels.roles.index') }}'" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                        Annuler
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@elseif(auth()->check() && auth()->user()->hasPermission('roles.view_only'))
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Détails du rôle: {{ $role->name }}</h1>
            <p class="text-gray-500 mt-1">Vue en lecture seule des permissions et détails de ce rôle</p>
        </div>
        <a href="{{ route('personnels.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Nom du rôle</h3>
                    <p class="text-lg font-medium text-gray-900">{{ $role->name }}</p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Description</h3>
                    <p class="text-base text-gray-800">
                        @if($role->description)
                            {{ $role->description }}
                        @else
                            <span class="text-gray-400 italic">Aucune description</span>
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-5">
                <h3 class="text-base font-medium text-gray-700 mb-3">Permissions accordées</h3>
                
                @if (is_array($role->permissions) && count($role->permissions) > 0)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        @php
                            $groupedPermissions = [];
                            foreach($role->permissions as $permission) {
                                $parts = explode('.', $permission);
                                $category = count($parts) > 1 ? ucfirst($parts[0]) : 'Général';
                                
                                if (!isset($groupedPermissions[$category])) {
                                    $groupedPermissions[$category] = [];
                                }
                                
                                if (isset($availablePermissions[$permission])) {
                                    $groupedPermissions[$category][$permission] = $availablePermissions[$permission];
                                } else {
                                    $groupedPermissions[$category][$permission] = $permission;
                                }
                            }
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($groupedPermissions as $category => $permissions)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $category }}</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($permissions as $key => $label)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $label }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                        <p class="text-gray-500">Ce rôle n'a aucune permission accordée.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Vous n'avez pas la permission de modifier ce rôle.
                    <a href="{{ route('personnels.roles.index') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                        Retour à la liste des rôles
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endif

@section('scripts')
<script>
    // Toggle permission categories
    function toggleCategory(category) {
        const content = document.getElementById(`category-${category}`);
        const icon = document.getElementById(`icon-${category}`);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('transform', 'rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('transform', 'rotate-180');
        }
    }
    
    // Count selected permissions per category
    function updatePermissionCounters() {
        const categories = {};
        
        // Initialize counters
        document.querySelectorAll('.permission-counter').forEach(counter => {
            const category = counter.id.replace('counter-', '');
            categories[category] = {
                counter: counter,
                total: 0,
                selected: 0
            };
        });
        
        // Count checkboxes
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            const category = checkbox.dataset.category;
            if (categories[category]) {
                categories[category].total++;
                if (checkbox.checked) {
                    categories[category].selected++;
                }
            }
        });
        
        // Update counters
        for (const category in categories) {
            const { counter, selected, total } = categories[category];
            counter.textContent = `${selected} / ${total} sélectionnée(s)`;
        }
    }
    
    // Initialize UI
    document.addEventListener('DOMContentLoaded', function() {
        @if(auth()->check() && auth()->user()->hasPermission('roles.edit'))
        // Show the first category by default
        const firstCategory = document.querySelector('#permissionsAccordion > div:first-child');
        if (firstCategory) {
            const categoryName = firstCategory.querySelector('h3').textContent.trim();
            toggleCategory(categoryName);
        }
        
        // Update permission counters
        updatePermissionCounters();
        
        // Add change listeners to checkboxes
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updatePermissionCounters);
        });
        
        // Select/deselect all permissions
        document.getElementById('selectAll').addEventListener('click', function() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updatePermissionCounters();
        });
        
        document.getElementById('deselectAll').addEventListener('click', function() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updatePermissionCounters();
        });
        @endif
    });
</script>
@endsection

@endsection 