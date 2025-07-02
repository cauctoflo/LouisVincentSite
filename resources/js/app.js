import './bootstrap';
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Checklist from '@editorjs/checklist';
import Quote from '@editorjs/quote';
import CodeTool from '@editorjs/code';
import LinkTool from '@editorjs/link';
import Attaches from '@editorjs/attaches';
import Embed from '@editorjs/embed';

// Initialiser l'éditeur une fois que tout est prêt
document.addEventListener('DOMContentLoaded', () => {
  // Initialisation sécurisée
  setTimeout(() => {
    // Vérifier que l'élément existe
    const editorElement = document.getElementById('editorjs');
    if (!editorElement) {
      console.warn("L'élément #editorjs n'a pas été trouvé dans le DOM");
      return;
    }
    
    try {
      // S'assurer que editorData est disponible
      if (typeof window.editorData === 'undefined') {
        console.warn("editorData n'est pas défini, utilisation d'un contenu vide");
        window.editorData = { blocks: [] };
      }
      
      console.log('Initialisation de l\'éditeur avec les données:', window.editorData);
      
      // Initialiser l'éditeur et l'exposer à l'objet window
      window.editor = new EditorJS({
        holder: 'editorjs',
        tools: {
          paragraph: {
            inlineToolbar: true
          },
          header: Header,
          list: List,
          checklist: Checklist,
          quote: Quote,
          code: CodeTool,
          linkTool: LinkTool,
          attaches: Attaches,
          embed: Embed
        },
        // Charger les données précédemment sauvegardées
        data: window.editorData || { blocks: [] },
        // Options d'autofocus
        autofocus: true,
        onReady: () => {
          console.log('Editor.js est prêt');
        },
        onChange: () => {
          console.log('Contenu modifié');
        }
      });
      
      console.log("Editor.js initialisé avec succès");
    } catch (e) {
      console.error("Erreur lors de l'initialisation de l'éditeur:", e);
    }
  }, 500); // Délai plus long pour s'assurer que le DOM est complètement chargé
});