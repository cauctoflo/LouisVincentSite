@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">{{ $page->title }}</h1>
            <p class="text-gray-500 mt-1">{{ $page->section->name }}@if($page->folder) > {{ $page->folder->name }}@endif</p>
        </div>
        
        <div class="flex items-center gap-3">
            @if(auth()->user()->hasPermission('pages.edit'))
            <a href="{{ route('personnels.pages.pages.edit', $page) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-all shadow-sm">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            @endif
            
            <a href="{{ route('personnels.pages.pages.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenu principal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Contenu de la page</h2>
                        
                        <div class="flex items-center gap-2">
                            @if($page->is_published)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Publié
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>
                                Brouillon
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($page->excerpt)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h3 class="text-sm font-semibold text-blue-900 mb-2">Extrait</h3>
                        <p class="text-blue-800">{{ $page->excerpt }}</p>
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
            
            @if($page->tags && count($page->tags) > 0)
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($page->tags as $tag)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                        <i class="fas fa-tag mr-1 text-xs"></i>
                        {{ $tag }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Sidebar avec informations -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Informations</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Créé par</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->creator->name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->updated_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    
                    @if($page->published_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de publication</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $page->published_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">URL</dt>
                        <dd class="mt-1 text-sm text-gray-900 break-all">
                            <code class="px-2 py-1 bg-gray-100 rounded text-xs">
                                /sections/{{ $page->section->slug }}@if($page->folder)/{{ $page->folder->slug }}@endif/{{ $page->slug }}
                            </code>
                        </dd>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                
                <div class="p-6 space-y-3">
                    @if(auth()->user()->hasPermission('pages.view'))
                    <a href="{{ route('public.pages.show', ['section' => $page->section->slug, 'page' => $page->slug]) }}" 
                       target="_blank"
                       class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Voir la page publique
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasPermission('pages.edit'))
                    <a href="{{ route('personnels.pages.pages.preview', $page) }}" 
                       target="_blank"
                       class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Prévisualiser
                    </a>
                    
                    <a href="{{ route('personnels.pages.pages.revisions', $page) }}" 
                       class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-history mr-2"></i>
                        Historique des révisions
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasPermission('pages.publish'))
                        @if($page->is_published)
                        <form action="{{ route('personnels.pages.pages.unpublish', $page) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-4 py-2 text-sm font-medium text-orange-700 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors"
                                    onclick="return confirm('Êtes-vous sûr de vouloir dépublier cette page ?')">
                                <i class="fas fa-times mr-2"></i>
                                Dépublier
                            </button>
                        </form>
                        @else
                        <form action="{{ route('personnels.pages.pages.publish', $page) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-4 py-2 text-sm font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <i class="fas fa-check mr-2"></i>
                                Publier
                            </button>
                        </form>
                        @endif
                    @endif
                    
                    @if(auth()->user()->hasPermission('pages.delete'))
                    <form action="{{ route('personnels.pages.pages.destroy', $page) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette page ? Cette action est irréversible.')">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
