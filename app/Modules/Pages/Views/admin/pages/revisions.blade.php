@extends('layouts.admin')

@section('title', 'Historique des révisions - ' . $page->title)

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Historique des révisions</h1>
            <p class="text-gray-500 mt-1">Page : {{ $page->title }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('personnels.pages.pages.edit', $page) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-all shadow-sm">
                <i class="fas fa-edit mr-2"></i>
                Modifier la page
            </a>
            
            <a href="{{ route('personnels.pages.pages.show', $page) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la page
            </a>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Liste des révisions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Révisions</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $revisions->total() }} révision(s)</p>
                </div>
                
                <div class="max-h-96 overflow-y-auto">
                    @forelse($revisions as $revision)
                    <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer revision-item"
                         data-revision-id="{{ $revision->id }}"
                         data-revision-title="{{ $revision->title }}"
                         data-revision-content="{{ $revision->content }}"
                         data-revision-excerpt="{{ $revision->excerpt }}"
                         data-revision-created="{{ $revision->created_at->format('d/m/Y à H:i:s') }}"
                         data-revision-creator="{{ $revision->creator->name ?? 'Système' }}"
                         data-revision-type="{{ $revision->revision_type }}">
                        
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full {{ $revision->revision_type === 'auto_save' ? 'bg-blue-400' : 'bg-green-400' }} mr-2"></div>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $revision->created_at->format('d/m H:i') }}
                                    </span>
                                </div>
                                
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $revision->creator->name ?? 'Système' }}
                                </p>
                                
                                <div class="flex items-center mt-1">
                                    @if($revision->revision_type === 'auto_save')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Auto-save
                                    </span>
                                    @elseif($revision->revision_type === 'manual')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Manuel
                                    </span>
                                    @elseif($revision->revision_type === 'publish')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Publication
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if(auth()->user()->hasPermission('pages.edit'))
                            <form action="{{ route('personnels.pages.pages.restore-revision', [$page, $revision]) }}" method="POST" class="ml-2">
                                @csrf
                                <button type="submit" 
                                        class="text-blue-600 hover:text-blue-800 transition-colors text-xs p-1"
                                        onclick="return confirm('Êtes-vous sûr de vouloir restaurer cette révision ?')"
                                        title="Restaurer cette révision">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center">
                        <i class="fas fa-history text-3xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Aucune révision trouvée</p>
                    </div>
                    @endforelse
                </div>
                
                @if($revisions->hasPages())
                <div class="px-6 py-3 border-t border-gray-100">
                    {{ $revisions->links() }}
                </div>
                @endif
            </div>
        </div>
        
        <!-- Contenu de la révision sélectionnée -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Aperçu de la révision</h3>
                        <div id="revision-info" class="text-sm text-gray-500">
                            Sélectionnez une révision pour voir son contenu
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div id="revision-content">
                        <div class="text-center py-12">
                            <i class="fas fa-mouse-pointer text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Cliquez sur une révision dans la liste pour afficher son contenu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const revisionItems = document.querySelectorAll('.revision-item');
    const revisionContent = document.getElementById('revision-content');
    const revisionInfo = document.getElementById('revision-info');
    
    revisionItems.forEach(item => {
        item.addEventListener('click', function() {
            // Retirer la classe active de tous les éléments
            revisionItems.forEach(el => el.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500'));
            
            // Ajouter la classe active à l'élément cliqué
            this.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
            
            // Récupérer les données de la révision
            const title = this.dataset.revisionTitle;
            const content = this.dataset.revisionContent;
            const excerpt = this.dataset.revisionExcerpt;
            const created = this.dataset.revisionCreated;
            const creator = this.dataset.revisionCreator;
            const type = this.dataset.revisionType;
            
            // Mettre à jour les informations
            revisionInfo.innerHTML = `
                <div class="flex items-center space-x-4">
                    <span class="font-medium">${created}</span>
                    <span>par ${creator}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${getTypeClass(type)}">
                        ${getTypeLabel(type)}
                    </span>
                </div>
            `;
            
            // Mettre à jour le contenu
            revisionContent.innerHTML = `
                <div class="space-y-6">
                    <!-- Titre -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Titre</h4>
                        <p class="text-gray-800">${title}</p>
                    </div>
                    
                    ${excerpt ? `
                    <!-- Extrait -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Extrait</h4>
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-blue-800">${excerpt}</p>
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Contenu -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Contenu</h4>
                        <div class="prose max-w-none border rounded-lg p-4 bg-gray-50">
                            ${content || '<p class="text-gray-500 italic">Aucun contenu</p>'}
                        </div>
                    </div>
                </div>
            `;
        });
    });
    
    function getTypeClass(type) {
        switch(type) {
            case 'auto_save':
                return 'bg-blue-100 text-blue-800';
            case 'manual':
                return 'bg-green-100 text-green-800';
            case 'publish':
                return 'bg-purple-100 text-purple-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
    
    function getTypeLabel(type) {
        switch(type) {
            case 'auto_save':
                return 'Auto-save';
            case 'manual':
                return 'Sauvegarde manuelle';
            case 'publish':
                return 'Publication';
            default:
                return 'Inconnue';
        }
    }
    
    // Sélectionner automatiquement la première révision s'il y en a une
    if (revisionItems.length > 0) {
        revisionItems[0].click();
    }
});
</script>
@endsection
