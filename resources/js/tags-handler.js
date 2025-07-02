/**
 * Tags Handler - Gestion des tags pour les pages
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initialiser la gestion des tags
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
});

/**
 * Ajoute un nouveau tag à la liste
 * @param {string} tagName - Le nom du tag à ajouter
 */
function addTag(tagName) {
    if (!tagName) return;
    
    const tagsList = document.getElementById('tags-list');
    if (!tagsList) return;
    
    // Récupérer tous les tags existants
    const existingTags = Array.from(tagsList.querySelectorAll('input[name="tags[]"]')).map(input => input.value);
    
    if (existingTags.includes(tagName)) return;
    
    // Créer l'élément du tag
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

/**
 * Supprime un tag de la liste
 * @param {HTMLElement} button - Le bouton de suppression cliqué
 * @param {string} tagName - Le nom du tag à supprimer
 */
function removeTag(button, tagName) {
    button.closest('span').remove();
}

// Exposer les fonctions globalement pour pouvoir les appeler depuis l'HTML
window.addTag = addTag;
window.removeTag = removeTag;
