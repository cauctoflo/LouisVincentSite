/**
 * Section and folder selection handler
 * 
 * This script handles the dynamic loading of folders when a section is selected
 * in the page creation/editing form.
 */

document.addEventListener('DOMContentLoaded', () => {
    // Références aux éléments DOM
    const sectionSelect = document.getElementById('section_id');
    const folderSelect = document.getElementById('folder_id');
    
    // Stocker la valeur actuelle du dossier sélectionné pour la restaurer après le chargement
    let currentFolderId = folderSelect.value;
    
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
