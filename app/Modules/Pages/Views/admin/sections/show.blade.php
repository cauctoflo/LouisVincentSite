@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">{{ $section->name }}</h1>
            <p class="text-gray-500 mt-1">{{ $section->description }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            @if(auth()->user()->hasPermission('sections.edit'))
            <a href="{{ route('personnels.pages.sections.edit', $section) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-all shadow-sm">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            @endif
            
            <a href="{{ route('personnels.pages.sections.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pages totales</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $section->pages()->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pages publiées</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $section->pages()->published()->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Dossiers</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $section->folders()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dossiers -->
            @if($section->folders()->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Dossiers</h3>
                        @if(auth()->user()->hasPermission('folders.create'))
                        <a href="{{ route('personnels.pages.folders.create', ['section_id' => $section->id]) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all">
                            <i class="fas fa-plus mr-1"></i>
                            Nouveau dossier
                        </a>
                        @endif
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @foreach($section->folders as $folder)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-folder text-yellow-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $folder->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $folder->pages()->count() }} page(s)</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                @if(auth()->user()->hasPermission('folders.edit'))
                                <a href="{{ route('personnels.pages.folders.edit', $folder) }}" 
                                   class="text-gray-400 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('folders.delete'))
                                <form action="{{ route('personnels.pages.folders.destroy', $folder) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-gray-400 hover:text-red-600 transition-colors"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Pages récentes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Pages récentes</h3>
                        @if(auth()->user()->hasPermission('pages.create'))
                        <a href="{{ route('personnels.pages.pages.create', ['section_id' => $section->id]) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-all">
                            <i class="fas fa-plus mr-1"></i>
                            Nouvelle page
                        </a>
                        @endif
                    </div>
                </div>
                
                @if($section->pages()->orderBy('updated_at', 'desc')->limit(10)->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($section->pages()->with(['folder', 'creator'])->orderBy('updated_at', 'desc')->limit(10)->get() as $page)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 {{ $page->is_published ? 'bg-green-100' : 'bg-yellow-100' }} rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt {{ $page->is_published ? 'text-green-600' : 'text-yellow-600' }}"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $page->title }}</h4>
                                    <div class="flex items-center text-sm text-gray-500 space-x-2">
                                        @if($page->folder)
                                        <span>{{ $page->folder->name }}</span>
                                        <span>•</span>
                                        @endif
                                        <span>{{ $page->updated_at->format('d/m/Y') }}</span>
                                        <span>•</span>
                                        <span>{{ $page->creator->name }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                @if($page->is_published)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Publié
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Brouillon
                                </span>
                                @endif
                                
                                @if(auth()->user()->hasPermission('pages.view'))
                                <a href="{{ route('personnels.pages.pages.show', $page) }}" 
                                   class="text-gray-400 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('pages.edit'))
                                <a href="{{ route('personnels.pages.pages.edit', $page) }}" 
                                   class="text-gray-400 hover:text-green-600 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-8 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-file-alt text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune page</h3>
                        <p class="text-gray-500 mb-4">Cette section ne contient encore aucune page.</p>
                        @if(auth()->user()->hasPermission('pages.create'))
                        <a href="{{ route('personnels.pages.pages.create', ['section_id' => $section->id]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-all">
                            <i class="fas fa-plus mr-2"></i>
                            Créer la première page
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar avec informations -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Informations</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $section->slug }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $section->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $section->updated_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">URL publique</dt>
                        <dd class="mt-1 text-sm text-gray-900 break-all">
                            <a href="{{ route('public.pages.section', $section->slug) }}" 
                               target="_blank"
                               class="text-blue-600 hover:underline">
                                /sections/{{ $section->slug }}
                            </a>
                        </dd>
                    </div>
                </div>
            </div>
            
            <!-- Responsables -->
            @if($section->managers && count($section->managers) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Responsables</h3>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($section->managers as $managerId)
                            @php $manager = \App\Models\User::find($managerId) @endphp
                            @if($manager)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-blue-600">{{ substr($manager->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $manager->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $manager->email }}</p>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                
                <div class="p-6 space-y-3">
                    <a href="{{ route('public.pages.section', $section->slug) }}" 
                       target="_blank"
                       class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Voir la section publique
                    </a>
                    
                    @if(auth()->user()->hasPermission('pages.view'))
                    <a href="{{ route('personnels.pages.pages.index', ['section_id' => $section->id]) }}" 
                       class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Voir toutes les pages
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasPermission('sections.delete'))
                    <form action="{{ route('personnels.pages.sections.destroy', $section) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette section ? Toutes les pages et dossiers associés seront également supprimés.')">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer la section
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
