@extends('layouts.admin')

@section('title', 'Gestionnaire de Pages')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestionnaire de Pages</h1>
        <div class="btn-group" role="group">
            @can('pages_create')
                <a href="{{ route('personnels.pages.sections.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fa-sm me-1"></i> Nouvelle section
                </a>
                <a href="{{ route('personnels.pages.folders.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-folder-plus fa-sm me-1"></i> Nouveau dossier
                </a>
                <a href="{{ route('personnels.pages.pages.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-plus fa-sm me-1"></i> Nouvelle page
                </a>
            @endcan
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sections</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sections'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Dossiers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['folders'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pages publiées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['published_pages'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Brouillons</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['draft_pages'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Navigation hiérarchique -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-sitemap me-2"></i>Structure des pages
                    </h6>
                    <div class="dropdown no-arrow">
                        <button class="btn btn-sm btn-outline-primary" id="expandAll">
                            <i class="fas fa-expand-alt me-1"></i> Tout développer
                        </button>
                        <button class="btn btn-sm btn-outline-secondary ms-1" id="collapseAll">
                            <i class="fas fa-compress-alt me-1"></i> Tout réduire
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Barre de recherche -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Rechercher une section, dossier ou page...">
                            <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Structure hiérarchique -->
                    <div id="hierarchyContainer">
                        @if($sections->count() > 0)
                            @foreach($sections as $section)
                                <div class="section-item mb-3" data-section-id="{{ $section->id }}">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-sm btn-link p-0 me-2 section-toggle" data-target="#section-{{ $section->id }}">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                            <i class="fas fa-layer-group text-primary me-2"></i>
                                            <strong>{{ $section->name }}</strong>
                                            <span class="badge bg-secondary ms-2">{{ $section->folders->count() }} dossiers</span>
                                        </div>
                                        <div class="btn-group" role="group">
                                            @can('pages_view')
                                                <a href="{{ route('personnels.pages.sections.show', $section) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('pages_edit')
                                                <a href="{{ route('personnels.pages.sections.edit', $section) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    
                                    <div class="collapse ms-4" id="section-{{ $section->id }}">
                                        @if($section->folders->count() > 0)
                                            @foreach($section->folders as $folder)
                                                <div class="folder-item my-2">
                                                    <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                                        <div class="d-flex align-items-center">
                                                            <button class="btn btn-sm btn-link p-0 me-2 folder-toggle" data-target="#folder-{{ $folder->id }}">
                                                                <i class="fas fa-chevron-right"></i>
                                                            </button>
                                                            <i class="fas fa-folder text-warning me-2"></i>
                                                            {{ $folder->name }}
                                                            <span class="badge bg-info ms-2">{{ $folder->pages->count() }} pages</span>
                                                        </div>
                                                        <div class="btn-group" role="group">
                                                            @can('pages_view')
                                                                <a href="{{ route('personnels.pages.folders.show', $folder) }}" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @endcan
                                                            @can('pages_edit')
                                                                <a href="{{ route('personnels.pages.folders.edit', $folder) }}" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="collapse ms-4" id="folder-{{ $folder->id }}">
                                                        @if($folder->pages->count() > 0)
                                                            @foreach($folder->pages as $page)
                                                                <div class="page-item my-1">
                                                                    <div class="d-flex justify-content-between align-items-center p-2 border-start border-2 border-success ps-3">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="fas fa-file-alt text-success me-2"></i>
                                                                            {{ $page->title }}
                                                                            <span class="badge bg-{{ $page->is_published ? 'success' : 'warning' }} ms-2">
                                                                                {{ $page->is_published ? 'Publié' : 'Brouillon' }}
                                                                            </span>
                                                                        </div>
                                                                        <div class="btn-group" role="group">
                                                                            @can('pages_view')
                                                                                <a href="{{ route('personnels.pages.pages.show', $page) }}" class="btn btn-sm btn-outline-primary">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </a>
                                                                            @endcan
                                                                            @can('pages_edit')
                                                                                <a href="{{ route('personnels.pages.pages.edit', $page) }}" class="btn btn-sm btn-outline-secondary">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </a>
                                                                            @endcan
                                                                            @if($page->is_published)
                                                                                <a href="{{ route('pages.show', [$section->slug, $folder->slug, $page->slug]) }}" 
                                                                                   class="btn btn-sm btn-outline-info" 
                                                                                   target="_blank" 
                                                                                   title="Voir sur le site">
                                                                                    <i class="fas fa-external-link-alt"></i>
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="text-muted text-center py-2">
                                                                <i class="fas fa-file-alt"></i> Aucune page
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-muted text-center py-2">
                                                <i class="fas fa-folder"></i> Aucun dossier
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-layer-group fa-3x text-gray-300 mb-3"></i>
                                <h5 class="text-muted">Aucune section créée</h5>
                                <p class="text-muted">Commencez par créer votre première section.</p>
                                @can('pages_create')
                                    <a href="{{ route('personnels.pages.sections.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Créer une section
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar avec pages récentes et actions rapides -->
        <div class="col-lg-4">
            <!-- Actions rapides -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('pages_create')
                            <a href="{{ route('personnels.pages.sections.create') }}" class="btn btn-primary">
                                <i class="fas fa-layer-group me-1"></i> Créer une section
                            </a>
                            <a href="{{ route('personnels.pages.folders.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-folder-plus me-1"></i> Créer un dossier
                            </a>
                            <a href="{{ route('personnels.pages.pages.create') }}" class="btn btn-success">
                                <i class="fas fa-file-plus me-1"></i> Créer une page
                            </a>
                        @endcan
                        <hr>
                        <a href="{{ route('personnels.pages.sections.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-1"></i> Toutes les sections
                        </a>
                        <a href="{{ route('personnels.pages.folders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-folder me-1"></i> Tous les dossiers
                        </a>
                        <a href="{{ route('personnels.pages.pages.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-file-alt me-1"></i> Toutes les pages
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pages récentes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pages récemment modifiées</h6>
                </div>
                <div class="card-body">
                    @if($recentPages->count() > 0)
                        @foreach($recentPages as $page)
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <div class="font-weight-bold">{{ Str::limit($page->title, 30) }}</div>
                                    <small class="text-muted">
                                        {{ $page->folder->section->name }} > {{ $page->folder->name }}
                                    </small>
                                    <br>
                                    <small class="text-muted">{{ $page->updated_at->diffForHumans() }}</small>
                                </div>
                                <div>
                                    <a href="{{ route('personnels.pages.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-file-alt"></i>
                            <p>Aucune page récente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des toggles pour sections
    document.querySelectorAll('.section-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.target);
            const icon = this.querySelector('i');
            
            if (target.classList.contains('show')) {
                target.classList.remove('show');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            } else {
                target.classList.add('show');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            }
        });
    });

    // Gestion des toggles pour dossiers
    document.querySelectorAll('.folder-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.target);
            const icon = this.querySelector('i');
            
            if (target.classList.contains('show')) {
                target.classList.remove('show');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            } else {
                target.classList.add('show');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            }
        });
    });

    // Développer tout
    document.getElementById('expandAll').addEventListener('click', function() {
        document.querySelectorAll('.collapse').forEach(collapse => {
            collapse.classList.add('show');
        });
        document.querySelectorAll('.fa-chevron-right').forEach(icon => {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        });
    });

    // Réduire tout
    document.getElementById('collapseAll').addEventListener('click', function() {
        document.querySelectorAll('.collapse').forEach(collapse => {
            collapse.classList.remove('show');
        });
        document.querySelectorAll('.fa-chevron-down').forEach(icon => {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        });
    });

    // Recherche
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.toLowerCase();
        
        searchTimeout = setTimeout(() => {
            if (query.length >= 2) {
                // Filtrer les éléments visibles
                document.querySelectorAll('.section-item, .folder-item, .page-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(query)) {
                        item.style.display = 'block';
                        // Développer les parents si nécessaire
                        const parent = item.closest('.collapse');
                        if (parent) {
                            parent.classList.add('show');
                        }
                    } else {
                        item.style.display = 'none';
                    }
                });
            } else {
                // Réafficher tous les éléments
                document.querySelectorAll('.section-item, .folder-item, .page-item').forEach(item => {
                    item.style.display = 'block';
                });
            }
        }, 300);
    });
});
</script>
@endpush
@endsection
