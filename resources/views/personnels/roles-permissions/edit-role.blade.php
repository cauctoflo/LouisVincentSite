@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Modifier le Rôle: {{ $role->name }}</h1>
        <a href="{{ route('personnels.roles-permissions.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <form action="{{ route('personnels.roles-permissions.update-role', $role) }}" method="POST" class="max-w-4xl">
        @csrf
        @method('PUT')
        
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Informations du Rôle</h2>
            </div>
            
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom du rôle</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $role->name) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_default" 
                           id="is_default" 
                           value="1"
                           {{ old('is_default', $role->is_default) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <label for="is_default" class="ml-2 block text-sm text-gray-900">
                        Rôle par défaut pour les nouveaux utilisateurs
                    </label>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Permissions</h2>
                <p class="text-sm text-gray-600">Sélectionnez les permissions à attribuer à ce rôle</p>
            </div>
            
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($permissions as $module => $modulePermissions)
                        <div class="border rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-3 capitalize flex items-center">
                                <i class="fas fa-{{ $module === 'pages' ? 'file-alt' : ($module === 'users' ? 'users' : ($module === 'drive' ? 'folder' : 'cog')) }} mr-2"></i>
                                {{ $module }}
                            </h3>
                            
                            <div class="space-y-2">
                                @foreach($modulePermissions as $permission)
                                    <label class="flex items-center text-sm">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}"
                                               {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-gray-700">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('personnels.roles-permissions.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Annuler
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Mettre à Jour
            </button>
        </div>
    </form>
</div>
@endsection