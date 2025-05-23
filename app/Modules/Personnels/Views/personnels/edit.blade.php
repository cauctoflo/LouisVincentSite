@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-display font-bold text-gray-900">Modifier le personnel: {{ $personnel->name }}</h1>
        <a href="{{ route('personnels.personnels.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale pour le formulaire -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations utilisateur -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Informations de base</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('personnels.personnels.update', $personnel) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('name') border-red-300 @enderror" value="{{ old('name', $personnel->name) }}" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('email') border-red-300 @enderror" value="{{ old('email', $personnel->email) }}" required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                <input type="password" name="password" id="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('password') border-red-300 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Laissez vide pour conserver le mot de passe actuel.</p>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            </div>
                            
                            <div class="space-y-2 md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('description') border-red-300 @enderror" rows="3">{{ old('description', $personnel->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Accès et permissions -->
                        <h3 class="text-md font-semibold text-gray-900 mb-4">Accès et permissions</h3>
                        
                        <div class="border rounded-lg p-4 bg-gray-50 mb-8">
                            <div class="mb-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="is_admin" id="is_admin" class="rounded border-gray-300 text-primary focus:ring-primary" value="1" {{ (old('is_admin', $personnel->is_admin)) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_admin" class="font-medium text-gray-700">Est administrateur</label>
                                        <p class="text-gray-500">Les administrateurs ont accès à toutes les fonctionnalités sans restrictions.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Attribution de rôles -->
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Rôles</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($roles as $role)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}" class="rounded border-gray-300 text-primary focus:ring-primary" 
                                                    {{ (old('roles') && in_array($role->id, old('roles'))) || 
                                                       (old('roles') === null && in_array($role->id, $userRoleIds)) ? 
                                                       'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="role_{{ $role->id }}" class="font-medium text-gray-700">{{ $role->name }}</label>
                                                @if($role->description)
                                                    <p class="text-gray-500">{{ $role->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Attribution de permissions spécifiques -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Permissions spécifiques</h4>
                                <p class="text-xs text-gray-500 mb-3">Les permissions ci-dessous seront attribuées en plus de celles des rôles.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($permissions as $key => $label)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" name="permissions[]" id="perm_{{ $key }}" value="{{ $key }}" class="rounded border-gray-300 text-primary focus:ring-primary" 
                                                    {{ (old('permissions') && in_array($key, old('permissions'))) || 
                                                       (old('permissions') === null && $personnel->permissions && in_array($key, $personnel->permissions)) ? 
                                                       'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="perm_{{ $key }}" class="font-medium text-gray-700">{{ $label }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-lg transition-all">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Informations sur l'utilisateur -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Informations</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500">Date de création</h3>
                        <p class="text-gray-700">{{ $personnel->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500">Dernière modification</h3>
                        <p class="text-gray-700">{{ $personnel->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @if($personnel->email_verified_at)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500">Email vérifié le</h3>
                        <p class="text-gray-700">{{ $personnel->email_verified_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Actions rapides</h2>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        <a href="{{ route('personnels.Log.index', ['model_type' => 'personnel', 'model_id' => $personnel->id]) }}" class="flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="fas fa-history mr-2"></i>
                            Historique des modifications
                        </a>
                        
                        @if(!$personnel->is_admin)
                        <a href="{{ route('personnels.permissions.edit', $personnel) }}" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                            <i class="fas fa-key mr-3"></i> Gérer les permissions
                        </a>
                        @endif
                        
                        <form action="{{ route('personnels.personnels.destroy', $personnel) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur? Cette action est irréversible.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fas fa-trash-alt mr-3"></i> Supprimer l'utilisateur
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Dernières activités -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Historique récent</h2>
                    <a href="{{ route('personnels.Log.index', ['model_type' => 'personnel', 'model_id' => $personnel->id]) }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                        Voir l'historique complet
                    </a>
                </div>
                <div class="p-4">
                    @if($logs->count() > 0)
                        <div class="space-y-3">
                            @foreach($logs as $log)
                                <div class="border-l-4 pl-4 py-1 {{ $log->action == 'create' ? 'border-green-500' : ($log->action == 'update' ? 'border-blue-500' : ($log->action == 'delete' ? 'border-red-500' : 'border-gray-500')) }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $log->description }}</p>
                                            <p class="text-xs text-gray-500">
                                                Par {{ $log->actor->name ?? 'Système' }} • {{ $log->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <a href="{{ route('personnels.Log.show', $log) }}" class="text-xs text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-500">Aucune activité enregistrée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 