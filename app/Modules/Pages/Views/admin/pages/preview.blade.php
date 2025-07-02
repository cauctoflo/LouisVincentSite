@extends('layouts.app')

@section('title', 'Prévisualisation - ' . $page->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Barre de prévisualisation -->
    <div class="bg-yellow-50 border-b border-yellow-200 px-4 py-3">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-eye text-yellow-600 mr-2"></i>
                <span class="text-yellow-800 font-medium">Mode prévisualisation</span>
                <span class="text-yellow-600 ml-2">- Cette page n'est pas encore publiée</span>
            </div>
            
            <div class="flex items-center space-x-3">
                @if(auth()->user()->hasPermission('pages.edit'))
                <a href="{{ route('personnels.pages.pages.edit', $page) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all">
                    <i class="fas fa-edit mr-1"></i>
                    Modifier
                </a>
                @endif
                
                <a href="{{ route('personnels.pages.pages.show', $page) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition-all">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Retour admin
                </a>
            </div>
        </div>
    </div>

    <!-- Contenu de la page -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="px-8 py-6">
                <!-- Breadcrumb -->
                <nav class="flex items-center text-sm text-gray-500 mb-4">
                    <a href="{{ route('public.pages.section', $page->section->slug) }}" class="hover:text-gray-700">{{ $page->section->name }}</a>
                    @if($page->folder)
                    <i class="fas fa-chevron-right mx-2"></i>
                    <a href="{{ route('public.pages.folder', [$page->section->slug, $page->folder->slug]) }}" class="hover:text-gray-700">{{ $page->folder->name }}</a>
                    @endif
                    <i class="fas fa-chevron-right mx-2"></i>
                    <span class="text-gray-700">{{ $page->title }}</span>
                </nav>
                
                <!-- Titre principal -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                
                <!-- Métadonnées -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-2"></i>
                        Créé le {{ $page->created_at->format('d/m/Y') }}
                    </div>
                    
                    <div class="flex items-center">
                        <i class="fas fa-user mr-2"></i>
                        Par {{ $page->creator->name }}
                    </div>
                    
                    @if($page->updated_at->ne($page->created_at))
                    <div class="flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Modifié le {{ $page->updated_at->format('d/m/Y') }}
                    </div>
                    @endif
                </div>
                
                <!-- Extrait -->
                @if($page->excerpt)
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-blue-900 leading-relaxed">{{ $page->excerpt }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="px-8 py-6">
                <div class="prose max-w-none">
                    <div id="editorjs-container"></div>
                </div>
            </div>
        </div>
        
        <!-- Tags -->
        @if($page->tags && count($page->tags) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($page->tags as $tag)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                        <i class="fas fa-tag mr-1 text-xs"></i>
                        {{ $tag }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<!-- Editor.js et ses plugins -->
<!-- Editor.js et ses outils avec imports UMD corrects -->
<link href="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.27.2/dist/css/editorjs.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.27.2/dist/editor.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.7.0/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@1.8.0/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.8.1/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@2.5.0/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/code@2.8.0/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@1.4.0/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@2.5.3/dist/bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@1.3.0/dist/bundle.min.js"></script>

<script>
// Accès aux classes des outils via les objets UMD
const Header = window.EditorJSHeader;
const List = window.EditorJSList;
const ImageTool = window.EditorJSImage;
const Quote = window.EditorJSQuote;
const CodeTool = window.EditorJSCode;
const InlineCode = window.EditorJSInlineCode;
const Embed = window.EditorJSEmbed;
const Delimiter = window.EditorJSDelimiter;
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Editor.js en mode lecture seule
    let content = @json($page->content);
    let editorData;
    
    try {
        // Si le contenu est déjà au format JSON
        if (typeof content === 'string') {
            editorData = JSON.parse(content);
        } else {
            editorData = content;
        }
    } catch (e) {
        // Si ce n'est pas du JSON valide, créer un bloc de paragraphe
        console.warn('Contenu non-JSON détecté');
        editorData = {
            blocks: [{
                type: 'paragraph',
                data: { text: content }
            }]
        };
    }

    const editor = new EditorJS({
        holder: 'editorjs-container',
        tools: {
            header: Header,
            list: List,
            image: ImageTool,
            quote: Quote,
            code: CodeTool,
            inlineCode: InlineCode,
            delimiter: Delimiter,
            embed: Embed
        },
        data: editorData,
        readOnly: true
    });
});
</script>

<style>
/* Styles personnalisés pour la prose */
.prose {
    max-width: none;
}

.ce-block__content {
    max-width: none !important;
}

.ce-toolbar__content {
    max-width: none !important;
}

.prose img {
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    margin: 1.5rem 0;
}

.prose blockquote {
    border-left: 4px solid #3b82f6;
    background: #eff6ff;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    border-radius: 0.5rem;
}

.prose table {
    border-collapse: collapse;
    width: 100%;
    margin: 1.5rem 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
    overflow: hidden;
}

.prose table th,
.prose table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.prose table th {
    background: #f9fafb;
    font-weight: 600;
    text-align: left;
}

.prose h2 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.prose h3 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.prose ul li,
.prose ol li {
    margin: 0.5rem 0;
}

.prose a {
    color: #3b82f6;
    text-decoration: none;
}

.prose a:hover {
    text-decoration: underline;
}

.prose code {
    background: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}

.prose pre {
    background: #1f2937;
    color: #f9fafb;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.prose pre code {
    background: none;
    padding: 0;
    color: inherit;
}
</style>
@endsection
