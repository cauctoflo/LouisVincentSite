@extends('layouts.admin')

@section('title', 'Modifier le dossier')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Modifier le dossier</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('personnels.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('personnels.pages.sections.index') }}">Sections</a></li>
        <li class="breadcrumb-item"><a href="{{ route('personnels.pages.sections.show', $folder->section) }}">{{ $folder->section->name }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('personnels.pages.folders.index') }}">Dossiers</a></li>
        <li class="breadcrumb-item active">Modifier "{{ $folder->name }}"</li>
    </ol>

    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    Modifier le dossier "{{ $folder->name }}"
                </div>
                <div class="card-body">
                    <form action="{{ route('personnels.pages.folders.update', $folder) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           id="inputName" 
                                           type="text" 
                                           name="name" 
                                           value="{{ old('name', $folder->name) }}" 
                                           required />
                                    <label for="inputName">Nom du dossier</label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control @error('slug') is-invalid @enderror" 
                                           id="inputSlug" 
                                           type="text" 
                                           name="slug" 
                                           value="{{ old('slug', $folder->slug) }}" 
                                           required />
                                    <label for="inputSlug">Slug (URL)</label>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select @error('section_id') is-invalid @enderror" 
                                        id="inputSection" 
                                        name="section_id" 
                                        required>
                                    <option value="">Choisir une section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" 
                                                @if(old('section_id', $folder->section_id) == $section->id) selected @endif>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="inputSection">Section</label>
                                @error('section_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="inputDescription" 
                                          name="description" 
                                          style="height: 100px">{{ old('description', $folder->description) }}</textarea>
                                <label for="inputDescription">Description (optionnelle)</label>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <input class="form-control @error('order_index') is-invalid @enderror" 
                                       id="inputOrderIndex" 
                                       type="number" 
                                       name="order_index" 
                                       value="{{ old('order_index', $folder->order_index) }}" 
                                       min="0" />
                                <label for="inputOrderIndex">Ordre d'affichage</label>
                                @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_active" 
                                   id="inputIsActive" 
                                   value="1" 
                                   @if(old('is_active', $folder->is_active)) checked @endif />
                            <label class="form-check-label" for="inputIsActive">
                                Dossier actif
                            </label>
                        </div>

                        <div class="mt-4 mb-0">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('personnels.pages.folders.show', $folder) }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </a>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-save me-1"></i> Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informations
                </div>
                <div class="card-body">
                    <p><strong>Créé le :</strong> {{ $folder->created_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Modifié le :</strong> {{ $folder->updated_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Section :</strong> {{ $folder->section->name }}</p>
                    <p><strong>Nombre de pages :</strong> {{ $folder->pages->count() }}</p>
                </div>
            </div>

            @if($folder->pages->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-file-alt me-1"></i>
                    Pages dans ce dossier
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($folder->pages as $page)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-1">{{ $page->title }}</h6>
                                    <small class="text-muted">{{ $page->slug }}</small>
                                    <span class="badge bg-{{ $page->is_published ? 'success' : 'warning' }} ms-2">
                                        {{ $page->is_published ? 'Publié' : 'Brouillon' }}
                                    </span>
                                </div>
                                <a href="{{ route('personnels.pages.pages.show', $page) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-générer le slug à partir du nom
    document.getElementById('inputName').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        document.getElementById('inputSlug').value = slug;
    });
</script>
@endpush
@endsection
