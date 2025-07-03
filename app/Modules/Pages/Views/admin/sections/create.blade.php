@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Créer une nouvelle section</h1>
            <p class="text-gray-500 mt-1">Organisez votre contenu en créant une nouvelle section thématique</p>
        </div>
        <a href="{{ route('personnels.pages.sections.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('personnels.pages.sections.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Formulaire principal -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom de la section <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                                   placeholder="Ex: Actualités, Documents, Ressources..."
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Le slug sera généré automatiquement à partir du nom</p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                                      placeholder="Description de la section (optionnel)">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Paramètres d'affichage -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Paramètres d'affichage</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Couleur -->
                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Couleur du thème
                                    </label>
                                    <select name="color" 
                                            id="color" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="blue" {{ old('color', 'blue') === 'blue' ? 'selected' : '' }}>Bleu</option>
                                        <option value="green" {{ old('color') === 'green' ? 'selected' : '' }}>Vert</option>
                                        <option value="purple" {{ old('color') === 'purple' ? 'selected' : '' }}>Violet</option>
                                        <option value="red" {{ old('color') === 'red' ? 'selected' : '' }}>Rouge</option>
                                        <option value="yellow" {{ old('color') === 'yellow' ? 'selected' : '' }}>Jaune</option>
                                        <option value="indigo" {{ old('color') === 'indigo' ? 'selected' : '' }}>Indigo</option>
                                        <option value="pink" {{ old('color') === 'pink' ? 'selected' : '' }}>Rose</option>
                                        <option value="emerald" {{ old('color') === 'emerald' ? 'selected' : '' }}>Émeraude</option>
                                        <option value="teal" {{ old('color') === 'teal' ? 'selected' : '' }}>Sarcelle</option>
                                        <option value="orange" {{ old('color') === 'orange' ? 'selected' : '' }}>Orange</option>
                                    </select>
                                </div>

                                <!-- Ordre d'affichage -->
                                <div>
                                    <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ordre d'affichage
                                    </label>
                                    <input type="number" 
                                           name="display_order" 
                                           id="display_order" 
                                           value="{{ old('display_order', 0) }}" 
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="0">
                                    <p class="mt-1 text-sm text-gray-500">Plus petit = affiché en premier</p>
                                </div>
                            </div>

                            <!-- Icône -->
                            <div class="mt-4">
                                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Icône Font Awesome <span class="text-gray-500">(optionnel)</span>
                                </label>
                                <input type="text" 
                                       name="icon" 
                                       id="icon" 
                                       value="{{ old('icon') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="Ex: fas fa-graduation-cap, fas fa-book, fas fa-users">
                                <p class="mt-1 text-sm text-gray-500">
                                    Exemples : fas fa-graduation-cap, fas fa-book, fas fa-users, fas fa-calendar
                                    <a href="https://fontawesome.com/icons" target="_blank" class="text-indigo-600 hover:text-indigo-500">Voir tous les icônes</a>
                                </p>
                                <div id="icon-preview" class="mt-2 hidden">
                                    <span class="text-sm text-gray-600">Aperçu : </span>
                                    <i id="icon-display" class="text-2xl text-gray-600"></i>
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="mt-4">
                                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
                                    URL de l'image <span class="text-gray-500">(optionnel)</span>
                                </label>
                                <input type="url" 
                                       name="image_url" 
                                       id="image_url" 
                                       value="{{ old('image_url') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="http://localhost:8000/images/token/exemple">
                                <p class="mt-1 text-sm text-gray-500">
                                    L'image sera affichée en priorité sur l'icône. Format recommandé : 64x64px minimum.
                                </p>
                            </div>

                            <!-- Note sur la priorité -->
                            <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                                    <div class="text-sm text-blue-700">
                                        <strong>Ordre de priorité :</strong> Si une image est définie, elle sera affichée. Sinon, l'icône sera utilisée. Si aucun des deux n'est défini, une icône par défaut sera affichée.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panneau latéral -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Statut -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Statut</h3>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" 
                                           name="is_active" 
                                           id="active" 
                                           value="1" 
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           {{ old('is_active', '1') === '1' ? 'checked' : '' }}>
                                    <label for="active" class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">Active</span>
                                        <div class="text-gray-500">La section sera visible publiquement</div>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" 
                                           name="is_active" 
                                           id="inactive" 
                                           value="0" 
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           {{ old('is_active') === '0' ? 'checked' : '' }}>
                                    <label for="inactive" class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">Inactive</span>
                                        <div class="text-gray-500">La section ne sera pas visible publiquement</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu URL -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-blue-900 mb-2">Aperçu de l'URL</h3>
                            <div class="text-sm text-blue-700 font-mono bg-white px-3 py-2 rounded border">
                                {{ url('/pages/') }}/<span id="slug-preview">nom-de-la-section</span>
                            </div>
                            <p class="mt-2 text-xs text-blue-600">L'URL sera générée automatiquement</p>
                        </div>

                        <!-- Permissions info -->
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-yellow-900 mb-2">
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

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                    <div class="text-sm text-gray-500">
                        Les champs marqués d'un <span class="text-red-500">*</span> sont obligatoires
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('personnels.pages.sections.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all">
                            <i class="fas fa-save mr-2"></i> Créer la section
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugPreview = document.getElementById('slug-preview');
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const iconDisplay = document.getElementById('icon-display');
    
    nameInput.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, '')
            .replace(/\s+/g, '-')
            .trim();
        
        slugPreview.textContent = slug || 'nom-de-la-section';
    });

    // Prévisualisation de l'icône
    if (iconInput) {
        iconInput.addEventListener('input', function() {
            const iconClass = this.value.trim();
            if (iconClass) {
                iconDisplay.className = iconClass + ' text-2xl text-gray-600';
                iconPreview.classList.remove('hidden');
            } else {
                iconPreview.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection
