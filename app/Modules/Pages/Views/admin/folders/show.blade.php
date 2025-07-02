@extends('layouts.admin')

@section('title', 'Détails du dossier')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Détails du dossier</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('personnels.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('personnels.pages.sections.index') }}">Sections</a></li>
        <li class="breadcrumb-item"><a href="{{ route('personnels.pages.sections.show', $folder->section) }}">{{ $folder->section->name }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('personnels.pages.folders.index') }}">Dossiers</a></li>
        <li class="breadcrumb-item active">{{ $folder->name }}</li>
    </ol>

    <div class="row">
        <div class="col-xl-8">
            <!-- Informations du dossier -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-folder me-1"></i>
                        {{ $folder->name }}
                    </div>
                    <div>
                        @can('pages_edit')
                            <a href="{{ route('personnels.pages.folders.edit', $folder) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                        @endcan
                        @can('pages_delete')
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nom :</strong> {{ $folder->name }}</p>
                            <p><strong>Slug :</strong> <code>{{ $folder->slug }}</code></p>
                            <p><strong>Section :</strong> 
                                <a href="{{ route('personnels.pages.sections.show', $folder->section) }}">
                                    {{ $folder->section->name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Statut :</strong> 
                                <span class="badge bg-{{ $folder->is_active ? 'success' : 'danger' }}">
                                    {{ $folder->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </p>
                            <p><strong>Ordre d'affichage :</strong> {{ $folder->order_index }}</p>
                            <p><strong>Nombre de pages :</strong> {{ $folder->pages->count() }}</p>
                        </div>
                    </div>
                    
                    @if($folder->description)
                        <div class="mt-3">
                            <strong>Description :</strong>
                            <p class="mt-2">{{ $folder->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pages du dossier -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-file-alt me-1"></i>
                        Pages du dossier ({{ $folder->pages->count() }})
                    </div>
                    @can('pages_create')
                        <a href="{{ route('personnels.pages.pages.create') }}?folder_id={{ $folder->id }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i> Nouvelle page
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    @if($folder->pages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Slug</th>
                                        <th>Statut</th>
                                        <th>Dernière modification</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($folder->pages as $page)
                                        <tr>
                                            <td>
                                                <strong>{{ $page->title }}</strong>
                                                @if($page->excerpt)
                                                    <br><small class="text-muted">{{ Str::limit($page->excerpt, 100) }}</small>
                                                @endif
                                            </td>
                                            <td><code>{{ $page->slug }}</code></td>
                                            <td>
                                                <span class="badge bg-{{ $page->is_published ? 'success' : 'warning' }}">
                                                    {{ $page->is_published ? 'Publié' : 'Brouillon' }}
                                                </span>
                                            </td>
                                            <td>{{ $page->updated_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @can('pages_view')
                                                        <a href="{{ route('personnels.pages.pages.show', $page) }}" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('pages_edit')
                                                        <a href="{{ route('personnels.pages.pages.edit', $page) }}" 
                                                           class="btn btn-sm btn-outline-secondary" 
                                                           title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @if($page->is_published)
                                                        <a href="{{ route('pages.show', [$folder->section->slug, $folder->slug, $page->slug]) }}" 
                                                           class="btn btn-sm btn-outline-info" 
                                                           title="Voir sur le site" 
                                                           target="_blank">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune page dans ce dossier</h5>
                            <p class="text-muted">Commencez par créer votre première page.</p>
                            @can('pages_create')
                                <a href="{{ route('personnels.pages.pages.create') }}?folder_id={{ $folder->id }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Créer une page
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Informations techniques -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informations techniques
                </div>
                <div class="card-body">
                    <p><strong>ID :</strong> {{ $folder->id }}</p>
                    <p><strong>Créé le :</strong> {{ $folder->created_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Modifié le :</strong> {{ $folder->updated_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>URL publique :</strong></p>
                    <small class="text-break">{{ url('/pages/' . $folder->section->slug . '/' . $folder->slug) }}</small>
                </div>
            </div>

            <!-- Navigation rapide -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-compass me-1"></i>
                    Navigation rapide
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('personnels.pages.sections.show', $folder->section) }}" class="btn btn-outline-primary">
                            <i class="fas fa-layer-group me-1"></i> Voir la section
                        </a>
                        <a href="{{ route('personnels.pages.folders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-folder me-1"></i> Tous les dossiers
                        </a>
                        @if($folder->is_active)
                            <a href="{{ url('/pages/' . $folder->section->slug . '/' . $folder->slug) }}" 
                               class="btn btn-outline-info" 
                               target="_blank">
                                <i class="fas fa-external-link-alt me-1"></i> Voir sur le site
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
@can('pages_delete')
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le dossier <strong>"{{ $folder->name }}"</strong> ?</p>
                @if($folder->pages->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Ce dossier contient {{ $folder->pages->count() }} page(s). 
                        Toutes les pages seront également supprimées.
                    </div>
                @endif
                <p class="text-muted">Cette action ne peut pas être annulée.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('personnels.pages.folders.destroy', $folder) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
