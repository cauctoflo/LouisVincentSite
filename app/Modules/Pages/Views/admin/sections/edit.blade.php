@extends('layouts.admin')

@section('title', 'Modifier la section: ' . $section->name)

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Modifier: {{ $section->name }}</h1>
            <p class="text-gray-500 mt-1">Modifiez les informations de cette section</p>
        </div>
        
        <a href="{{ route('personnels.pages.sections.show', $section) }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
    </div>

    <form action="{{ route('personnels.pages.sections.update', $section) }}" method="POST" class="max-w-4xl">
        @csrf
        @method('PUT')
        
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
                                Nom de la section <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $section->name) }}"
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
                                   value="{{ old('slug', $section->slug) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Généré automatiquement si vide">
                            <p class="mt-1 text-sm text-gray-500">
                                URL publique : <code class="bg-gray-100 px-1 rounded">/sections/<span id="slug-preview">{{ $section->slug }}</span></code>
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
                                      placeholder="Description de la section...">{{ old('description', $section->description) }}</textarea>
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
                                   value="{{ old('order_index', $section->order_index) }}"
                                   min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <p class="mt-1 text-sm text-gray-500">Plus le nombre est petit, plus la section apparaît en premier</p>
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
                                   value="{{ old('icon', $section->icon) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="ex: fas fa-book">
                            <p class="mt-1 text-sm text-gray-500">Classe CSS FontAwesome (ex: fas fa-book)</p>
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
                                   {{ old('is_active', $section->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                                Section active (visible publiquement)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="show_in_menu" 
                                   name="show_in_menu" 
                                   value="1"
                                   {{ old('show_in_menu', $section->show_in_menu) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="show_in_menu" class="ml-3 text-sm font-medium text-gray-700">
                                Afficher dans le menu principal
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Responsables -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Responsables de la section</h3>
                    
                    <div class="space-y-3">
                        @foreach(\App\Models\User::all() as $user)
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="manager_{{ $user->id }}" 
                                   name="managers[]" 
                                   value="{{ $user->id }}"
                                   {{ in_array($user->id, old('managers', $section->managers ?? [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="manager_{{ $user->id }}" class="ml-3 flex items-center">
                                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-xs font-medium text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    
                    @error('managers')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-600"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 mb-1">
                                    <i class="fas fa-info-circle mr-1"></i> Permissions
                                </h3>
                                <div class="text-sm text-yellow-700 space-y-1">
                                    <p>• Les responsables peuvent créer et modifier les pages</p>
                                    <p>• Les administrateurs ont tous les droits</p>
                                    <p>• Les utilisateurs standards peuvent voir selon les permissions</p>
                                </div>
                            </div>
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
                                   value="{{ old('meta_title', $section->meta_title) }}"
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
                                      placeholder="Description pour les moteurs de recherche...">{{ old('meta_description', $section->meta_description) }}</textarea>
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
                            Sauvegarder les modifications
                        </button>
                        
                        <a href="{{ route('personnels.pages.sections.show', $section) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                    </div>
                </div>
                
                <!-- Informations -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Informations</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Créé le :</strong> {{ $section->created_at->format('d/m/Y à H:i') }}</p>
                        <p><strong>Modifié le :</strong> {{ $section->updated_at->format('d/m/Y à H:i') }}</p>
                        <p><strong>Pages :</strong> {{ $section->pages()->count() }}</p>
                        <p><strong>Dossiers :</strong> {{ $section->folders()->count() }}</p>
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
            slugPreview.textContent = slug;
        }
    });
    
    slugInput.addEventListener('input', function() {
        slugPreview.textContent = this.value;
    });
    
    // Prévisualisation de l'icône
    const iconInput = document.getElementById('icon');
    if (iconInput) {
        iconInput.addEventListener('input', function() {
            // Ici, on pourrait ajouter une prévisualisation de l'icône
            console.log('Icône sélectionnée:', this.value);
        });
    }
});
</script>
@endsection
