@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Gestion des sections</h1>
            <p class="text-gray-500 mt-1">Organisez votre contenu en sections thématiques</p>
        </div>
        @if(auth()->user()->hasPermission('sections.create'))
        <a href="{{ route('personnels.pages.sections.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all shadow-sm">
            <i class="fas fa-plus mr-2"></i> Créer une section
        </a>
        @endif
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md animate__animated animate__fadeIn">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                <div class="ml-auto">
                    <button type="button" class="text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" 
                           placeholder="Rechercher une section...">
                </div>
            </div>
            <div>
                <select name="status" class="block w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actives</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactives</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="{{ route('personnels.pages.sections.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-all">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des sections -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsables</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créée le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($sections as $section)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-start">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $section->name }}</div>
                                        <div class="text-sm text-gray-500 mt-1">{{ Str::limit($section->description, 60) }}</div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-link mr-1"></i>
                                                /pages/{{ $section->slug }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($section->responsibles->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($section->responsibles->take(3) as $responsible)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $responsible->name }}
                                            </span>
                                        @endforeach
                                        @if($section->responsibles->count() > 3)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600">
                                                +{{ $section->responsibles->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Aucun responsable</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($section->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-circle text-green-500 mr-1.5" style="font-size: 6px;"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-circle text-red-500 mr-1.5" style="font-size: 6px;"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div>{{ $section->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">par {{ $section->creator->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('pages.sections.show', $section->slug) }}" target="_blank" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors p-1.5 rounded-full hover:bg-blue-50" 
                                       title="Voir en public">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    
                                    @if(auth()->user()->hasPermission('sections.edit'))
                                    <a href="{{ route('personnels.pages.sections.edit', $section) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors p-1.5 rounded-full hover:bg-indigo-50" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('sections.delete'))
                                    <form action="{{ route('personnels.pages.sections.destroy', $section) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700 transition-colors p-1.5 rounded-full hover:bg-red-50" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette section? Toutes les pages associées seront également supprimées.')" 
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 mb-4">
                                        <i class="fas fa-folder-open text-2xl"></i>
                                    </div>
                                    <span class="text-gray-500 font-medium">Aucune section trouvée</span>
                                    <p class="text-gray-400 mt-1 max-w-md">Commencez par créer une section pour organiser vos pages</p>
                                    @if(auth()->user()->hasPermission('sections.create'))
                                        <a href="{{ route('personnels.pages.sections.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all">
                                            <i class="fas fa-plus mr-2"></i> Créer la première section
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sections->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $sections->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
