@extends('layouts.admin')

@section('head')
<!-- Editor.js CSS simplifié -->
<style>
#editorjs {
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  background: #fff;
  min-height: 300px;
}

.autosave-indicator.saved {
    opacity: 0.7;
}
</style>
@endsection

@section('scripts')
<!-- Scripts pour l'éditeur -->
@vite(['resources/js/editor-form.js', 'resources/js/tags-handler.js'])

<script>
// Section-Folder handler script inline (until added to build process)
document.addEventListener('DOMContentLoaded', () => {
    // Références aux éléments DOM
    const sectionSelect = document.getElementById('section_id');
    const folderSelect = document.getElementById('folder_id');
    
    // Stocker la valeur actuelle du dossier sélectionné pour la restaurer après le chargement
    let currentFolderId = folderSelect.getAttribute('data-current') || '';
    
    // Fonction pour charger les dossiers d'une section
    const loadFolders = async (sectionId, selectedFolderId = null) => {
        // Vider le sélecteur de dossiers sauf l'option par défaut
        folderSelect.innerHTML = '<option value="">Racine de la section</option>';
        
        // Si aucune section n'est sélectionnée, on s'arrête là
        if (!sectionId) return;
        
        try {
            // Charger les dossiers via une requête AJAX
            const response = await fetch(`/api/sections/${sectionId}/folders`);
            
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            
            const folders = await response.json();
            
            // Ajouter les options de dossiers
            folders.forEach(folder => {
                const option = document.createElement('option');
                option.value = folder.id;
                option.textContent = folder.name;
                
                // Sélectionner le dossier actuel si défini
                if (selectedFolderId && folder.id == selectedFolderId) {
                    option.selected = true;
                }
                
                folderSelect.appendChild(option);
            });
        } catch (error) {
            console.error("Erreur lors du chargement des dossiers:", error);
            
            // Ajouter un message d'erreur
            const errorOption = document.createElement('option');
            errorOption.disabled = true;
            errorOption.textContent = "Erreur de chargement des dossiers";
            folderSelect.appendChild(errorOption);
        }
    };
    
    // Écouter les changements de section
    if (sectionSelect) {
        sectionSelect.addEventListener('change', (event) => {
            loadFolders(event.target.value);
        });
        
        // Charger les dossiers pour la section sélectionnée au chargement
        if (sectionSelect.value) {
            loadFolders(sectionSelect.value, currentFolderId);
        }
    }
});
</script>
@endsection

@section('content')

<script>
    // Initialisation immédiate des données pour l'éditeur - AVANT le chargement de app.js
    (function() {
        // Utilisons une approche plus robuste avec vérification et nettoyage des données
        var contentString = @json($page->content);
        try {
            // Tenter de parser la chaîne JSON si ce n'est pas déjà fait
            var parsedData = typeof contentString === 'string' ? JSON.parse(contentString) : contentString;
            console.log("Raw editor data:", parsedData);
            
            // Structure par défaut si aucun contenu n'est présent
            if (!parsedData || !parsedData.blocks || parsedData.blocks.length === 0) {
                parsedData = {
                    time: Date.now(),
                    version: "2.28.0",
                    blocks: []
                };
            }
            
            // Vérifier que les blocs sont bien formatés
            if (parsedData && parsedData.blocks) {
                // Transformer les blocs pour s'assurer qu'ils sont tous valides
                parsedData.blocks = parsedData.blocks.map(function(block) {
                    console.log("Traitement du bloc:", block.type);
                    
                    // Vérifier et corriger les blocs paragraphes qui posent problème
                    if (block.type === 'paragraph' && (!block.data || typeof block.data !== 'object')) {
                        console.log("Réparation d'un bloc paragraph:", block);
                        // Créer un bloc paragraph valide
                        return {
                            id: block.id || String(Math.floor(Math.random() * 1000000)),
                            type: 'paragraph',
                            data: {
                                text: block.data || ''
                            }
                        };
                    }

                    // Assurer que tous les blocs ont un ID
                    if (!block.id) {
                        block.id = String(Math.floor(Math.random() * 1000000));
                    }

                    // S'assurer que data est toujours un objet
                    if (!block.data || typeof block.data !== 'object') {
                        block.data = {};
                    }
                    
                    return block;
                }).filter(function(block) {
                    // Vérifier que chaque bloc a les propriétés nécessaires après réparation
                    if (!(block && block.type && block.data)) {
                        console.warn("Block skipped due to missing properties:", block);
                        return false;
                    }
                    
                    console.log("Processing block type:", block.type);
                    return true;
                });
                
                console.log("Cleaned editor data:", parsedData);
            }
            
            // Définir la variable globale immédiatement
            window.editorData = parsedData;
        } catch(e) {
            console.error("Erreur de parsing du contenu JSON:", e);
            // Créer une structure minimale valide pour éviter les erreurs
            window.editorData = {
                time: Date.now(),
                version: "2.28.0",
                blocks: []
            };
        }
    })();
</script>
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">{{ isset($page->id) ? 'Modifier la page' : 'Créer une nouvelle page' }}</h1>
            <p class="text-gray-500 mt-1">Rédigez votre contenu avec l'éditeur avancé</p>
        </div>
        <div class="flex items-center gap-3">
            <div id="autosave-indicator" class="autosave-indicator text-sm text-gray-500">
                <i class="fas fa-circle text-gray-400 mr-1"></i>
                <span id="autosave-text">Prêt</span>
            </div>
            <a href="{{ route('personnels.pages.pages.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>

    <form id="page-form" action="{{ route('personnels.pages.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Contenu principal -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Titre -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de la page <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               value="{{ old('title', $page->title ?? '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror text-lg"
                               placeholder="Entrez le titre de votre page..."
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                            Extrait (résumé)
                        </label>
                        <textarea name="excerpt" 
                                  id="excerpt" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('excerpt') border-red-300 @enderror"
                                  placeholder="Résumé de la page (optionnel, utilisé pour les aperçus)">{{ old('excerpt', $page->excerpt ?? '') }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Éditeur de contenu -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Contenu de la page</h3>
                        <p class="text-sm text-gray-500 mt-1">Utilisez l'éditeur pour formater votre contenu</p>
                    </div>
                    

                    <div class="p-6">

                        <div id="editorjs" class="p-4 border border-gray-300"></div>

                            <!-- Le contenu sera mis à jour par le JavaScript avant la soumission -->
                            <input type="hidden" name="content" id="content" value="">
                        
                    
                        <div class="flex justify-between mt-3 text-xs text-gray-500">
                            <span><i class="fas fa-keyboard mr-1"></i> Éditeur simple avec mise en forme par blocs</span>
                            <span id="block-count">0 blocs</span>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aide pour les images -->
                <div class="bg-green-50 rounded-xl border border-green-200 p-4">
                    <h4 class="text-sm font-medium text-green-900 mb-2">
                        <i class="fas fa-images mr-1"></i> Insertion d'images
                    </h4>
                    <p class="text-sm text-green-700 mb-3">
                        Dans l'éditeur, cliquez sur "+" puis sélectionnez "Image" pour insérer une image via URL
                    </p>
                    <div class="text-xs text-green-600 space-y-1">
                        <div><strong>Exemple d'URL :</strong></div>
                        <div class="font-mono bg-white p-2 rounded border">
                            http://localhost:8000/images/token/MX47snilphyQugrDGk6xwP2HYEU8RKLV
                        </div>
                        <div class="mt-2 text-green-700">
                            💡 L'éditeur par blocs permet aussi d'ajouter des titres, listes, citations, code et bien plus !
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panneau latéral -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Publication -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Publication</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_published" 
                                       value="1" 
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Publier immédiatement</span>
                            </label>
                        </div>
                        
                        <div class="text-xs text-gray-500">
                            Si non cochée, la page sera sauvegardée en brouillon
                        </div>
                    </div>
                </div>

                <!-- Organisation -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Organisation</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="section_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Section <span class="text-red-500">*</span>
                            </label>
                            <select name="section_id" 
                                    id="section_id" 
                                    class="w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('section_id') border-red-300 @enderror"
                                    required>
                                <option value="">Choisir une section</option>
                                @foreach(\App\Modules\Pages\Models\Section::active()->orderBy('name')->get() as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id', $page->section_id ?? '') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="folder_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Dossier
                            </label>
                            <select name="folder_id" 
                                    id="folder_id" 
                                    class="w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    data-current="{{ old('folder_id', $page->folder_id ?? '') }}">
                                <option value="">Racine de la section</option>
                                <!-- Les dossiers seront chargés dynamiquement via JavaScript -->
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Optionnel - laissez vide pour placer à la racine</p>
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">SEO</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Titre SEO
                            </label>
                            <input type="text" 
                                   name="meta_title" 
                                   id="meta_title" 
                                   value="{{ old('meta_title', $page->meta_title ?? '') }}" 
                                   class="w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                   placeholder="Titre pour les moteurs de recherche">
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description SEO
                            </label>
                            <textarea name="meta_description" 
                                      id="meta_description" 
                                      rows="3" 
                                      class="w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                      placeholder="Description pour les moteurs de recherche">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                        </div>

                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">
                                Tags
                            </label>
                            <input type="text" 
                                   name="tags" 
                                   id="tags" 
                                   value="{{ old('tags', is_array($page->tags) ? implode(', ', $page->tags) : $page->tags) }}" 
                                   class="w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                   placeholder="tag1, tag2, tag3">
                            <p class="mt-1 text-xs text-gray-500">Séparez les tags par des virgules</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="space-y-3">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                        
                        <button type="button" id="save-draft" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition-all">
                            <i class="fas fa-file-alt mr-2"></i> Sauvegarder en brouillon
                        </button>
                        
                        <a href="{{ route('personnels.pages.pages.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


@endsection
