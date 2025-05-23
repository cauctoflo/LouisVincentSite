@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-display font-bold text-gray-900">Détails de l'activité</h1>
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
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $actionClass }}">
                    {{ ucfirst($log->action) }}
                </span>
            </div>
            <p class="text-gray-500 mt-2">{{ $log->created_at->format('d/m/Y à H:i:s') }}</p>
        </div>
        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Résumé de l'action -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900">Résumé de l'action</h2>
                </div>
                <div class="p-6">
                    @if(isset($log->details['message']))
                        <div class="mb-6">
                            <p class="text-lg text-gray-900">{{ $log->details['message'] }}</p>
                            @if(isset($log->details['description']))
                                <p class="text-gray-600 mt-2">{{ $log->details['description'] }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="border-t border-gray-100 pt-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            @if($log->user)
                            <div class="col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Utilisateur concerné</dt>
                                <dd class="mt-1 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('personnels.Log.user', $log->user) }}" class="text-sm font-medium text-primary hover:underline">
                                            {{ $log->user->name }}
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                    </div>
                                </dd>
                            </div>
                            @endif

                            <div class="col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Action effectuée par</dt>
                                <dd class="mt-1 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user-shield text-gray-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $log->actor->name ?? 'Système' }}</p>
                                        @if($log->actor)
                                            <p class="text-xs text-gray-500">{{ $log->actor->email }}</p>
                                        @endif
                                    </div>
                                </dd>
                            </div>

                            @if($log->model_type)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type d'élément</dt>
                                <dd class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-800 text-sm">
                                    {{ class_basename($log->model_type) }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID de l'élément</dt>
                                <dd class="mt-1 text-sm text-gray-900">#{{ $log->model_id }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            @if(isset($log->details['details']) || isset($log->details['modifications']))
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900">Détails des modifications</h2>
                </div>
                <div class="p-6">
                    @if(isset($log->details['details']))
                        @foreach($log->details['details'] as $key => $value)
                            <div class="mb-6 last:mb-0">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</h3>
                                @if(is_array($value))
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        @foreach($value as $subKey => $subValue)
                                            <div class="mb-2 last:mb-0">
                                                <span class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $subKey)) }}:</span>
                                                @if(is_array($subValue))
                                                    <ul class="mt-1 space-y-1">
                                                        @foreach($subValue as $item)
                                                            <li class="text-sm text-gray-900">• {{ is_array($item) ? json_encode($item) : $item }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-sm text-gray-900 ml-2">{{ $subValue }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-900">{{ $value }}</p>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    @if(isset($log->details['modifications']))
                        <div class="space-y-6">
                            @foreach($log->details['modifications'] as $field => $changes)
                                <div class="border-t border-gray-100 pt-4 first:border-0 first:pt-0">
                                    <h3 class="text-sm font-medium text-gray-900 mb-3">{{ ucfirst(str_replace('_', ' ', $field)) }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="bg-red-50 rounded-lg p-4">
                                            <p class="text-xs font-medium text-red-800 mb-2">Avant</p>
                                            @if(is_array($changes['avant']))
                                                <ul class="space-y-1">
                                                    @foreach($changes['avant'] as $item)
                                                        <li class="text-sm text-red-900">• {{ $item }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-sm text-red-900">{{ $changes['avant'] }}</p>
                                            @endif
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-4">
                                            <p class="text-xs font-medium text-green-800 mb-2">Après</p>
                                            @if(is_array($changes['après']))
                                                <ul class="space-y-1">
                                                    @foreach($changes['après'] as $item)
                                                        <li class="text-sm text-green-900">• {{ $item }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-sm text-green-900">{{ $changes['après'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif

            @if(isset($log->details['technical_details']))
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900">Détails techniques</h2>
                </div>
                <div class="p-6">
                    <pre class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 overflow-x-auto">{{ json_encode($log->details['technical_details'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
            @endif
        </div>

        <!-- Informations système -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900">Informations système</h2>
                </div>
                <div class="p-6">
                    <dl class="space-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Adresse IP</dt>
                            <dd class="mt-1">
                                <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2">
                                    <i class="fas fa-network-wired text-gray-400"></i>
                                    <span class="text-sm font-mono text-gray-900">{{ $log->ip_address ?? 'N/A' }}</span>
                                </div>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Navigateur</dt>
                            <dd class="mt-1">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-browser text-gray-400"></i>
                                        <span class="text-sm font-medium text-gray-900">Agent utilisateur</span>
                                    </div>
                                    <p class="text-xs text-gray-600 font-mono break-all">{{ $log->user_agent ?? 'N/A' }}</p>
                                </div>
                            </dd>
                        </div>

                        @if(auth()->check() && auth()->user()->hasPermission('personnels.Log.delete'))
                        <div class="pt-6 border-t border-gray-200">
                            <form action="{{ route('personnels.Log.destroy', $log) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-50 text-red-700 rounded-lg font-medium hover:bg-red-100 transition-colors" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée de journal ?')">
                                    <i class="fas fa-trash mr-2"></i> Supprimer cette entrée
                                </button>
                            </form>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 