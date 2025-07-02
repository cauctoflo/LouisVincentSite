/**
 * Editor.js form integration script
 * This script handles the integration between Editor.js and form submission
 */

document.addEventListener('DOMContentLoaded', () => {
    // Référence aux éléments du DOM
    const pageForm = document.getElementById('page-form');
    const contentInput = document.getElementById('content');
    const blockCountElement = document.getElementById('block-count');
    const autosaveIndicator = document.getElementById('autosave-indicator');
    const autosaveText = document.getElementById('autosave-text');
    const saveDraftButton = document.getElementById('save-draft');

    // S'assurer que les éléments nécessaires existent
    if (!pageForm || !contentInput) {
        console.error('Éléments de formulaire manquants');
        return;
    }

    // Accès à l'instance de l'éditeur
    // EditorJS est initialisé dans app.js et est disponible globalement
    const editorInstance = window.editor;
    
    if (!editorInstance) {
        console.error("L'instance de l'éditeur n'est pas disponible");
        return;
    }

    // Fonction pour mettre à jour le compteur de blocs
    const updateBlockCount = async () => {
        try {
            // Vérifier que l'éditeur est bien initialisé et a la méthode save
            if (!editorInstance || typeof editorInstance.save !== 'function') {
                console.warn("L'instance de l'éditeur n'est pas prête ou n'a pas de méthode save");
                if (blockCountElement) {
                    blockCountElement.textContent = '0 bloc';
                }
                return null;
            }
            
            const savedData = await editorInstance.save();
            const count = savedData.blocks ? savedData.blocks.length : 0;
            if (blockCountElement) {
                blockCountElement.textContent = `${count} bloc${count !== 1 ? 's' : ''}`;
            }
            return savedData;
        } catch (error) {
            console.error('Erreur lors du comptage des blocs:', error);
            if (blockCountElement) {
                blockCountElement.textContent = 'Erreur de comptage';
            }
            return null;
        }
    };

    // Fonction pour mettre à jour l'indicateur de sauvegarde
    const updateSaveIndicator = (status) => {
        if (!autosaveIndicator || !autosaveText) return;
        
        autosaveIndicator.classList.remove('saved');
        
        switch (status) {
            case 'saving':
                autosaveText.textContent = 'Sauvegarde...';
                break;
            case 'saved':
                autosaveText.textContent = 'Sauvegardé';
                autosaveIndicator.classList.add('saved');
                break;
            case 'error':
                autosaveText.textContent = 'Erreur de sauvegarde';
                break;
            default:
                autosaveText.textContent = 'Prêt';
        }
    };

    // Fonction pour sauvegarder le contenu de l'éditeur dans le champ caché
    const saveEditorContent = async () => {
        updateSaveIndicator('saving');
        
        try {
            // Vérifier que l'éditeur est bien initialisé et a la méthode save
            if (!editorInstance || typeof editorInstance.save !== 'function') {
                console.warn("L'instance de l'éditeur n'est pas prête ou n'a pas de méthode save");
                updateSaveIndicator('error');
                return false;
            }
            
            const savedData = await editorInstance.save();
            contentInput.value = JSON.stringify(savedData);
            updateBlockCount();
            updateSaveIndicator('saved');
            return true;
        } catch (error) {
            console.error('Erreur lors de la sauvegarde du contenu:', error);
            updateSaveIndicator('error');
            
            // En cas d'erreur, essayer de sauvegarder un contenu minimal
            try {
                contentInput.value = JSON.stringify({
                    time: Date.now(),
                    version: "2.28.0",
                    blocks: []
                });
                return true; // On permet quand même la soumission avec un contenu vide
            } catch(e) {
                return false;
            }
        }
    };

    // Intercepter la soumission du formulaire pour sauvegarder le contenu de l'éditeur
    pageForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        if (await saveEditorContent()) {
            pageForm.submit();
        } else {
            alert('Erreur lors de la sauvegarde du contenu. Veuillez réessayer.');
        }
    });

    // Gestion du bouton "Sauvegarder en brouillon"
    if (saveDraftButton) {
        saveDraftButton.addEventListener('click', async () => {
            // Désactiver la publication
            const publishCheckbox = document.querySelector('input[name="is_published"]');
            if (publishCheckbox) {
                publishCheckbox.checked = false;
            }
            
            // Sauvegarder et soumettre
            if (await saveEditorContent()) {
                pageForm.submit();
            }
        });
    }

    // Mettre à jour le compteur de blocs périodiquement
    setInterval(updateBlockCount, 5000);
    
    // Initialiser le compteur de blocs
    setTimeout(updateBlockCount, 1000);
});
