@extends('layouts.admin')

@section('head')
<!-- Editor.js CSS moderne et élégant -->
<style>
#editorjs {
  padding: 2rem;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  background: #ffffff;
  min-height: 400px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  line-height: 1.6;
  transition: all 0.2s ease;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

#editorjs:focus-within {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Styles pour les blocs de l'éditeur */
#editorjs .ce-block__content {
  max-width: none;
  margin: 0;
}

#editorjs .ce-paragraph {
  font-size: 16px;
  line-height: 1.7;
  color: #374151;
}

#editorjs .ce-header {
  color: #111827;
  font-weight: 700;
  margin: 1.5rem 0 1rem 0;
}

#editorjs h1.ce-header {
  font-size: 2rem;
  border-bottom: 2px solid #e5e7eb;
  padding-bottom: 0.5rem;
}

#editorjs h2.ce-header {
  font-size: 1.5rem;
}

#editorjs h3.ce-header {
  font-size: 1.25rem;
}

#editorjs .ce-list {
  color: #374151;
}

#editorjs .ce-list__item {
  margin: 0.5rem 0;
  line-height: 1.6;
}

#editorjs .ce-quote {
  border-left: 4px solid #6366f1;
  background: #f8fafc;
  padding: 1rem 1.5rem;
  margin: 1.5rem 0;
  border-radius: 0 8px 8px 0;
}

#editorjs .ce-quote__text {
  font-style: italic;
  font-size: 1.1rem;
  color: #4b5563;
  margin-bottom: 0.5rem;
}

#editorjs .ce-quote__caption {
  color: #6b7280;
  font-size: 0.9rem;
}

#editorjs .ce-code {
  background: #1f2937;
  color: #f9fafb;
  border-radius: 8px;
  padding: 1rem;
  margin: 1rem 0;
  font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
  font-size: 14px;
  line-height: 1.5;
}

#editorjs .ce-toolbar__plus {
  color: #6366f1 !important;
  background: #ffffff !important;
  border: 2px solid #e5e7eb !important;
  border-radius: 8px !important;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
  transition: all 0.2s ease !important;
}

#editorjs .ce-toolbar__plus:hover {
  background: #6366f1 !important;
  color: #ffffff !important;
  border-color: #6366f1 !important;
  transform: scale(1.05);
}

#editorjs .ce-toolbar__settings-btn {
  color: #6b7280 !important;
  border-radius: 6px !important;
  transition: all 0.2s ease !important;
}

#editorjs .ce-toolbar__settings-btn:hover {
  background: #f3f4f6 !important;
  color: #374151 !important;
}

#editorjs .ce-popover {
  border-radius: 12px !important;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
  border: 1px solid #e5e7eb !important;
}

#editorjs .ce-popover__item {
  border-radius: 8px !important;
  margin: 2px 4px !important;
  transition: all 0.2s ease !important;
}

#editorjs .ce-popover__item:hover {
  background: #6366f1 !important;
  color: #ffffff !important;
}

#editorjs .ce-popover__item-icon {
  border-radius: 6px !important;
  margin-right: 8px !important;
}

/* Indicateur de sauvegarde plus moderne */
.autosave-indicator {
  background: #f3f4f6;
  border-radius: 20px;
  padding: 0.5rem 1rem;
  transition: all 0.3s ease;
}

.autosave-indicator.saved {
  background: #d1fae5;
  color: #065f46;
}

.autosave-indicator.saved .fas {
  color: #10b981;
}

/* Animation pour le placeholder vide */
#editorjs .ce-block--empty::before {
  color: #9ca3af !important;
  font-style: italic;
}

/* Amélioration de la sélection de texte */
#editorjs ::selection {
  background: rgba(99, 102, 241, 0.2);
}

/* Responsive design pour l'éditeur */
@media (max-width: 768px) {
  #editorjs {
    padding: 1rem;
    font-size: 15px;
  }
  
  #editorjs h1.ce-header {
    font-size: 1.75rem;
  }
  
  #editorjs h2.ce-header {
    font-size: 1.375rem;
  }
}
</style>
@endsection

@section('scripts')
<!-- Scripts pour l'éditeur -->

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
{{-- @vite(['resources/js/editor-form.js', 'resources/js/tags-handler.js']) --}}

@section('content')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pageForm = document.getElementById('page-form');
        const contentInput = document.getElementById('content');
        let isEditorReady = false;

        // Écouter l'événement personnalisé indiquant que l'éditeur est prêt
        window.addEventListener('editorReady', () => {
            console.log('Éditeur prêt - événement reçu');
            isEditorReady = true;
            updateBlockCount();
        });

        const updateSaveIndicator = (status) => {
            const indicator = document.getElementById('autosave-indicator');
            const text = document.getElementById('autosave-text');
            
            if (!indicator || !text) return;
            
            if (status === 'saving') {
                indicator.classList.remove('saved');
                text.textContent = 'Enregistrement...';
            } else if (status === 'saved') {
                indicator.classList.add('saved');
                text.textContent = 'Modifications enregistrées';
            } else if (status === 'error') {
                indicator.classList.remove('saved');
                text.textContent = 'Erreur d\'enregistrement';
            }
        };

        const updateBlockCount = () => {
            const blockCountElement = document.getElementById('block-count');
            const editor = window.editorInstance || window.editor;
            if (!blockCountElement || !editor) return;
            
            const count = editor.blocks.getBlocksCount();
            blockCountElement.textContent = `${count} bloc${count !== 1 ? 's' : ''}`;
        };

        const saveEditorContent = async () => {
            updateSaveIndicator('saving');
            console.log("Début de la sauvegarde du contenu de l'éditeur");
            
            if (!contentInput) {
                console.error("Élément input#content non trouvé");
                return false;
            }
            
            try {
                // Attendre que l'éditeur soit prêt (avec timeout)
                let editorReady = false;
                let attempts = 0;
                const maxAttempts = 10;
                
                while (!editorReady && attempts < maxAttempts) {
                    // Vérifier différentes références possibles à l'éditeur
                    const editor = window.editorInstance || window.editor;
                    
                    if (editor && typeof editor.save === 'function') {
                        editorReady = true;
                        break;
                    }
                    
                    console.log(`Attente de l'éditeur... tentative ${attempts + 1}/${maxAttempts}`);
                    await new Promise(resolve => setTimeout(resolve, 100));
                    attempts++;
                }
                
                if (!editorReady) {
                    console.warn("L'instance de l'éditeur n'est pas prête après attente");
                    updateSaveIndicator('error');
                    return false;
                }
                
                const editor = window.editorInstance || window.editor;
                const savedData = await editor.save();
                console.log("Contenu récupéré depuis l'éditeur:", savedData);
                
                const jsonString = JSON.stringify(savedData);
                console.log("Contenu JSON à sauvegarder:", jsonString);
                
                contentInput.value = jsonString;
                console.log("Valeur du champ content après sauvegarde:", contentInput.value);
                
                updateBlockCount();
                updateSaveIndicator('saved');
                return true;
            } catch (error) {
                console.error('Erreur lors de la sauvegarde du contenu:', error);
                updateSaveIndicator('error');
                
                // En cas d'erreur, essayer de sauvegarder un contenu minimal
                try {
                    const minimalContent = JSON.stringify({
                        time: Date.now(),
                        version: "2.28.0",
                        blocks: []
                    });
                    contentInput.value = minimalContent;
                    console.log("Sauvegarde minimale fallback:", minimalContent);
                    return true; // On permet quand même la soumission avec un contenu vide
                } catch(e) {
                    console.error("Même la sauvegarde minimale a échoué:", e);
                    return false;
                }
            }
        };

        // Intercepter la soumission du formulaire pour sauvegarder le contenu de l'éditeur
        if (pageForm) {
            pageForm.addEventListener('submit', async (event) => {
                console.log("Formulaire soumis, sauvegarde du contenu avant envoi");
                event.preventDefault();
                
                if (await saveEditorContent()) {
                    console.log("Contenu sauvegardé avec succès, soumission du formulaire");
                    console.log("Valeur finale du champ content:", contentInput.value);
                    pageForm.submit();
                } else {
                    console.error("Échec de la sauvegarde du contenu, formulaire non soumis");
                    alert('Erreur lors de la sauvegarde du contenu. Veuillez réessayer.');
                }
            });
            
            // Bouton de sauvegarde en brouillon
            const saveDraftButton = document.getElementById('save-draft');
            if (saveDraftButton) {
                saveDraftButton.addEventListener('click', async () => {
                    const isPublishedCheckbox = document.querySelector('input[name="is_published"]');
                    if (isPublishedCheckbox) {
                        isPublishedCheckbox.checked = false;
                    }
                    
                    if (await saveEditorContent()) {
                        pageForm.submit();
                    } else {
                        alert('Erreur lors de la sauvegarde du brouillon. Veuillez réessayer.');
                    }
                });
            }
        }
    });
</script>


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
                        <div id="editorjs" class="editor-container"></div>
                        
                        <!-- Le contenu sera mis à jour par le JavaScript avant la soumission -->
                        <input type="hidden" name="content" id="content" value="">
                        
                        <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-keyboard text-indigo-500 mr-2"></i>
                                <span>Éditeur par blocs avec mise en forme avancée</span>
                            </div>
                            <div class="flex items-center bg-gray-50 px-3 py-1 rounded-full">
                                <i class="fas fa-cube text-gray-400 mr-1"></i>
                                <span id="block-count" class="font-medium">0 blocs</span>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>

                <!-- Aide pour l'éditeur -->
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200 p-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-emerald-100 rounded-full">
                                <i class="fas fa-lightbulb text-emerald-600"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-emerald-900 mb-3">
                                Guide d'utilisation de l'éditeur
                            </h4>
                            
                            <div class="space-y-3 text-sm text-emerald-800">
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-plus-circle text-emerald-600 mt-0.5 flex-shrink-0"></i>
                                    <span>Cliquez sur <strong>"+"</strong> pour ajouter des blocs (texte, titres, listes, images, etc.)</span>
                                </div>
                                
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-images text-emerald-600 mt-0.5 flex-shrink-0"></i>
                                    <span>Sélectionnez <strong>"Image"</strong> puis collez l'URL de votre image</span>
                                </div>
                                
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-heading text-emerald-600 mt-0.5 flex-shrink-0"></i>
                                    <span>Utilisez les <strong>titres</strong> pour structurer votre contenu</span>
                                </div>
                            </div>
                            
                            <div class="mt-4 p-3 bg-white rounded-lg border border-emerald-200">
                                <div class="text-xs font-medium text-emerald-700 mb-1">Exemple d'URL d'image :</div>
                                <div class="font-mono text-xs text-gray-600 break-all">
                                    http://localhost:8000/images/token/MX47snilphyQugrDGk6xwP2HYEU8RKLV
                                </div>
                            </div>
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
