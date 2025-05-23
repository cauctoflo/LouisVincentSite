@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-display font-bold text-gray-900">Profil de {{ $personnel->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('personnels.personnels.edit', $personnel) }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-lg transition-all">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('personnels.personnels.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Carte profil -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Informations du compte</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-2xl">
                                {{ substr($personnel->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-6 space-y-4 flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-500">Nom complet</h3>
                                    <p class="text-gray-900 font-medium">{{ $personnel->name }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-500">Email</h3>
                                    <p class="text-gray-900">{{ $personnel->email }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500">Description</h3>
                                <p class="text-gray-700">{{ $personnel->description ?: 'Aucune description disponible' }}</p>
                            </div>
                            
                            <div class="flex flex-wrap gap-2 pt-2">
                                @if ($personnel->is_admin)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Administrateur
                                    </span>
                                @endif
                                
                                @if ($personnel->roles->count() > 0)
                                    @foreach ($personnel->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rôles et permissions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Rôles et Permissions</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Rôles -->
                    <div>
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Rôles assignés</h3>
                        
                        @if ($personnel->roles->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($personnel->roles as $role)
                                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                        <h4 class="font-medium text-gray-900">{{ $role->name }}</h4>
                                        @if ($role->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $role->description }}</p>
                                        @endif
                                        @if ($role->permissions && count($role->permissions) > 0)
                                            <div class="mt-3">
                                                <h5 class="text-xs font-semibold text-gray-500 mb-2">Permissions incluses:</h5>
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
                            <div class="text-center py-6 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Aucun rôle assigné</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Permissions spécifiques -->
                    <div>
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Permissions spécifiques</h3>
                        
                        @if ($personnel->permissions && count($personnel->permissions) > 0)
                            <div class="bg-gray-50 rounded-lg border border-gray-100 p-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($personnel->permissions as $permission)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $permission }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Aucune permission spécifique</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations secondaires -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Informations générales</h2>
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
                    <h2 class="text-lg font-bold text-gray-900">Actions</h2>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        <a href="{{ route('personnels.Log.index', ['model_type' => 'personnel', 'model_id' => $personnel->id]) }}" class="flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="fas fa-history mr-2"></i>
                            Historique des modifications
                        </a>
                        
                        <a href="{{ route('personnels.personnels.edit', $personnel) }}" class="flex items-center px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                            <i class="fas fa-edit mr-3"></i> Modifier le profil
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
                    <h2 class="text-lg font-bold text-gray-900">Dernières activités</h2>
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