@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Gestion des pages</h1>
            <p class="text-gray-500 mt-1">Créez et gérez le contenu de vos pages</p>
        </div>
        @if(auth()->user()->hasPermission('pages.create'))
        <a href="{{ route('personnels.pages.pages.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all shadow-sm">
            <i class="fas fa-plus mr-2"></i> Créer une page
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
                           placeholder="Rechercher une page...">
                </div>
            </div>
            <div>
                <select name="section" class="block w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3">
                    <option value="">Toutes les sections</option>
                    @foreach(\App\Modules\Pages\Models\Section::active()->orderBy('name')->get() as $section)
                        <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="block w-full border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3">
                    <option value="">Tous les statuts</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publiées</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillons</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="{{ route('personnels.pages.pages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-all">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des pages -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section / Dossier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modifiée le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pages as $page)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-start">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $page->title }}</div>
                                        @if($page->excerpt)
                                            <div class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit($page->excerpt, 80) }}</div>
                                        @endif
                                        <div class="flex items-center mt-2 text-xs text-gray-400">
                                            <i class="fas fa-link mr-1"></i>
                                            @if($page->folder)
                                                /pages/{{ $page->section->slug }}/{{ $page->folder->slug }}/{{ $page->slug }}
                                            @else
                                                /pages/{{ $page->section->slug }}/pages/{{ $page->slug }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $page->section->name }}</div>
                                @if($page->folder)
                                    <div class="text-sm text-gray-500">{{ $page->folder->name }}</div>
                                @else
                                    <div class="text-sm text-gray-400 italic">Racine de section</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($page->is_published)
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-circle text-green-500 mr-1.5" style="font-size: 6px;"></i>
                                            Publiée
                                        </span>
                                        @if($page->published_at)
                                            <div class="ml-2 text-xs text-gray-500">
                                                {{ $page->published_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-circle text-yellow-500 mr-1.5" style="font-size: 6px;"></i>
                                        Brouillon
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <div>
                                        <div>{{ $page->creator->name }}</div>
                                        @if($page->updated_by && $page->updated_by !== $page->created_by)
                                            <div class="text-xs text-gray-400">Modifiée par {{ $page->updater->name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div>{{ $page->updated_at->format('d/m/Y H:i') }}</div>
                                <div class="text-xs text-gray-400">{{ $page->updated_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    @if($page->is_published)
                                        @if($page->folder)
                                            <a href="{{ route('pages.show', [$page->section->slug, $page->folder->slug, $page->slug]) }}" 
                                               target="_blank" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors p-1.5 rounded-full hover:bg-blue-50" 
                                               title="Voir en public">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('pages.section-pages.show', [$page->section->slug, $page->slug]) }}" 
                                               target="_blank" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors p-1.5 rounded-full hover:bg-blue-50" 
                                               title="Voir en public">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('personnels.pages.pages.preview', $page) }}" 
                                           target="_blank" 
                                           class="text-gray-600 hover:text-gray-900 transition-colors p-1.5 rounded-full hover:bg-gray-50" 
                                           title="Prévisualiser">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    
                                    @if(auth()->user()->hasPermission('pages.edit'))
                                    <a href="{{ route('personnels.pages.pages.edit', $page) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors p-1.5 rounded-full hover:bg-indigo-50" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('pages.publish'))
                                        @if(!$page->is_published)
                                            <form action="{{ route('personnels.pages.pages.publish', $page) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 transition-colors p-1.5 rounded-full hover:bg-green-50" 
                                                        title="Publier">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('personnels.pages.pages.unpublish', $page) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-orange-600 hover:text-orange-900 transition-colors p-1.5 rounded-full hover:bg-orange-50" 
                                                        title="Dépublier">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    @if(auth()->user()->hasPermission('pages.delete'))
                                    <form action="{{ route('personnels.pages.pages.destroy', $page) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700 transition-colors p-1.5 rounded-full hover:bg-red-50" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette page?')" 
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
                            <td colspan="6" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 mb-4">
                                        <i class="fas fa-file-alt text-2xl"></i>
                                    </div>
                                    <span class="text-gray-500 font-medium">Aucune page trouvée</span>
                                    <p class="text-gray-400 mt-1 max-w-md">Commencez par créer votre première page</p>
                                    @if(auth()->user()->hasPermission('pages.create'))
                                        <a href="{{ route('personnels.pages.pages.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all">
                                            <i class="fas fa-plus mr-2"></i> Créer la première page
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pages->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $pages->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
