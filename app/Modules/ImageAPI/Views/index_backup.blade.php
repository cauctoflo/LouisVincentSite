@extends('layouts.admin')

@section('title', 'Gestion des Images')

@section('content')

@php 
    $currentGroupId = request('group', null);
    $images = $currentGroupId ? 
        App\Models\ImageApi::where('group_id', $currentGroupId)->get() : 
        App\Models\ImageApi::whereNull('group_id')->get();
    
    $groups = App\Models\ImageApiGroups::orderBy('sort', 'asc')->orderBy('name', 'asc')->get();
    $totalGroups = $groups->count();
    $search = request('search', '');
    $filter = request('filter', 'all');
    $viewMode = request('view', 'grid'); // Default to grid view
    
    if (!empty($search)) {
        $images = $images->filter(function($image) use ($search) {
            return stripos($image->Name ?? '', $search) !== false || 
                   stripos($image->path ?? '', $search) !== false ||
                   stripos($image->tags ?? '', $search) !== false;
        });
    }
    
    if ($filter === 'public') {
        $images = $images->where('status', 'public');
    } elseif ($filter === 'private') {
        $images = $images->where('status', '!=', 'public');
    }
    
    $publicCount = App\Models\ImageApi::where('status', 'public')->count();
    $privateCount = App\Models\ImageApi::where('status', '!=', 'public')->count();
    $totalImages = $images->count();
    
    // Calcul de l'espace de stockage (simulation)
    $usedStorage = 650; // GB simulé
    $totalStorage = 1000; // GB total simulé
    $storagePercentage = ($usedStorage / $totalStorage) * 100;
@endphp

<!-- Modern Google Drive inspired layout -->
<div class="min-h-screen bg-white">
    <!-- Top Navigation Bar -->
    <div class="sticky top-0 z-40 border-b border-gray-200 bg-white/80 backdrop-blur-md">
        <div class="mx-auto max-w-full px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Left Section -->
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3">
                        <div class="rounded-lg bg-blue-500 p-2">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">Images</h1>
                            <p class="text-sm text-gray-500">{{ $totalImages }} éléments</p>
                        </div>
                    </div>
                    
                    <!-- Breadcrumb -->
                    <nav class="hidden lg:flex items-center space-x-2 text-sm text-gray-500">
                        <span>Mon Drive</span>
                        @if($currentGroupId)
                            @php $group = $groups->firstWhere('id', $currentGroupId); @endphp
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                            <span>{{ $group->name ?? 'Dossier' }}</span>
                        @endif
                    </nav>
                </div>

                <!-- Center Search -->
                <div class="flex-1 max-w-xl mx-8">
                    <form action="{{ url()->current() }}" method="GET" class="relative">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" value="{{ $search }}" 
                                   placeholder="Rechercher dans vos images..."
                                   class="w-full rounded-full border-0 bg-gray-100 pl-10 pr-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                        </div>
                        @if($currentGroupId)
                            <input type="hidden" name="group" value="{{ $currentGroupId }}">
                        @endif
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-3">
                    <!-- View Toggle -->
                    <div class="flex items-center rounded-lg bg-gray-100 p-1">
                        <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['view' => 'grid'])) }}" 
                           class="rounded-md p-2 {{ $viewMode === 'grid' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </a>
                        <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['view' => 'list'])) }}" 
                           class="rounded-md p-2 {{ $viewMode === 'list' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                <line x1="3" y1="18" x2="21" y2="18"></line>
                            </svg>
                        </a>
                    </div>

                    <!-- Filter Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            <span>Filtrer</span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['filter' => 'all'])) }}" 
                                   class="block px-4 py-2 text-sm {{ $filter === 'all' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Toutes les images
                                </a>
                                <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['filter' => 'public'])) }}" 
                                   class="block px-4 py-2 text-sm {{ $filter === 'public' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Images publiques
                                </a>
                                <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['filter' => 'private'])) }}" 
                                   class="block px-4 py-2 text-sm {{ $filter === 'private' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Images privées
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Button -->
                    <button id="openAddImageModal" class="inline-flex items-center space-x-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Nouveau</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex h-screen pt-20">
        <!-- Sidebar -->
        <div class="w-64 border-r border-gray-200 bg-gray-50/50 px-4 py-6">
            <!-- Quick Stats -->
            <div class="mb-6 space-y-3">
                <div class="rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Stockage utilisé</p>
                            <p class="text-2xl font-bold">{{ $usedStorage }} GB</p>
                            <p class="text-xs opacity-75">sur {{ $totalStorage }} GB</p>
                        </div>
                        <div class="text-right">
                            <div class="h-2 w-20 rounded-full bg-white/20">
                                <div class="h-2 rounded-full bg-white" style="width: {{ $storagePercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="space-y-2">
                <a href="{{ url()->current() }}" 
                   class="flex items-center space-x-3 rounded-lg px-3 py-2 text-sm {{ !$currentGroupId ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span>Toutes mes images</span>
                    <span class="ml-auto text-xs text-gray-500">{{ App\Models\ImageApi::count() }}</span>
                </a>

                <a href="{{ url()->current() }}?filter=public" 
                   class="flex items-center space-x-3 rounded-lg px-3 py-2 text-sm {{ $filter === 'public' ? 'bg-green-50 text-green-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                    <span>Partagées</span>
                    <span class="ml-auto text-xs text-gray-500">{{ $publicCount }}</span>
                </a>

                <a href="{{ url()->current() }}?filter=private" 
                   class="flex items-center space-x-3 rounded-lg px-3 py-2 text-sm {{ $filter === 'private' ? 'bg-red-50 text-red-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span>Privées</span>
                    <span class="ml-auto text-xs text-gray-500">{{ $privateCount }}</span>
                </a>
            </nav>

            <!-- Folders Section -->
            @if($groups->count() > 0)
            <div class="mt-8">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Dossiers</h3>
                    <button onclick="openCreateGroupModal()" class="rounded p-1 text-gray-400 hover:text-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
                <nav class="space-y-1">
                    @foreach($groups as $group)
                    <a href="{{ url()->current() }}?group={{ $group->id }}" 
                       class="flex items-center space-x-3 rounded-lg px-3 py-2 text-sm {{ $currentGroupId == $group->id ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                        <span class="truncate">{{ $group->name }}</span>
                    </a>
                    @endforeach
                </nav>
            </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 overflow-auto">
            @if($images->count() > 0)
                @if($viewMode === 'grid')
                    <!-- Grid View -->
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                            @foreach($images as $image)
                            <div class="group relative">
                                <!-- Image Card -->
                                <div class="aspect-square overflow-hidden rounded-lg border border-gray-200 bg-gray-50 hover:shadow-lg transition-all duration-200 cursor-pointer"
                                     onclick="openEditModal({{ $image->id }}, '{{ $image->Name }}', '{{ $image->tags }}', '{{ $image->description }}', '{{ $image->alt_text }}', '{{ $image->status }}', '{{ asset('storage/' . $image->path) }}')">
                                    <img src="{{ asset('storage/' . $image->path) }}" 
                                         alt="{{ $image->alt_text ?? $image->Name }}"
                                         class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-105">
                                    
                                    <!-- Status Badge -->
                                    <div class="absolute top-2 right-2">
                                        @if($image->status === 'public')
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800">
                                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                                </svg>
                                                Public
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800">
                                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                Privé
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Hover Actions -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200">
                                        <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <button onclick="event.stopPropagation(); copyImageLink('{{ $image->token }}')" 
                                                    class="rounded-full bg-white p-2 shadow-md hover:bg-gray-50">
                                                <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Image Title -->
                                <div class="mt-2 px-1">
                                    <p class="truncate text-sm text-gray-900 font-medium">{{ $image->Name ?? 'image-' . $image->id . '.jpg' }}</p>
                                    <p class="text-xs text-gray-500">{{ $image->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- List View -->
                    <div class="p-6">
                        <div class="overflow-hidden bg-white rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($images as $image)
                                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="openEditModal({{ $image->id }}, '{{ $image->Name }}', '{{ $image->tags }}', '{{ $image->description }}', '{{ $image->alt_text }}', '{{ $image->status }}', '{{ asset('storage/' . $image->path) }}')">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="h-10 w-10 rounded-lg overflow-hidden bg-gray-100">
                                                <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->alt_text }}" class="h-full w-full object-cover">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $image->Name ?? 'image-' . $image->id . '.jpg' }}</div>
                                            <div class="text-sm text-gray-500">{{ $image->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($image->status === 'public')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Public
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Privé
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $image->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="event.stopPropagation(); copyImageLink('{{ $image->token }}')" class="text-blue-600 hover:text-blue-900 mr-3">
                                                Partager
                                            </button>
                                            <button onclick="event.stopPropagation(); deleteImage({{ $image->id }})" class="text-red-600 hover:text-red-900">
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="flex h-full items-center justify-center">
                    <div class="text-center">
                        <div class="mx-auto h-24 w-24 text-gray-300">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">
                            @if($search)
                                Aucune image trouvée
                            @elseif($currentGroupId)
                                Ce dossier est vide
                            @else
                                Aucune image
                            @endif
                        </h3>
                        <p class="mt-2 text-gray-500">
                            @if($search)
                                Aucune image ne correspond à votre recherche "{{ $search }}"
                            @elseif($currentGroupId)
                                Ajoutez des images à ce dossier pour commencer
                            @else
                                Commencez par ajouter vos premières images
                            @endif
                        </p>
                        <div class="mt-6">
                            @if($search)
                                <a href="{{ url()->current() }}?{{ http_build_query(array_diff_key(request()->all(), ['search' => ''])) }}" 
                                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                                    Effacer la recherche
                                </a>
                            @endif
                            <button id="openAddImageModalEmpty" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Ajouter des images
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="addImageModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <form action="{{ route('personnels.ImageAPI.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Ajouter des images
                            </h3>
                            <div class="mt-4 space-y-4">
                                <!-- File Upload -->
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                    <input type="file" name="images[]" multiple accept="image/*" class="hidden" id="image-upload">
                                    <label for="image-upload" class="cursor-pointer">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <span class="mt-2 block text-sm font-medium text-gray-900">
                                            Glissez vos images ici ou cliquez pour les sélectionner
                                        </span>
                                    </label>
                                </div>
                                
                                <!-- Form Fields -->
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Titre</label>
                                        <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tags</label>
                                        <input type="text" name="tag" placeholder="Séparés par des virgules" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="public">Public</option>
                                            <option value="private">Privé</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Ajouter
                    </button>
                    <button type="button" onclick="hideModal('addImageModal')" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editImageModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <form id="editImageForm" action="{{ route('personnels.ImageAPI.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="image_id" id="edit_image_id">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Modifier l'image
                            </h3>
                            <div class="mt-4 space-y-4">
                                <!-- Image Preview -->
                                <div class="text-center">
                                    <img id="edit_image_preview" src="" alt="Aperçu" class="mx-auto h-32 w-32 rounded-lg object-cover border border-gray-200">
                                </div>
                                
                                <!-- Form Fields -->
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Titre</label>
                                        <input type="text" name="title" id="edit_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tags</label>
                                        <input type="text" name="tag" id="edit_tag" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" id="edit_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                                        <select name="status" id="edit_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="public">Public</option>
                                            <option value="private">Privé</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Enregistrer
                    </button>
                    <button type="button" onclick="deleteImage(document.getElementById('edit_image_id').value)" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Supprimer
                    </button>
                    <button type="button" onclick="hideModal('editImageModal')" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notifications -->
@if (session('message') || session('error') || session('success') || session('warning') || session('info'))
    @php
        $type = 'success';
        $message = session('success') ?? session('message') ?? '';
        $alertClasses = 'bg-green-50 border-green-200 text-green-800';
        $iconColor = 'text-green-400';
        
        if (session('error')) {
            $type = 'error';
            $message = session('error');
            $alertClasses = 'bg-red-50 border-red-200 text-red-800';
            $iconColor = 'text-red-400';
        } elseif (session('warning')) {
            $type = 'warning';
            $message = session('warning');
            $alertClasses = 'bg-yellow-50 border-yellow-200 text-yellow-800';
            $iconColor = 'text-yellow-400';
        } elseif (session('info')) {
            $type = 'info';
            $message = session('info');
            $alertClasses = 'bg-blue-50 border-blue-200 text-blue-800';
            $iconColor = 'text-blue-400';
        }
    @endphp
    
    <div id="notification" class="fixed top-4 right-4 max-w-sm w-full z-50">
        <div class="rounded-md border {{ $alertClasses }} p-4 shadow-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($type === 'success')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @elseif($type === 'error')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @elseif($type === 'warning')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @endif
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ $message }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="document.getElementById('notification').remove()" class="inline-flex rounded-md p-1.5 hover:bg-gray-100 focus:outline-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        setTimeout(() => {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.remove();
            }
        }, 5000);
    </script>
@endif

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
// Modal functions
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Open modals
document.getElementById('openAddImageModal').addEventListener('click', () => showModal('addImageModal'));

// Check if there's an empty button and add listener
const emptyButton = document.getElementById('openAddImageModalEmpty');
if (emptyButton) {
    emptyButton.addEventListener('click', () => showModal('addImageModal'));
}

// Edit modal function
function openEditModal(id, name, tags, description, altText, status, imagePath) {
    document.getElementById('edit_image_id').value = id;
    document.getElementById('edit_title').value = name || '';
    document.getElementById('edit_tag').value = tags || '';
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_status').value = status || 'public';
    document.getElementById('edit_image_preview').src = imagePath;
    
    showModal('editImageModal');
}

// Copy image link function
function copyImageLink(token) {
    const embedLink = `{{ url('/images/token') }}/${token}`;
    
    navigator.clipboard.writeText(embedLink).then(() => {
        showNotification('Lien copié avec succès !', 'success');
    }).catch(() => {
        showNotification('Erreur lors de la copie du lien', 'error');
    });
}

// Delete image function
function deleteImage(imageId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("personnels.ImageAPI.destroy") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        const imageIdInput = document.createElement('input');
        imageIdInput.type = 'hidden';
        imageIdInput.name = 'image_id';
        imageIdInput.value = imageId;
        form.appendChild(imageIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Show notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const colors = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };
    
    notification.className = `fixed top-4 right-4 max-w-sm w-full z-50 transform transition-transform duration-300 translate-x-full`;
    notification.innerHTML = `
        <div class="rounded-md border ${colors[type]} p-4 shadow-md">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex rounded-md p-1.5 hover:bg-gray-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function openCreateGroupModal() {
    showNotification('Fonctionnalité en cours de développement', 'info');
}

// Close modals when clicking outside
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        const modals = ['addImageModal', 'editImageModal'];
        modals.forEach(modalId => {
            if (!document.getElementById(modalId).classList.contains('hidden')) {
                hideModal(modalId);
            }
        });
    }
});
</script>

@endsection
