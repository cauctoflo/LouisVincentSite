@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Créer une nouvelle section</h1>
            <p class="text-gray-500 mt-1">Organisez votre contenu en créant une nouvelle section thématique</p>
        </div>
        <a href="{{ route('personnels.pages.sections.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('personnels.pages.sections.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Formulaire principal -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom de la section <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                                   placeholder="Ex: Actualités, Documents, Ressources..."
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Le slug sera généré automatiquement à partir du nom</p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                                      placeholder="Description de la section (optionnel)">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Attribution des responsables -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Responsables de la section
                            </label>
                            <div class="space-y-3 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                @foreach(\App\Models\User::orderBy('name')->get() as $user)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="responsibles[]" 
                                               id="user_{{ $user->id }}" 
                                               value="{{ $user->id }}" 
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                               {{ in_array($user->id, old('responsibles', [])) ? 'checked' : '' }}>
                                        <label for="user_{{ $user->id }}" class="ml-3 text-sm text-gray-700">
                                            {{ $user->name }}
                                            <span class="text-gray-500">({{ $user->email }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Les responsables pourront gérer le contenu de cette section</p>
                        </div>
                    </div>

                    <!-- Panneau latéral -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Statut -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Statut</h3>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" 
                                           name="is_active" 
                                           id="active" 
                                           value="1" 
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           {{ old('is_active', '1') === '1' ? 'checked' : '' }}>
                                    <label for="active" class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">Active</span>
                                        <div class="text-gray-500">La section sera visible publiquement</div>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" 
                                           name="is_active" 
                                           id="inactive" 
                                           value="0" 
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           {{ old('is_active') === '0' ? 'checked' : '' }}>
                                    <label for="inactive" class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">Inactive</span>
                                        <div class="text-gray-500">La section ne sera pas visible publiquement</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu URL -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-blue-900 mb-2">Aperçu de l'URL</h3>
                            <div class="text-sm text-blue-700 font-mono bg-white px-3 py-2 rounded border">
                                {{ url('/pages/') }}/<span id="slug-preview">nom-de-la-section</span>
                            </div>
                            <p class="mt-2 text-xs text-blue-600">L'URL sera générée automatiquement</p>
                        </div>

                        <!-- Permissions info -->
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-yellow-900 mb-2">
                                <i class="fas fa-info-circle mr-1"></i> Permissions
                            </h3>
                            <div class="text-sm text-yellow-700 space-y-1">
                                <p>• Les responsables peuvent créer et modifier les pages</p>
                                <p>• Les administrateurs ont tous les droits</p>
                                <p>• Les utilisateurs standards peuvent voir selon les permissions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                    <div class="text-sm text-gray-500">
                        Les champs marqués d'un <span class="text-red-500">*</span> sont obligatoires
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('personnels.pages.sections.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all">
                            <i class="fas fa-save mr-2"></i> Créer la section
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugPreview = document.getElementById('slug-preview');
    
    nameInput.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, '')
            .replace(/\s+/g, '-')
            .trim();
        
        slugPreview.textContent = slug || 'nom-de-la-section';
    });
});
</script>
@endsection
