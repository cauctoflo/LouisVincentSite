@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Gestion des dossiers</h1>
            <p class="text-gray-500 mt-1">Organisez vos pages en dossiers au sein des sections</p>
        </div>
        
        @if(auth()->user()->hasPermission('folders.create'))
        <a href="{{ route('personnels.pages.folders.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-all shadow-sm">
            <i class="fas fa-plus mr-2"></i>
            Nouveau dossier
        </a>
        @endif
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Rechercher des dossiers..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            
            <div class="min-w-48">
                <select name="section_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Toutes les sections</option>
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                        {{ $section->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition-all">
                <i class="fas fa-search mr-2"></i>
                Filtrer
            </button>
            
            @if(request()->hasAny(['search', 'section_id']))
            <a href="{{ route('personnels.pages.folders.index') }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                <i class="fas fa-times mr-2"></i>
                Réinitialiser
            </a>
            @endif
        </form>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Liste des dossiers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($folders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dossier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pages</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé le</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($folders as $folder)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-folder text-yellow-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $folder->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $folder->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $folder->section->name }}</div>
                            <div class="text-sm text-gray-500">{{ $folder->section->slug }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $folder->pages()->count() }}</span>
                                <span class="ml-2 text-xs text-gray-500">page(s)</span>
                            </div>
                            @if($folder->pages()->published()->count() > 0)
                            <div class="text-xs text-green-600">
                                {{ $folder->pages()->published()->count() }} publiée(s)
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($folder->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Actif
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-pause-circle mr-1"></i>
                                Inactif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $folder->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                @if(auth()->user()->hasPermission('folders.view'))
                                <a href="{{ route('personnels.pages.folders.show', $folder) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors p-1.5 rounded-full hover:bg-blue-50" 
                                   title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('folders.edit'))
                                <a href="{{ route('personnels.pages.folders.edit', $folder) }}" 
                                   class="text-green-600 hover:text-green-800 transition-colors p-1.5 rounded-full hover:bg-green-50" 
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('folders.delete'))
                                <form action="{{ route('personnels.pages.folders.destroy', $folder) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 transition-colors p-1.5 rounded-full hover:bg-red-50" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ? Toutes les pages associées seront déplacées vers la section.')" 
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($folders->hasPages())
        <div class="bg-white px-6 py-3 border-t border-gray-100">
            {{ $folders->links() }}
        </div>
        @endif
        
        @else
        <!-- État vide -->
        <div class="px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-folder text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun dossier trouvé</h3>
                <p class="text-gray-500 mb-6 max-w-md">
                    @if(request()->hasAny(['search', 'section_id']))
                        Aucun dossier ne correspond à vos critères de recherche.
                    @else
                        Commencez par créer un dossier pour organiser vos pages.
                    @endif
                </p>
                
                @if(auth()->user()->hasPermission('folders.create'))
                <a href="{{ route('personnels.pages.folders.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    Créer le premier dossier
                </a>
                @endif
                
                @if(request()->hasAny(['search', 'section_id']))
                <a href="{{ route('personnels.pages.folders.index') }}" 
                   class="mt-3 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                    <i class="fas fa-times mr-2"></i>
                    Réinitialiser les filtres
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
