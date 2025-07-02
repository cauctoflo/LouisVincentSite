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


document.addEventListener('DOMContentLoaded', () => {
  const editor = new EditorJS({
    holder: 'editorjs',
    tools: {
        header: Header,
        list: List,
        checklist: Checklist,
        quote: Quote,
        code: CodeTool,
        linkTool: LinkTool,
        attaches: Attaches,
        embed: Embed,
    },
  });
});


