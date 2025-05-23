@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Journal d'activité système</h1>
            <p class="text-gray-500 mt-1">Consultez et gérez les logs de toutes les activités du système</p>
        </div>
        @if(auth()->check() && auth()->user()->hasPermission('personnels.Log.export'))
        <a href="{{ route('personnels.Log.export') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-dark transition-all">
            <i class="fas fa-download mr-2"></i> Exporter
        </a>
        @endif
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form action="{{ route('personnels.Log.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="space-y-2">
                <label for="model_type" class="block text-sm font-medium text-gray-700">Type</label>
                <select name="model_type" id="model_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Tous les types</option>
                    @foreach($modelTypes as $type)
                        <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                            {{ class_basename($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Utilisateur concerné</label>
                <select name="user_id" id="user_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Tous les utilisateurs</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label for="actor_id" class="block text-sm font-medium text-gray-700">Effectué par</label>
                <select name="actor_id" id="actor_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Tous les acteurs</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('actor_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="space-y-2">
                <label for="action" class="block text-sm font-medium text-gray-700">Action</label>
                <select name="action" id="action" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">Toutes les actions</option>
                    @foreach($actions as $key => $value)
                        <option value="{{ $key }}" {{ request('action') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="space-y-2">
                <label for="date_start" class="block text-sm font-medium text-gray-700">Date de début</label>
                <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>
            
            <div class="space-y-2">
                <label for="date_end" class="block text-sm font-medium text-gray-700">Date de fin</label>
                <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>
            
            <div class="col-span-1 md:col-span-3 lg:col-span-6 flex justify-end space-x-3">
                <a href="{{ route('personnels.Log.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                    <i class="fas fa-redo mr-2"></i> Réinitialiser
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-dark transition-colors">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Par</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $log->model_type ? class_basename($log->model_type) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $actionClasses = [
                                        'create' => 'bg-green-100 text-green-800',
                                        'update' => 'bg-blue-100 text-blue-800',
                                        'delete' => 'bg-red-100 text-red-800',
                                        'login' => 'bg-purple-100 text-purple-800',
                                        'logout' => 'bg-gray-100 text-gray-800',
                                        'role_change' => 'bg-yellow-100 text-yellow-800',
                                        'permission_change' => 'bg-indigo-100 text-indigo-800',
                                    ];
                                    $actionClass = $actionClasses[$log->action] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $actionClass }}">
                                    {{ $actions[$log->action] ?? ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if($log->user)
                                    <a href="{{ route('personnels.Log.user', $log->user) }}" class="hover:text-primary hover:underline">
                                        {{ $log->user->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $log->actor->name ?? 'Système' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('personnels.Log.show', $log) }}" class="text-primary hover:text-primary-dark transition-colors">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                
                                @if(auth()->check() && auth()->user()->hasPermission('personnels.Log.delete'))
                                <form action="{{ route('personnels.Log.destroy', $log) }}" method="POST" class="inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée de journal?')" 
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 mb-4">
                                        <i class="fas fa-history text-2xl"></i>
                                    </div>
                                    <span class="text-gray-500 font-medium">Aucune activité trouvée</span>
                                    <p class="text-gray-400 mt-1">Ajustez vos filtres ou réessayez plus tard</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($logs->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 