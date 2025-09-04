@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Gestion des Rôles et Permissions</h1>
        <a href="{{ route('personnels.roles-permissions.create-role') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Créer un Rôle
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($roles as $role)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $role->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $role->description }}</p>
                            
                            <div class="mt-2">
                                <span class="text-xs text-gray-600">Permissions: </span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @if($role->permissions && $role->permissions->count() > 0)
                                        @foreach($role->permissions as $permission)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-gray-400">Aucune permission</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-2">
                                <span class="text-xs text-gray-600">Utilisateurs: {{ $role->users ? $role->users->count() : 0 }}</span>
                                @if($role->is_default)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Rôle par défaut
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('personnels.roles-permissions.edit-role', $role) }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            @if($role->users->count() == 0)
                                <form action="{{ route('personnels.roles-permissions.delete-role', $role) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-4 text-center text-gray-500">
                    Aucun rôle défini
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Permissions par Module</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($permissions as $module => $modulePermissions)
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="font-medium text-gray-900 mb-2 capitalize">{{ $module }}</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        @foreach($modulePermissions as $permission)
                            <li>• {{ $permission->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection