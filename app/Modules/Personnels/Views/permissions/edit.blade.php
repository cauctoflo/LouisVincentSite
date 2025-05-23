@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-display font-bold text-gray-900">Gérer les permissions: {{ $user->name }}</h1>
        <a href="{{ route('personnels.permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne d'informations utilisateur -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Informations de l'utilisateur</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                <span class="text-2xl font-semibold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            <div class="mt-2">
                                @if ($user->is_admin)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Administrateur
                                    </span>
                                    <p class="mt-2 text-xs text-gray-500">Un administrateur a accès à toutes les fonctionnalités sans restrictions.</p>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Utilisateur standard
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Rôles attribués</h2>
                </div>
                <div class="p-6">
                    @if ($user->roles->count() > 0)
                        <div class="space-y-3">
                            @foreach ($user->roles as $role)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="font-medium text-gray-900">{{ $role->name }}</div>
                                    @if ($role->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $role->description }}</p>
                                    @endif
                                    @if ($role->permissions && count($role->permissions) > 0)
                                        <div class="mt-2">
                                            <p class="text-xs font-semibold text-gray-500 mb-1">Permissions incluses:</p>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($role->permissions as $permission)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $permission }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-500">Aucun rôle n'est attribué à cet utilisateur</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Actions rapides</h2>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        <a href="{{ route('personnels.personnels.edit', $user) }}" class="flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="fas fa-user-edit mr-3"></i> Modifier le profil
                        </a>
                        
                        <a href="{{ route('personnels.Log.index', ['model_type' => 'personnel', 'model_id' => $user->id]) }}" class="flex items-center px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                            <i class="fas fa-history mr-2"></i>
                            Historique des modifications
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Colonne du formulaire de permissions -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Permissions directes</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-6">Ces permissions sont attribuées directement à l'utilisateur, en plus de celles héritées des rôles.</p>
                    
                    <form action="{{ route('personnels.permissions.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($availablePermissions as $key => $label)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="permissions[]" class="rounded border-gray-300 text-primary focus:ring-primary" id="perm_{{ $key }}" value="{{ $key }}" {{ in_array($key, old('permissions', $user->permissions ?? [])) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="perm_{{ $key }}" class="font-medium text-gray-700">{{ $label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @error('permissions')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                        
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-lg transition-all">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 