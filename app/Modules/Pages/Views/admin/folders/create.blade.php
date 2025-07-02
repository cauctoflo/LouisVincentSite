@extends('layouts.admin')

@section('title', 'Créer un nouveau dossier')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Créer un nouveau dossier</h1>
            <p class="text-gray-500 mt-1">Organisez vos pages en créant des dossiers thématiques</p>
        </div>
        
        <a href="{{ route('personnels.pages.folders.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour à la liste
        </a>
    </div>

    <form action="{{ route('personnels.pages.folders.store') }}" method="POST" class="max-w-4xl">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations de base -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Informations générales</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Nom -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom du dossier <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug (URL)
                            </label>
                            <input type="text" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Généré automatiquement si vide">
                            <p class="mt-1 text-sm text-gray-500">
                                URL publique : <code class="bg-gray-100 px-1 rounded">/sections/{section}/<span id="slug-preview">nouveau-dossier</span></code>
                            </p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="Description du dossier...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Paramètres d'affichage -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Paramètres d'affichage</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ordre d'affichage -->
                        <div>
                            <label for="order_index" class="block text-sm font-medium text-gray-700 mb-2">
                                Ordre d'affichage
                            </label>
                            <input type="number" 
                                   id="order_index" 
                                   name="order_index" 
                                   value="{{ old('order_index', 0) }}"
                                   min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <p class="mt-1 text-sm text-gray-500">Plus le nombre est petit, plus le dossier apparaît en premier</p>
                            @error('order_index')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Icône -->
                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                                Icône FontAwesome (optionnel)
                            </label>
                            <input type="text" 
                                   id="icon" 
                                   name="icon" 
                                   value="{{ old('icon') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="ex: fas fa-folder-open">
                            <p class="mt-1 text-sm text-gray-500">Classe CSS FontAwesome (ex: fas fa-folder-open)</p>
                            @error('icon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Options d'affichage -->
                    <div class="mt-6 space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                                Dossier actif (visible publiquement)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="show_in_menu" 
                                   name="show_in_menu" 
                                   value="1"
                                   {{ old('show_in_menu', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="show_in_menu" class="ml-3 text-sm font-medium text-gray-700">
                                Afficher dans le menu de navigation
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Organisation -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Organisation</h3>
                    
                    <div class="space-y-4">
                        <!-- Section -->
                        <div>
                            <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Section <span class="text-red-500">*</span>
                            </label>
                            <select id="section_id" 
                                    name="section_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required>
                                <option value="">Choisir une section</option>
                                @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ old('section_id', request('section_id')) == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Dossier parent -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Dossier parent (optionnel)
                            </label>
                            <select id="parent_id" 
                                    name="parent_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Aucun (dossier racine)</option>
                                <!-- Les options seront chargées via JavaScript -->
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Permet de créer des sous-dossiers (un niveau maximum)</p>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- SEO -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre SEO
                            </label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   value="{{ old('meta_title') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   maxlength="255">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description SEO
                            </label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      rows="3"
                                      maxlength="300"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="Description pour les moteurs de recherche...">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-all">
                            <i class="fas fa-save mr-2"></i>
                            Créer le dossier
                        </button>
                        
                        <a href="{{ route('personnels.pages.folders.index') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                    </div>
                </div>
                
                <!-- Information -->
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">À propos des dossiers</h3>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p>• Les dossiers permettent d'organiser vos pages</p>
                                <p>• Un seul niveau de hiérarchie est supporté</p>
                                <p>• Les dossiers peuvent être réorganisés après création</p>
                                <p>• L'URL publique sera : /sections/{section}/{dossier}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Génération automatique du slug
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const slugPreview = document.getElementById('slug-preview');
    
    nameInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.value === '') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            
            slugInput.value = slug;
            slugPreview.textContent = slug || 'nouveau-dossier';
        }
    });
    
    slugInput.addEventListener('input', function() {
        slugPreview.textContent = this.value || 'nouveau-dossier';
    });
    
    // Gestion des dossiers parents selon la section
    const sectionSelect = document.getElementById('section_id');
    const parentSelect = document.getElementById('parent_id');
    
    sectionSelect.addEventListener('change', function() {
        const sectionId = this.value;
        
        // Vider les options du dossier parent
        parentSelect.innerHTML = '<option value="">Aucun (dossier racine)</option>';
        
        if (sectionId) {
            // Charger les dossiers de la section sélectionnée
            fetch(`{{ route('personnels.pages.api.folders') }}?section_id=${sectionId}`)
                .then(response => response.json())
                .then(folders => {
                    folders.forEach(folder => {
                        // Ne montrer que les dossiers de niveau racine
                        if (!folder.parent_id) {
                            const option = document.createElement('option');
                            option.value = folder.id;
                            option.textContent = folder.name;
                            parentSelect.appendChild(option);
                        }
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des dossiers:', error);
                });
        }
    });
    
    // Déclencher le changement si une section est déjà sélectionnée
    if (sectionSelect.value) {
        sectionSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
