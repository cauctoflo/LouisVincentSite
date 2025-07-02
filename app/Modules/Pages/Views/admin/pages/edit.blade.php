@extends('layouts.admin')

@section('title', 'Modifier la page: ' . $page->title)

@section('head')
<!-- Editor.js CSS amélioré -->
<style>
#editorjs {
  padding: 2rem;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  background: #fff;
  min-height: 500px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  transition: all 0.25s ease;
}

#editorjs:focus-within {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.ce-block__content {
  max-width: 850px; /* Largeur plus confortable pour la lecture */
}

.ce-toolbar__plus {
  background-color: #f3f4f6;
  border-radius: 50%;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.ce-toolbar__plus:hover {
  background-color: #e5e7eb;
}

/* Styles pour l'éditeur en plein écran */
#editorjs.fullscreen-mode {
  border-radius: 0;
  padding: 3rem;
}

/* Styles pour la barre d'outils de l'éditeur */
.editor-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 1rem;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-bottom: none;
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
}

.editor-toolbar__group {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

/* Styles pour la barre d'état */
.editor-statusbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.625rem 1rem;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-top: none;
  border-bottom-left-radius: 0.5rem;
  border-bottom-right-radius: 0.5rem;
  color: #6b7280;
  font-size: 0.75rem;
}

.editor-container {
  margin-bottom: 1rem;
  transition: all 0.3s ease;
}
</style>
@endsection

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Modifier: {{ $page->title }}</h1>
            <p class="text-gray-500 mt-1">{{ $page->section->name }}@if($page->folder) > {{ $page->folder->name }}@endif</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('personnels.pages.pages.show', $page) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <form id="page-form" action="{{ route('personnels.pages.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Contenu principal -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Informations de base -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Titre -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $page->title) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                URL (slug)
                            </label>
                            <input type="text" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug', $page->slug) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Généré automatiquement si vide">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Extrait -->
                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                                Extrait/Résumé
                            </label>
                            <textarea id="excerpt" 
                                      name="excerpt" 
                                      rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="Description courte de la page...">{{ old('excerpt', $page->excerpt) }}</textarea>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Éditeur de contenu -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Contenu de la page</h3>
                            <p class="text-sm text-gray-500 mt-1">Utilisez les blocs pour structurer votre contenu</p>
                        </div>
                        <div>
                            <span id="save-status" class="text-xs bg-gray-100 px-3 py-1 rounded-full hidden">
                                <i class="fas fa-circle text-green-500 mr-1"></i> Enregistré
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Barre d'outils améliorée -->
                        <div class="editor-toolbar mb-3 rounded-t-lg">
                            <div class="editor-toolbar__group">
                                <div class="flex items-center text-blue-700">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    <span class="text-sm">
                                        <strong>Éditeur par blocs</strong> - Utilisez <kbd class="bg-gray-100 text-xs px-1 py-0.5 rounded">+</kbd> pour ajouter du contenu
                                    </span>
                                </div>
                            </div>
                            
                            <div class="editor-toolbar__group">
                                <div class="flex items-center gap-3">
                                    <button type="button" id="fullscreen-btn" class="text-xs flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded border border-blue-100 transition-colors">
                                        <i class="fas fa-expand-alt mr-1"></i> Plein écran
                                    </button>
                                    <span id="block-count" class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full font-medium">0 blocs</span>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Conteneur de l'éditeur avec meilleur design -->
                        <div class="editor-container">
                            <div id="editorjs" class="rounded-lg focus:outline-none border border-gray-200"></div>
                        </div>
                        
                        <!-- Champ caché pour le contenu -->
                        <textarea id="content" name="content" class="hidden">{{ old('content', $page->content) }}</textarea>
                        
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Barre d'état améliorée -->
                        <div class="editor-statusbar mt-1 rounded-b-lg">
                            <div class="flex items-center space-x-3">
                                <span class="flex items-center"><i class="fas fa-keyboard mr-1"></i> Éditeur par blocs</span>
                                <span class="h-4 w-px bg-gray-300"></span>
                                <span>Dernière modification: <span id="last-edit-time" class="text-gray-700 font-medium">-</span></span>
                            </div>
                            <div>
                                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                    <i class="fas fa-save mr-1"></i> Ctrl+S pour enregistrer
                                </span>
                            </div>
                        </div>
                    </div>
                                            <button type="button" 
                                                    id="cancel-image"
                                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                                                Annuler
                                            </button>
                                            <button type="button" 
                                                    id="insert-image"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                Insérer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Publication -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Publication</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_published" 
                                   name="is_published" 
                                   value="1"
                                   {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_published" class="ml-3 text-sm font-medium text-gray-700">
                                Page publiée
                            </label>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="submit" 
                                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-all">
                                    <i class="fas fa-save mr-2"></i>
                                    Sauvegarder
                                </button>
                                
                                @if(auth()->user()->hasPermission('pages.edit'))
                                <button type="button" 
                                        id="save-draft"
                                        class="inline-flex justify-center items-center px-4 py-2 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition-all">
                                    <i class="fas fa-file-alt mr-2"></i>
                                    Brouillon
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
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
                                <option value="{{ $section->id }}" {{ old('section_id', $page->section_id) == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Dossier -->
                        <div>
                            <label for="folder_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Dossier (optionnel)
                            </label>
                            <select id="folder_id" 
                                    name="folder_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Aucun dossier</option>
                                @if($page->section)
                                    @foreach($page->section->folders as $folder)
                                    <option value="{{ $folder->id }}" {{ old('folder_id', $page->folder_id) == $folder->id ? 'selected' : '' }}>
                                        {{ $folder->name }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('folder_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Ordre -->
                        <div>
                            <label for="order_index" class="block text-sm font-medium text-gray-700 mb-2">
                                Ordre d'affichage
                            </label>
                            <input type="number" 
                                   id="order_index" 
                                   name="order_index" 
                                   value="{{ old('order_index', $page->order_index) }}"
                                   min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('order_index')
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
                                   value="{{ old('meta_title', $page->meta_title) }}"
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
                                      placeholder="Description pour les moteurs de recherche...">{{ old('meta_description', $page->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Tags -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
                    
                    <div id="tags-input" class="space-y-2">
                        <input type="text" 
                               id="new-tag" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Ajouter un tag...">
                        
                        <div id="tags-list" class="flex flex-wrap gap-2">
                            @if($page->tags)
                                @foreach($page->tags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800">
                                    {{ $tag }}
                                    <button type="button" class="ml-2 text-indigo-600 hover:text-indigo-800" onclick="removeTag(this, '{{ $tag }}')">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    <input type="hidden" name="tags[]" value="{{ $tag }}">
                                </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<!-- Inclure Editor.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>

<script>
let editor;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Initialisation de la page d\'édition avec Editor.js');
    
    // Initialiser Editor.js avec l'approche simplifiée
    initEditor();
    
    // Gérer la soumission du formulaire
    const pageForm = document.getElementById('page-form');
    if (pageForm) {
        pageForm.addEventListener('submit', handleFormSubmit);
    }
    
    // Gestion du mode plein écran
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', toggleFullscreen);
    }
    
    // Génération automatique du slug
    document.getElementById('title').addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        
        if (!document.getElementById('slug').value || document.getElementById('slug').value === '') {
            document.getElementById('slug').value = slug;
        }
    });
    
    // Gestion des dossiers selon la section
    document.getElementById('section_id').addEventListener('change', function() {
        const sectionId = this.value;
        const folderSelect = document.getElementById('folder_id');
        
        // Vider les options
        folderSelect.innerHTML = '<option value="">Aucun dossier</option>';
        
        if (sectionId) {
            fetch(`{{ route('personnels.pages.pages.folders') }}?section_id=${sectionId}`)
                .then(response => response.json())
                .then(folders => {
                    folders.forEach(folder => {
                        const option = document.createElement('option');
                        option.value = folder.id;
                        option.textContent = folder.name;
                        folderSelect.appendChild(option);
                    });
                });
        }
    });
    
    // Gestion des tags
    const newTagInput = document.getElementById('new-tag');
    if (newTagInput) {
        newTagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag(this.value.trim());
                this.value = '';
            }
        });
    }
    
    // Sauvegarder en brouillon
    const saveDraftBtn = document.getElementById('save-draft');
    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function() {
            document.getElementById('is_published').checked = false;
            document.getElementById('page-form').submit();
        });
    }
});

// Fonction pour mettre à jour le compteur de blocs
function updateBlockCount() {
    if (editor) {
        editor.save().then((data) => {
            const count = data.blocks ? data.blocks.length : 0;
            document.getElementById('block-count').textContent = count + (count === 1 ? ' bloc' : ' blocs');
        });
    }
}

function initEditor() {
    try {
        console.log('Initialisation de l\'éditeur...');
        
        // Récupérer le contenu initial s'il existe
        const contentField = document.getElementById('content');
        let initialContent = contentField ? contentField.value : '';
        let initialData = {
            time: new Date().getTime(),
            blocks: [
                {
                    type: "paragraph",
                    data: {
                        text: "Bienvenue dans <b>Editor.js</b> ! Commencez à écrire ici."
                    }
                }
            ]
        };
        
        // Si un contenu existe déjà, on l'utilise
        if (initialContent && initialContent.trim() !== '') {
            try {
                initialData = JSON.parse(initialContent);
                console.log('Contenu JSON existant chargé');
            } catch (e) {
                console.warn('Format non-JSON, création d\'un nouveau bloc');
            }
        }
        
        // Création de l'instance EditorJS avec une configuration simplifiée et améliorée
        editor = new EditorJS({
            /** ID de l'élément conteneur */
            holder: 'editorjs',
            
            /** Données par défaut */
            data: initialData,
            
            /** Configuration améliorée */
            placeholder: 'Commencez à écrire votre contenu ou cliquez sur "+" pour ajouter un bloc...',
            
            /** Configuration de base */
            onReady: () => {
                console.log('Editor.js est prêt à l\'utilisation');
                updateBlockCount(); // Mise à jour initiale du compteur de blocs
            },
            
            onChange: async () => {
                const content = await editor.save();
                document.getElementById('content').value = JSON.stringify(content);
                updateBlockCount(); // Mise à jour du compteur à chaque changement
                console.log('Contenu mis à jour');
            }
        });
        
    } catch (error) {
        console.error('Erreur lors de l\'initialisation d\'Editor.js:', error);
        document.getElementById('editorjs').innerHTML = `
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-600 font-medium">Erreur d'initialisation de l'éditeur</p>
                <p class="text-red-500 text-sm mt-1">${error.message}</p>
                <p class="text-gray-600 text-sm mt-2">Rechargez la page pour réessayer.</p>
            </div>
        `;
    }
}

function handleFormSubmit(e) {
    if (e) e.preventDefault();
    
    if (editor) {
        editor.save().then((outputData) => {
            document.getElementById('content').value = JSON.stringify(outputData);
            document.getElementById('page-form').submit();
        }).catch((error) => {
            console.error('Erreur lors de la sauvegarde du contenu:', error);
        });
    } else {
        document.getElementById('page-form').submit();
    }
}

function addTag(tagName) {
    if (!tagName) return;
    
    const tagsList = document.getElementById('tags-list');
    if (!tagsList) return;
    
    const existingTags = Array.from(tagsList.querySelectorAll('input[name="tags[]"]')).map(input => input.value);
    
    if (existingTags.includes(tagName)) return;
    
    const tagElement = document.createElement('span');
    tagElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800';
    tagElement.innerHTML = `
        ${tagName}
        <button type="button" class="ml-2 text-indigo-600 hover:text-indigo-800" onclick="removeTag(this, '${tagName}')">
            <i class="fas fa-times text-xs"></i>
        </button>
        <input type="hidden" name="tags[]" value="${tagName}">
    `;
    
    tagsList.appendChild(tagElement);
}

function removeTag(button, tagName) {
    button.closest('span').remove();
}

// Fonction pour basculer en mode plein écran avec animation
function toggleFullscreen() {
    const editorContainer = document.getElementById('editorjs');
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const editorWrapper = document.querySelector('.editor-container');
    
    if (!editorContainer.classList.contains('fullscreen-mode')) {
        // Activer le mode plein écran avec animation
        editorContainer.classList.add('fullscreen-mode');
        
        // Position fixe avec animation
        editorContainer.style.position = 'fixed';
        editorContainer.style.top = '0';
        editorContainer.style.left = '0';
        editorContainer.style.width = '100vw';
        editorContainer.style.height = '100vh';
        editorContainer.style.zIndex = '9999';
        editorContainer.style.padding = '3rem';
        editorContainer.style.backgroundColor = '#fff';
        editorContainer.style.overflow = 'auto';
        editorContainer.style.transition = 'all 0.3s ease';
        
        // Amélioration du focus sur le contenu en plein écran
        editorContainer.style.boxShadow = '0 0 0 100vmax rgba(0, 0, 0, 0.3)';
        
        // Modifier le texte du bouton
        fullscreenBtn.innerHTML = '<i class="fas fa-compress-alt mr-1"></i> Réduire';
        fullscreenBtn.classList.remove('bg-blue-50', 'hover:bg-blue-100', 'text-blue-700', 'border-blue-100');
        fullscreenBtn.classList.add('bg-gray-200', 'hover:bg-gray-300', 'text-gray-700', 'border-gray-300');
        
        // Désactiver le défilement du corps
        document.body.style.overflow = 'hidden';
        
        // Ajouter un message flottant temporaire
        const msg = document.createElement('div');
        msg.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 bg-black/70 text-white px-4 py-2 rounded-full text-sm z-[10000] opacity-0 transition-opacity';
        msg.innerHTML = '<i class="fas fa-expand mr-2"></i> Mode plein écran actif — Appuyez sur Esc pour quitter';
        document.body.appendChild(msg);
        
        // Afficher puis masquer le message
        setTimeout(() => msg.style.opacity = '1', 100);
        setTimeout(() => {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }, 3000);
        
        // Ajouter un événement pour sortir avec Echap
        document.addEventListener('keydown', exitFullscreenOnEsc);
    } else {
        // Désactiver le mode plein écran
        exitFullscreenMode();
    }
    
    // Fonction pour sortir du mode plein écran
    function exitFullscreenMode() {
        editorContainer.classList.remove('fullscreen-mode');
        editorContainer.style.position = '';
        editorContainer.style.top = '';
        editorContainer.style.left = '';
        editorContainer.style.width = '';
        editorContainer.style.height = '';
        editorContainer.style.zIndex = '';
        editorContainer.style.padding = '';
        editorContainer.style.backgroundColor = '';
        editorContainer.style.overflow = '';
        editorContainer.style.boxShadow = '';
        
        // Restaurer le texte du bouton
        fullscreenBtn.innerHTML = '<i class="fas fa-expand-alt mr-1"></i> Plein écran';
        fullscreenBtn.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'text-gray-700', 'border-gray-300');
        fullscreenBtn.classList.add('bg-blue-50', 'hover:bg-blue-100', 'text-blue-700', 'border-blue-100');
        
        // Réactiver le défilement du corps
        document.body.style.overflow = '';
        
        // Supprimer l'événement d'échap
        document.removeEventListener('keydown', exitFullscreenOnEsc);
    }
    
    // Gestionnaire pour la touche Echap
    function exitFullscreenOnEsc(e) {
        if (e.key === 'Escape' && editorContainer.classList.contains('fullscreen-mode')) {
            exitFullscreenMode();
        }
    }
}
}
</script>
@endsection
