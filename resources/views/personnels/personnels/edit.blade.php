@extends('layouts.admin')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div class="py-8 px-6 max-w-[1400px] mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Modifier l'utilisateur</h1>
            <p class="text-sm text-gray-500">{{ $personnel->name }} • {{ $personnel->email }}</p>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('personnels.personnels.show', $personnel) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-eye mr-2"></i>
                Voir le profil
            </a>
            <a href="{{ route('personnels.personnels.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="font-medium mb-2">Erreurs détectées :</div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('personnels.personnels.update', $personnel) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Informations personnelles
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom complet *
                            </label>
                            <input type="text" id="name" name="name" 
                                   value="{{ old('name', $personnel->name) }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse email *
                            </label>
                            <input type="email" id="email" name="email" 
                                   value="{{ old('email', $personnel->email) }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   required>
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nouveau mot de passe
                            </label>
                            <input type="password" id="password" name="password" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Laissez vide pour conserver le mot de passe actuel</p>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmer le mot de passe
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description / Notes
                        </label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $personnel->description) }}</textarea>
                    </div>

                    <!-- Admin toggle -->
                    <div class="mt-6 flex items-center">
                        @if(auth()->id() === $personnel->id)
                            <!-- L'utilisateur ne peut pas modifier son propre statut admin -->
                            <div class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <i class="fas fa-lock text-yellow-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-yellow-800">Restriction de sécurité</div>
                                    <div class="text-xs text-yellow-600 mt-1">Vous ne pouvez pas modifier votre propre statut d'administrateur</div>
                                </div>
                                @if($personnel->is_admin)
                                    <div class="ml-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-crown mr-1"></i>Administrateur
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_admin" class="sr-only peer" {{ $personnel->is_admin ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900">
                                    Administrateur système
                                    <span class="block text-xs text-gray-500">Accès complet à toutes les fonctionnalités</span>
                                </span>
                            </label>
                        @endif
                    </div>
                </div>

                <!-- Rôles -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-user-tag text-purple-600 mr-2"></i>
                        Rôles assignés
                        @if($personnel->is_admin)
                            <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Admin = Tous droits</span>
                        @endif
                    </h2>
                    
                    @if($personnel->is_admin)
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-crown text-red-600 mr-2"></i>
                                <div class="text-sm">
                                    <div class="font-medium text-red-800">Utilisateur Administrateur</div>
                                    <div class="text-red-600 text-xs mt-1">
                                        Les administrateurs ont automatiquement accès à toutes les fonctionnalités du système, 
                                        indépendamment des rôles et permissions assignés.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($roles->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($roles as $role)
                                @php
                                    $isDisabled = $personnel->is_admin || (auth()->id() === $personnel->id);
                                    $isChecked = in_array($role->id, $userRoleIds);
                                @endphp
                                <label class="relative flex items-start p-4 rounded-lg border border-gray-200 {{ $isDisabled ? 'bg-gray-50 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer' }} transition-colors">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                           {{ $isChecked ? 'checked' : '' }}
                                           {{ $isDisabled ? 'disabled' : '' }}
                                           class="mt-1 h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 {{ $isDisabled ? 'opacity-50' : '' }}">
                                    <div class="ml-3 min-w-0 flex-1">
                                        <div class="text-sm font-medium {{ $isDisabled ? 'text-gray-500' : 'text-gray-900' }}">
                                            {{ $role->name }}
                                            @if($isDisabled && $personnel->is_admin)
                                                <i class="fas fa-crown text-red-400 ml-1" title="Admin a tous les droits"></i>
                                            @endif
                                        </div>
                                        @if($role->description)
                                            <div class="text-xs {{ $isDisabled ? 'text-gray-400' : 'text-gray-500' }} mt-1">{{ $role->description }}</div>
                                        @endif
                                        <div class="text-xs {{ $isDisabled ? 'text-gray-400' : 'text-blue-600' }} mt-1">{{ $role->permissions ? $role->permissions->count() : 0 }} permission(s)</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        
                        @if(auth()->id() === $personnel->id && !$personnel->is_admin)
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    <div class="text-sm text-blue-700">
                                        <span class="font-medium">Note :</span> Vous ne pouvez pas modifier vos propres rôles pour des raisons de sécurité.
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-user-tag text-3xl mb-2"></i>
                            <p>Aucun rôle disponible</p>
                        </div>
                    @endif
                </div>

                <!-- Permissions directes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-key text-green-600 mr-2"></i>
                        Permissions directes
                        <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Optionnel</span>
                        @if($personnel->is_admin)
                            <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Admin = Toutes permissions</span>
                        @endif
                    </h2>
                    
                    @if($personnel->is_admin)
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-crown text-red-600 mr-2"></i>
                                <div class="text-sm">
                                    <div class="font-medium text-red-800">Administrateur - Toutes permissions accordées</div>
                                    <div class="text-red-600 text-xs mt-1">
                                        En tant qu'administrateur, cet utilisateur a automatiquement accès à toutes les permissions disponibles.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-sm text-gray-600 mb-6 bg-blue-50 p-3 rounded-lg">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                            Les permissions directes s'ajoutent aux permissions des rôles assignés. Utilisez-les pour des cas spéciaux.
                        </div>
                    @endif

                    @if($permissions->count() > 0)
                        <div class="space-y-6">
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-cube text-gray-400 mr-2"></i>
                                        Module : {{ $module }}
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($modulePermissions as $permission)
                                            @php
                                                $isDisabled = $personnel->is_admin || (auth()->id() === $personnel->id);
                                                $isChecked = in_array($permission->id, $userPermissionIds);
                                            @endphp
                                            <label class="flex items-start p-3 rounded border border-gray-100 {{ $isDisabled ? 'bg-gray-50 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer' }} transition-colors">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                       {{ $isChecked ? 'checked' : '' }}
                                                       {{ $isDisabled ? 'disabled' : '' }}
                                                       class="mt-0.5 h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500 {{ $isDisabled ? 'opacity-50' : '' }}">
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium {{ $isDisabled ? 'text-gray-500' : 'text-gray-900' }}">
                                                        {{ $permission->display_name }}
                                                        @if($isDisabled && $personnel->is_admin)
                                                            <i class="fas fa-crown text-red-400 ml-1" title="Admin a toutes les permissions"></i>
                                                        @endif
                                                    </div>
                                                    @if($permission->description)
                                                        <div class="text-xs {{ $isDisabled ? 'text-gray-400' : 'text-gray-500' }} mt-1">{{ $permission->description }}</div>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-key text-3xl mb-2"></i>
                            <p>Aucune permission disponible</p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour
                    </button>
                    
                    <a href="{{ route('personnels.personnels.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                </div>
            </div>

            <!-- Sidebar - Logs et statistiques -->
            <div class="space-y-6">
                <!-- Informations utilisateur -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">ID utilisateur :</span>
                            <span class="font-medium">#{{ $personnel->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Créé le :</span>
                            <span class="font-medium">{{ $personnel->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Dernière modification :</span>
                            <span class="font-medium">{{ $personnel->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Statut :</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $personnel->is_admin ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $personnel->is_admin ? 'Administrateur' : 'Utilisateur' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Logs récents -->
                @if($logs->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-history text-gray-400 mr-2"></i>
                            Activité récente
                        </h3>
                        
                        <div class="space-y-3">
                            @foreach($logs as $log)
                                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900 font-medium">{{ $log->action }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('personnels.personnels.show', $personnel) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Voir tous les logs →
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save indicator
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Visual feedback que des changements ont été apportés
            const submitBtn = form.querySelector('button[type="submit"]');
            if (!submitBtn.classList.contains('bg-orange-600')) {
                submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                submitBtn.classList.add('bg-orange-600', 'hover:bg-orange-700');
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Sauvegarder les modifications';
            }
        });
    });
});
</script>
@endsection