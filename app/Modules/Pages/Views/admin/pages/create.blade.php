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

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Créer une nouvelle page</h1>
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

    <form id="page-form" action="{{ route('personnels.pages.pages.store') }}" method="POST">
        @csrf
        
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
                               value="{{ old('title') }}" 
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
                                  placeholder="Résumé de la page (optionnel, utilisé pour les aperçus)">{{ old('excerpt') }}</textarea>
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
                        <!-- Éditeur Editor.js simplifié -->
                        <div id="editorjs" class="mb-4"></div>
                        
                        <!-- Champ caché pour stocker le contenu JSON -->
                        <input type="hidden" name="content" id="content" value="{{ old('content') }}">
                        
                        <!-- Indicateurs de statut pour l'édition -->
                        <div class="flex justify-between mt-3 text-xs text-gray-500">
                            <span><i class="fas fa-keyboard mr-1"></i> Éditeur simple avec mise en forme par blocs</span>
                            <span id="block-count">0 blocs</span>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aide pour l'édition -->
                <div class="bg-green-50 rounded-xl border border-green-200 p-4">
                    <h4 class="text-sm font-medium text-green-900 mb-2">
                        <i class="fas fa-edit mr-1"></i> Outils d'édition disponibles
                    </h4>
                    <p class="text-sm text-green-700 mb-3">
                        Cliquez sur "+" dans l'éditeur pour accéder à tous les outils de mise en forme
                    </p>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-white p-3 rounded border">
                            <div class="font-medium text-gray-800 mb-1"><i class="fas fa-heading mr-1 text-blue-600"></i> Titres</div>
                            <div class="text-gray-600">H1, H2, H3 pour structurer votre contenu</div>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <div class="font-medium text-gray-800 mb-1"><i class="fas fa-list mr-1 text-blue-600"></i> Listes</div>
                            <div class="text-gray-600">À puces ou numérotées</div>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <div class="font-medium text-gray-800 mb-1"><i class="fas fa-quote-right mr-1 text-blue-600"></i> Citations</div>
                            <div class="text-gray-600">Citations avec auteur optionnel</div>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <div class="font-medium text-gray-800 mb-1"><i class="fas fa-code mr-1 text-blue-600"></i> Code</div>
                            <div class="text-gray-600">Blocs de code formatés</div>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <div class="font-medium text-gray-800 mb-1"><i class="fas fa-table mr-1 text-blue-600"></i> Tableaux</div>
                            <div class="text-gray-600">Tableaux formatés avec cellules</div>
                        </div>
                        <div class="bg-white p-3 rounded border">
                            <div class="font-medium text-gray-800 mb-1"><i class="fas fa-images mr-1 text-blue-600"></i> Images</div>
                            <div class="text-gray-600">Via URL (ex: http://localhost:8000/images/token/...)</div>
                        </div>
                    </div>
                    <div class="mt-3 text-center text-xs text-green-700">
                        <div>💡 Raccourci : <span class="bg-white px-2 py-1 rounded border">Ctrl+S</span> pour sauvegarder rapidement</div>
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
                                       {{ old('is_published') ? 'checked' : '' }}>
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
                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
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
                                    class="w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
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
                                   value="{{ old('meta_title') }}" 
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
                                      placeholder="Description pour les moteurs de recherche">{{ old('meta_description') }}</textarea>
                        </div>

                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">
                                Tags
                            </label>
                            <input type="text" 
                                   name="tags" 
                                   id="tags" 
                                   value="{{ old('tags') }}" 
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

<!-- Inclure Editor.js et ses outils via CDN -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>

<script>
let editor;

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Editor.js avec l'approche simplifiée
    initEditor();
    
    // Gérer le changement de section pour charger les dossiers
    const sectionSelect = document.getElementById('section_id');
    if (sectionSelect) {
        sectionSelect.addEventListener('change', loadFolders);
    }
    
    // Gérer la soumission du formulaire
    const pageForm = document.getElementById('page-form');
    if (pageForm) {
        pageForm.addEventListener('submit', handleFormSubmit);
    }
    
    // Raccourci clavier pour sauvegarder
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            handleFormSubmit(e);
        }
    });
    
    // Configuration du bouton de sauvegarde en brouillon
    const saveDraftButton = document.getElementById('save-draft');
    if (saveDraftButton) {
        saveDraftButton.addEventListener('click', saveDraft);
    }
});

function initEditor() {
    try {
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
            } catch (e) {
                console.warn('Format non-JSON, création d\'un nouveau bloc');
            }
        }
        
        // Création de l'instance EditorJS avec des outils avancés
        editor = new EditorJS({
            /** ID de l'élément conteneur */
            holder: 'editorjs',
            
            /** Données par défaut */
            data: initialData,
            
            /** Configuration des outils disponibles */
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: ['marker', 'link'],
                    config: {
                        placeholder: 'Entrez un titre',
                        levels: [1, 2, 3],
                        defaultLevel: 2
                    }
                },
                list: {
                    class: List,
                    inlineToolbar: true,
                    config: {
                        defaultStyle: 'unordered'
                    }
                },
                quote: {
                    class: Quote,
                    inlineToolbar: true,
                    config: {
                        quotePlaceholder: 'Entrez une citation',
                        captionPlaceholder: 'Auteur de la citation'
                    }
                },
                code: {
                    class: CodeTool
                },
                image: {
                    class: ImageTool,
                    config: {
                        endpoints: {
                            byFile: null, // Désactivé pour le moment
                            byUrl: '/loadImageFromUrl'
                        },
                        field: 'image',
                        types: 'image/*'
                    }
                },
                link: {
                    class: LinkTool,
                    config: {
                        endpoint: '/fetchUrl'
                    }
                },
                table: {
                    class: Table,
                    inlineToolbar: true
                },
                marker: {
                    class: Marker,
                    shortcut: 'CMD+M'
                }
            },
            
            /** Configuration de base */
            onReady: () => {
                console.log('Editor.js est prêt à l\'utilisation avec tous les outils');
                document.getElementById('block-count').textContent = 
                    editor.blocks.getBlocksCount() + ' blocs';
            },
            
            onChange: async () => {
                const content = await editor.save();
                document.getElementById('content').value = JSON.stringify(content);
                updateAutosaveIndicator('saved', 'Sauvegardé');
                document.getElementById('block-count').textContent = 
                    editor.blocks.getBlocksCount() + ' blocs';
            }
        });
        
    } catch (error) {
        console.error('Erreur lors de l\'initialisation d\'Editor.js:', error);
        
        // Fallback vers un textarea simple en cas d'erreur
        const contentField = document.getElementById('content');
        const existingContent = contentField ? contentField.value : '';
        
        document.getElementById('editorjs').innerHTML = `
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-700 mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Erreur lors du chargement de l'éditeur avancé.
                </p>
                <textarea name="content_fallback" 
                          id="content_fallback"
                          class="w-full h-64 p-3 border border-gray-300 rounded-lg" 
                          placeholder="Utilisez ce champ pour saisir votre contenu...">${existingContent}</textarea>
            </div>
        `;
        
        // Ajouter un gestionnaire pour synchroniser le contenu du fallback textarea avec le champ hidden
        const fallbackTextarea = document.getElementById('content_fallback');
        if (fallbackTextarea) {
            fallbackTextarea.addEventListener('input', function() {
                if (contentField) {
                    contentField.value = this.value;
                }
            });
        }
    }
}

//Cette fonction n'est plus utilisée dans la version simplifiée

async function handleFormSubmit(e) {
    if (e) e.preventDefault();
    
    try {
        // Sauvegarder le contenu de l'éditeur avant soumission
        if (editor) {
            const outputData = await editor.save();
            document.getElementById('content').value = JSON.stringify(outputData);
            console.log('Contenu sauvegardé avant soumission:', outputData);
        }
        
        // Vérifier que le contenu n'est pas vide
        const content = document.getElementById('content').value;
        if (!content || content === '{}' || content === '{"blocks":[]}') {
            alert('Veuillez saisir du contenu avant de sauvegarder.');
            return;
        }
        
        // Soumettre le formulaire
        if (e && e.target) e.target.submit();
    } catch (error) {
        console.error('Erreur lors de la soumission du formulaire:', error);
    }
}

function loadFolders() {
    const sectionId = document.getElementById('section_id').value;
    const folderSelect = document.getElementById('folder_id');
    
    // Vider les options actuelles
    folderSelect.innerHTML = '<option value="">Racine de la section</option>';
    
    if (sectionId) {
        fetch(`/personnels/pages/sections/${sectionId}/folders`)
            .then(response => response.json())
            .then(folders => {
                folders.forEach(folder => {
                    const option = document.createElement('option');
                    option.value = folder.id;
                    option.textContent = folder.name;
                    folderSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur lors du chargement des dossiers:', error));
    }
}

// Fonction pour indiquer les sauvegardes
function updateAutosaveIndicator(status, text) {
    const indicator = document.getElementById('autosave-indicator');
    const textElement = document.getElementById('autosave-text');
    
    if (!indicator || !textElement) return;
    
    textElement.textContent = text;
    
    const icon = indicator.querySelector('i');
    if (icon) {
        if (status === 'saving') {
            icon.className = 'fas fa-circle text-yellow-500 mr-1';
        } else if (status === 'saved') {
            icon.className = 'fas fa-circle text-green-500 mr-1';
        } else if (status === 'error') {
            icon.className = 'fas fa-circle text-red-500 mr-1';
        } else {
            icon.className = 'fas fa-circle text-gray-400 mr-1';
        }
    }
}

async function saveDraft() {
    try {
        // Sauvegarder le contenu Editor.js d'abord
        if (editor) {
            const outputData = await editor.save();
            document.getElementById('content').value = JSON.stringify(outputData);
        }
        
        const publishCheckbox = document.querySelector('[name="is_published"]');
        const wasChecked = publishCheckbox ? publishCheckbox.checked : false;
        
        if (publishCheckbox) {
            publishCheckbox.checked = false;
        }
        
        document.getElementById('page-form').submit();
        
        // Restaurer l'état original au cas où
        if (publishCheckbox) {
            publishCheckbox.checked = wasChecked;
        }
    } catch (error) {
        console.error('Erreur lors de la sauvegarde en brouillon:', error);
        alert('Erreur lors de la sauvegarde en brouillon');
    }
}
</script>
@endsection
