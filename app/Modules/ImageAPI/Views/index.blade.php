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

<div class="bg-white border-b border-gray-200 mb-6 mb-10">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Left Section -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="rounded-lg bg-blue-500 p-2">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Gestion des Images</h1>
                        <p class="text-sm text-gray-500">{{ $totalImages }} éléments</p>
                    </div>
                </div>
                
                <!-- Breadcrumb -->
                @if($currentGroupId)
                    @php $group = $groups->firstWhere('id', $currentGroupId); @endphp
                    <nav class="hidden lg:flex items-center space-x-2 text-sm text-gray-500">
                        <span>Toutes les images</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                        <span>{{ $group->name ?? 'Dossier' }}</span>
                    </nav>
                @endif
            </div>


            <div class="flex items-center space-x-3">
                <div class="flex-1 max-w-md">
                    <form action="{{ url()->current() }}" method="GET" class="relative">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" value="{{ $search }}" 
                                   placeholder="Rechercher..."
                                   class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        @if($currentGroupId)
                            <input type="hidden" name="group" value="{{ $currentGroupId }}">
                        @endif
                    </form>
                </div>

                <!-- View Toggle -->
                <div class="flex items-center rounded-lg bg-gray-100 p-1">
                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['view' => 'grid'])) }}" 
                       class="rounded-md p-2 {{ $viewMode === 'grid' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </a>
                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['view' => 'list'])) }}" 
                       class="rounded-md p-2 {{ $viewMode === 'list' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </a>
                </div>

                <!-- Filter Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        <span>Filtrer</span>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-10">
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
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Nouveau</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Content Wrapper with proper flex layout -->
<div class="flex flex-col">
<!-- Stats and Navigation Cards -->
<div class="bg-gray-50 px-6 py-4 flex-shrink-0">
    <!-- Stats Cards Row -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Storage Card -->
        <div class="md:col-span-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
            <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">Stockage utilisé</p>
                <p class="text-2xl font-bold">{{ $usedStorage }} GB</p>
                <p class="text-xs opacity-75">sur {{ $totalStorage }} GB</p>
            </div>
            <div class="text-right col-span-2">
                <div class="h-2 w-48 md:w-64 lg:w-80 rounded-full bg-white/20">
                <div class="h-2 rounded-full bg-white" style="width: {{ $storagePercentage }}%"></div>
                </div>
                <p class="text-xs mt-1 opacity-75">{{ number_format($storagePercentage, 1) }}%</p>
            </div>
            </div>
        </div>
        
        <!-- Total Images Card -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="rounded-lg bg-gray-100 p-3">
                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ App\Models\ImageApi::count() }}</p>
                </div>
            </div>
        </div>
        
        <!-- Public Images Card -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="rounded-lg bg-green-100 p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-green-600">Publiques</p>
                    <p class="text-2xl font-bold text-green-900">{{ $publicCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Cards Row -->
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Navigation rapide</h3>
        <div class="flex flex-wrap gap-3">
            <!-- All Images -->
            <a href="{{ url()->current() }}?filter=all" 
               class="inline-flex items-center space-x-2 rounded-lg px-4 py-3 text-sm font-medium transition-colors {{ !$currentGroupId && $filter === 'all' ? 'bg-blue-50 text-blue-600 border-2 border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span>Tout</span>
                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">{{ App\Models\ImageApi::count() }}</span>
            </a>

            <!-- Public Images -->
            <a href="{{ url()->current() }}?filter=public" 
               class="inline-flex items-center space-x-2 rounded-lg px-4 py-3 text-sm font-medium transition-colors {{ $filter === 'public' ? 'bg-green-50 text-green-600 border-2 border-green-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
                <span>Partagées</span>
                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">{{ $publicCount }}</span>
            </a>

            <!-- Private Images -->
            <a href="{{ url()->current() }}?filter=private" 
               class="inline-flex items-center space-x-2 rounded-lg px-4 py-3 text-sm font-medium transition-colors {{ $filter === 'private' ? 'bg-red-50 text-red-600 border-2 border-red-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>Privées</span>
                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">{{ $privateCount }}</span>
            </a>
        </div>
    </div>

    <!-- Folders Cards Row -->
    @if($groups->count() > 0 && $filter === 'all')
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-gray-900">Dossiers</h3>
            <button onclick="openCreateGroupModal()" class="inline-flex items-center space-x-1 rounded-lg bg-white border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Nouveau dossier</span>
            </button>
        </div>
        <div class="flex flex-wrap gap-3">
            @foreach($groups as $group)
            <div class="relative group-item">
                <!-- Folder Card -->
                @php
                    $folderBgClass = '';
                    $folderBorderClass = '';
                    $folderTextClass = '';
                    
                    if ($group->color && $currentGroupId == $group->id) {
                        $folderBgClass = match($group->color) {
                            'blue' => 'bg-blue-50',
                            'green' => 'bg-green-50',
                            'red' => 'bg-red-50',
                            'yellow' => 'bg-yellow-50',
                            'purple' => 'bg-purple-50',
                            'orange' => 'bg-orange-50',
                            'gray' => 'bg-gray-50',
                            default => 'bg-blue-50'
                        };
                        
                        $folderBorderClass = match($group->color) {
                            'blue' => 'border-blue-200',
                            'green' => 'border-green-200',
                            'red' => 'border-red-200',
                            'yellow' => 'border-yellow-200',
                            'purple' => 'border-purple-200',
                            'orange' => 'border-orange-200',
                            'gray' => 'border-gray-200',
                            default => 'border-blue-200'
                        };
                        
                        $folderTextClass = match($group->color) {
                            'blue' => 'text-blue-600',
                            'green' => 'text-green-600',
                            'red' => 'text-red-600',
                            'yellow' => 'text-yellow-600',
                            'purple' => 'text-purple-600',
                            'orange' => 'text-orange-600',
                            'gray' => 'text-gray-600',
                            default => 'text-blue-600'
                        };
                    } else {
                        $folderBgClass = $currentGroupId == $group->id ? 'bg-blue-50' : 'bg-white';
                        $folderBorderClass = $currentGroupId == $group->id ? 'border-blue-200' : 'border-gray-200';
                        $folderTextClass = $currentGroupId == $group->id ? 'text-blue-600' : 'text-gray-700';
                    }
                @endphp
                
                <a href="{{ url()->current() }}?group={{ $group->id }}" 
                   class="inline-flex items-center space-x-2 rounded-lg px-4 py-3 text-sm font-medium transition-colors {{ $folderBgClass }} {{ $folderTextClass }} border-2 {{ $folderBorderClass }} hover:bg-gray-50 pr-10">
                    @if($group->icon && $group->color)
                        @php
                            $iconEmoji = match($group->icon) {
                                'camera' => '📷',
                                'image' => '🖼️',
                                'star' => '⭐',
                                default => '📁'
                            };
                            
                            $iconBgClass = match($group->color) {
                                'blue' => 'bg-blue-100',
                                'green' => 'bg-green-100',
                                'red' => 'bg-red-100',
                                'yellow' => 'bg-yellow-100',
                                'purple' => 'bg-purple-100',
                                'orange' => 'bg-orange-100',
                                'gray' => 'bg-gray-100',
                                default => 'bg-blue-100'
                            };
                        @endphp
                        <span class="text-base p-1 rounded {{ $iconBgClass }}">{{ $iconEmoji }}</span>
                    @else
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    @endif
                    <span class="truncate max-w-32">{{ $group->name }}</span>
                </a>
                
                <!-- Options Button avec 3 points -->
                <div class="absolute top-1/2 right-2 transform -translate-y-1/2 z-10">
                    <button type="button" 
                            class="flex items-center justify-center w-6 h-6 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-200/80 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors opacity-0 group-item-hover:opacity-100" 
                            onclick="event.preventDefault(); event.stopPropagation(); toggleGroupOptions({{ $group->id }})"
                            title="Options pour {{ $group->name }}">
                        <!-- SVG des 3 points verticaux -->
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                    
                    <!-- Menu déroulant -->
                    <div class="hidden absolute right-0 top-full mt-1 z-50" id="group-options-{{ $group->id }}">
                        <div class="bg-white shadow-lg rounded-lg border border-gray-200 min-w-48 py-1">
                            <button type="button" 
                                    onclick="editGroup({{ $group->id }}); document.getElementById('group-options-{{ $group->id }}').classList.add('hidden');" 
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <svg class="h-4 w-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier le dossier
                            </button>
                            <button type="button" 
                                    onclick="deleteGroup({{ $group->id }}); document.getElementById('group-options-{{ $group->id }}').classList.add('hidden');" 
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                <svg class="h-4 w-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer le dossier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Main Content -->
<div class="bg-white">
    <!-- Main Content Area with fixed height and scroll -->
    <div class="images-container overflow-y-scroll border-t border-gray-200" style="height: calc(100vh - 350px); min-height: 400px; max-height: calc(100vh - 200px);">
        @if($images->count() > 0)
            @if($viewMode === 'grid')
                <!-- Grid View -->
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                        @foreach($images as $image)
                        <div class="group relative">
                            <!-- Image Card -->
                            <div class="aspect-square overflow-hidden rounded-lg border border-gray-200 bg-gray-50 hover:shadow-lg transition-all duration-200 cursor-pointer relative"
                                 onclick="openEditModal({{ $image->id }}, '{{ addslashes($image->Name ?? '') }}', '{{ addslashes($image->tags ?? '') }}', '{{ addslashes($image->description ?? '') }}', '{{ addslashes($image->alt_text ?? '') }}', '{{ $image->status }}', '{{ asset('storage/' . $image->path) }}')">
                                
                                @if($image->path && file_exists(storage_path('app/public/' . $image->path)))
                                    <img src="{{ asset('storage/' . $image->path) }}" 
                                         alt="{{ $image->alt_text ?? $image->Name }}"
                                         class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-105"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    
                                    <!-- Fallback when image fails to load -->
                                    <div class="h-full w-full flex items-center justify-center bg-gray-100" style="display: none;">
                                        <div class="text-center">
                                            <svg class="h-12 w-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-500 mt-2">Image non disponible</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-gray-100">
                                        <div class="text-center">
                                            <svg class="h-12 w-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-500 mt-2">Fichier introuvable</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-2 right-2 z-10">
                                    @if($image->status === 'public')
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 shadow-sm">
                                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                            </svg>
                                            Public
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800 shadow-sm">
                                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            Privé
                                        </span>
                                    @endif
                                </div>

                                <!-- Hover Actions -->
                                <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                    <button onclick="event.stopPropagation(); copyImageLink('{{ $image->token }}')" 
                                            class="rounded-full bg-white p-2 shadow-md hover:bg-gray-50">
                                        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                        </svg>
                                    </button>
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
                                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="openEditModal({{ $image->id }}, '{{ addslashes($image->Name ?? '') }}', '{{ addslashes($image->tags ?? '') }}', '{{ addslashes($image->description ?? '') }}', '{{ addslashes($image->alt_text ?? '') }}', '{{ $image->status }}', '{{ asset('storage/' . $image->path) }}')">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="h-10 w-10 rounded-lg overflow-hidden bg-gray-100">
                                                @if($image->path && file_exists(storage_path('app/public/' . $image->path)))
                                                    <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->alt_text }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
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
                <div class="flex min-h-96 items-center justify-center p-6">
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
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Dossier</label>
                                        <select name="group_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">📁 Aucun (racine)</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" {{ $currentGroupId == $group->id ? 'selected' : '' }}>
                                                    @if($group->icon)
                                                        @switch($group->icon)
                                                            @case('camera')
                                                                📷 {{ $group->name }}
                                                                @break
                                                            @case('image')
                                                                🖼️ {{ $group->name }}
                                                                @break
                                                            @case('star')
                                                                ⭐ {{ $group->name }}
                                                                @break
                                                            @default
                                                                📁 {{ $group->name }}
                                                        @endswitch
                                                    @else
                                                        📁 {{ $group->name }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Sélectionnez le dossier où placer l'image</p>
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

<!-- Create Folder Modal -->
<div id="createFolderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <form action="{{ route('personnels.ImageAPI.folder.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Nouveau dossier
                            </h3>
                            <div class="mt-4 space-y-4">
                                <!-- Form Fields -->
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nom du dossier <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ex: Photos de vacances">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Description du dossier (optionnel)"></textarea>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Icône</label>
                                            <select name="icon" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="folder">📁 Dossier</option>
                                                <option value="camera">📷 Appareil photo</option>
                                                <option value="image">🖼️ Image</option>
                                                <option value="star">⭐ Favoris</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Couleur</label>
                                            <select name="color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="blue">🔵 Bleu</option>
                                                <option value="green">🟢 Vert</option>
                                                <option value="red">🔴 Rouge</option>
                                                <option value="yellow">🟡 Jaune</option>
                                                <option value="purple">🟣 Violet</option>
                                                <option value="orange">🟠 Orange</option>
                                                <option value="gray">⚫ Gris</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Dossier parent</label>
                                        <select name="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Aucun (dossier racine)</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Groups Management Section -->
                                    @if($groups && $groups->count() > 0)
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700">Gestion des dossiers existants ({{ $groups->count() }})</label>
                                            <span class="text-xs text-gray-500">Cliquez sur ⋮ pour les options</span>
                                        </div>
                                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3 bg-gray-50">
                                            @foreach($groups as $group)
                                            <div class="flex items-center justify-between p-3 bg-white rounded-md shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5l-2-2H5a2 2 0 00-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $group->name }}</p>
                                                        <p class="text-xs text-gray-500">ID: {{ $group->id }}</p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Group actions button avec 3 points -->
                                                <div class="relative flex-shrink-0 ml-3">
                                                    <button type="button" 
                                                            class="flex items-center justify-center w-8 h-8 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" 
                                                            onclick="event.stopPropagation(); toggleGroupOptions({{ $group->id }})"
                                                            title="Options pour {{ $group->name }}">
                                                        <!-- SVG des 3 points verticaux -->
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Menu déroulant -->
                                                    <div class="hidden absolute right-0 top-full mt-1 z-50" id="group-options-{{ $group->id }}">
                                                        <div class="bg-white shadow-lg rounded-lg border border-gray-200 min-w-48 py-1">
                                                            <button type="button" 
                                                                    onclick="editGroup({{ $group->id }}); document.getElementById('group-options-{{ $group->id }}').classList.add('hidden');" 
                                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                                <svg class="h-4 w-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                </svg>
                                                                Modifier le dossier
                                                            </button>
                                                            <button type="button" 
                                                                    onclick="deleteGroup({{ $group->id }}); document.getElementById('group-options-{{ $group->id }}').classList.add('hidden');" 
                                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                                <svg class="h-4 w-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                                Supprimer le dossier
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @else
                                    <div class="mt-4 p-3 bg-gray-50 rounded-md border border-gray-200">
                                        <p class="text-sm text-gray-500 text-center">Aucun dossier existant. Créez votre premier dossier ci-dessus.</p>
                                    </div>
                                    @endif
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Ordre de tri</label>
                                            <input type="number" name="sort" value="0" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Taille max (MB)</label>
                                            <input type="number" name="max_size" value="100" min="1" max="1000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Types de fichiers autorisés</label>
                                        <input type="text" name="allowed_types" value="jpg,jpeg,png,gif,webp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ex: jpg,png,gif">
                                        <p class="text-xs text-gray-500 mt-1">Séparez les extensions par des virgules</p>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <label class="ml-2 block text-sm text-gray-700">Dossier actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Créer le dossier
                    </button>
                    <button type="button" onclick="hideModal('createFolderModal')" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Folder Modal -->
<div id="editFolderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <form id="editFolderForm" action="{{ route('personnels.ImageAPI.folder.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="folder_id" id="edit_folder_id">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Modifier le dossier
                            </h3>
                            <div class="mt-4 space-y-4">
                                <!-- Form Fields -->
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nom du dossier <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="edit_folder_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ex: Photos de vacances">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" id="edit_folder_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Description du dossier (optionnel)"></textarea>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Icône</label>
                                            <select name="icon" id="edit_folder_icon" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="folder">📁 Dossier</option>
                                                <option value="camera">📷 Appareil photo</option>
                                                <option value="image">🖼️ Image</option>
                                                <option value="star">⭐ Favoris</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Couleur</label>
                                            <select name="color" id="edit_folder_color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="blue">🔵 Bleu</option>
                                                <option value="green">🟢 Vert</option>
                                                <option value="red">🔴 Rouge</option>
                                                <option value="yellow">🟡 Jaune</option>
                                                <option value="purple">🟣 Violet</option>
                                                <option value="orange">🟠 Orange</option>
                                                <option value="gray">⚫ Gris</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Dossier parent</label>
                                        <select name="group_id" id="edit_folder_parent" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Aucun (dossier racine)</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Ordre de tri</label>
                                            <input type="number" name="sort" id="edit_folder_sort" value="0" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Taille max (MB)</label>
                                            <input type="number" name="max_size" id="edit_folder_max_size" value="100" min="1" max="1000" step="0.1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Types de fichiers autorisés</label>
                                        <input type="text" name="allowed_types" id="edit_folder_allowed_types" value="jpg,jpeg,png,gif,webp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ex: jpg,png,gif">
                                        <p class="text-xs text-gray-500 mt-1">Séparez les extensions par des virgules</p>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_active" id="edit_folder_is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <label class="ml-2 block text-sm text-gray-700">Dossier actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Modifier le dossier
                    </button>
                    <button type="button" onclick="hideModal('editFolderModal')" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Folder Confirmation Modal -->
<div id="deleteFolderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Confirmer la suppression
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Êtes-vous sûr de vouloir supprimer le dossier <strong id="deleteFolderName"></strong> ?
                            </p>
                            <div id="deleteFolderDetails" class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <span id="deleteFolderImageCount">0</span> image(s) seront déplacées à la racine.
                                            Cette action est irréversible.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" id="confirmDeleteFolder" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                    Supprimer définitivement
                </button>
                <button type="button" onclick="hideModal('deleteFolderModal')" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0114 0z"></path>
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
document.getElementById('openAddImageModal').addEventListener('click', () => {
    // Pré-sélectionner le dossier actuel si on est dans un dossier
    const currentGroupId = '{{ $currentGroupId }}';
    const groupSelect = document.querySelector('#addImageModal select[name="group_id"]');
    if (groupSelect && currentGroupId) {
        groupSelect.value = currentGroupId;
    }
    showModal('addImageModal');
});

// Check if there's an empty button and add listener
const emptyButton = document.getElementById('openAddImageModalEmpty');
if (emptyButton) {
    emptyButton.addEventListener('click', () => {
        // Pré-sélectionner le dossier actuel si on est dans un dossier
        const currentGroupId = '{{ $currentGroupId }}';
        const groupSelect = document.querySelector('#addImageModal select[name="group_id"]');
        if (groupSelect && currentGroupId) {
            groupSelect.value = currentGroupId;
        }
        showModal('addImageModal');
    });
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
    showModal('createFolderModal');
}

// Toggle group options menu
function toggleGroupOptions(groupId) {
    const optionsMenu = document.getElementById('group-options-' + groupId);
    if (optionsMenu) {
        // Hide all other open menus first
        document.querySelectorAll('[id^="group-options-"]').forEach(menu => {
            if (menu.id !== 'group-options-' + groupId) {
                menu.classList.add('hidden');
            }
        });
        // Toggle current menu
        optionsMenu.classList.toggle('hidden');
    }
}

// Close group options when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[id^="group-options-"]') && !event.target.closest('button[onclick*="toggleGroupOptions"]')) {
        document.querySelectorAll('[id^="group-options-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Edit and delete group functions
function editGroup(groupId) {
    // Chercher les données du groupe dans la page
    const groupData = @json($groups->keyBy('id'));
    const group = groupData[groupId];
    
    if (group) {
        // Remplir le modal avec les données du groupe
        document.getElementById('edit_folder_id').value = group.id;
        document.getElementById('edit_folder_name').value = group.name || '';
        document.getElementById('edit_folder_description').value = group.description || '';
        document.getElementById('edit_folder_icon').value = group.icon || 'folder';
        document.getElementById('edit_folder_color').value = group.color || 'blue';
        document.getElementById('edit_folder_parent').value = group.group_id || '';
        document.getElementById('edit_folder_sort').value = group.sort || 0;
        document.getElementById('edit_folder_max_size').value = group.max_size || 100;
        document.getElementById('edit_folder_allowed_types').value = group.allowed_types || 'jpg,jpeg,png,gif,webp';
        document.getElementById('edit_folder_is_active').checked = group.is_active == 1;
        
        // Ouvrir le modal
        showModal('editFolderModal');
    } else {
        showNotification('Erreur: Données du dossier non trouvées', 'error');
    }
}

function deleteGroup(groupId) {
    // Trouver les informations du dossier
    const allGroups = @json($groups);
    const groupToDelete = allGroups.find(g => g.id == groupId);
    
    if (!groupToDelete) {
        showNotification('Erreur: Dossier non trouvé', 'error');
        return;
    }
    
    // Faire une requête pour obtenir le nombre d'images dans ce dossier
    fetch(`{{ url('/personnels/images/folder') }}/${groupId}/images-count`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Remplir le modal avec les informations
        document.getElementById('deleteFolderName').textContent = groupToDelete.name;
        document.getElementById('deleteFolderImageCount').textContent = data.count || 0;
        
        // Stocker l'ID du dossier pour la confirmation
        document.getElementById('confirmDeleteFolder').setAttribute('data-folder-id', groupId);
        
        // Afficher le modal
        showModal('deleteFolderModal');
    })
    .catch(error => {
        console.error('Erreur:', error);
        // En cas d'erreur, on affiche quand même le modal avec 0 images
        document.getElementById('deleteFolderName').textContent = groupToDelete.name;
        document.getElementById('deleteFolderImageCount').textContent = '0';
        document.getElementById('confirmDeleteFolder').setAttribute('data-folder-id', groupId);
        showModal('deleteFolderModal');
    });
}

function confirmFolderDeletion() {
    const folderId = document.getElementById('confirmDeleteFolder').getAttribute('data-folder-id');
    
    if (!folderId) {
        showNotification('Erreur: ID du dossier non trouvé', 'error');
        return;
    }
    
    // Fermer le modal avant de soumettre
    hideModal('deleteFolderModal');
    
    // Créer un formulaire pour supprimer le dossier
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("personnels.ImageAPI.folder.destroy") }}';
    
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
    
    const folderIdInput = document.createElement('input');
    folderIdInput.type = 'hidden';
    folderIdInput.name = 'folder_id';
    folderIdInput.value = folderId;
    form.appendChild(folderIdInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Close modals when clicking outside
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        const modals = ['addImageModal', 'editImageModal', 'createFolderModal', 'editFolderModal', 'deleteFolderModal'];
        modals.forEach(modalId => {
            if (!document.getElementById(modalId).classList.contains('hidden')) {
                hideModal(modalId);
            }
        });
    }
});

// Event listener for delete folder confirmation button
document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteBtn = document.getElementById('confirmDeleteFolder');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', confirmFolderDeletion);
    }
    
    // Force scrollbar visibility sur la zone des images uniquement
    const imageContainer = document.querySelector('.images-container');
    if (imageContainer) {
        imageContainer.style.overflowY = 'scroll';
    }
});
</script>

@endsection

<style>
.group-item:hover .group-item-hover\:opacity-100 {
    opacity: 1 !important;
}

/* Fix pour assurer un bon affichage et scroll */
body {
    overflow-x: hidden;
}

/* Conteneur principal - assure une base stable */
.bg-white {
    position: relative;
    overflow: hidden;
}

/* Scrollbar uniquement sur la zone des images */
.images-container {
    scrollbar-width: auto; /* Firefox */
    -ms-overflow-style: scrollbar; /* IE et Edge */
    scroll-behavior: smooth; /* Améliore l'expérience de scroll */
    /* Pour iOS et mobile */
    -webkit-overflow-scrolling: touch;
    /* Force le hardware acceleration pour un scroll plus fluide */
    transform: translateZ(0);
    backface-visibility: hidden;
    perspective: 1000;
}

/* Webkit scrollbar styling pour la zone des images */
.images-container::-webkit-scrollbar {
    width: 14px;
}

.images-container::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.images-container::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #cbd5e1 0%, #94a3b8 100%);
    border-radius: 8px;
    border: 2px solid #f8fafc;
    box-shadow: inset 0 1px 0 0 #e2e8f0;
}

.images-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #94a3b8 0%, #64748b 100%);
}

.images-container::-webkit-scrollbar-thumb:active {
    background: linear-gradient(180deg, #64748b 0%, #475569 100%);
}

/* Layout responsive amélioré */
@media (max-height: 700px) {
    .images-container {
        height: calc(100vh - 300px) !important;
        min-height: 350px !important;
    }
}

@media (max-height: 600px) {
    .images-container {
        height: calc(100vh - 280px) !important;
        min-height: 300px !important;
    }
}

/* Responsive pour écrans plus petits */
@media (max-height: 500px) {
    .images-container {
        height: calc(100vh - 240px) !important;
        min-height: 250px !important;
    }
}

/* Amélioration pour les petits écrans */
@media (max-width: 640px) {
    .grid {
        gap: 0.75rem;
    }
    
    /* Ajustement mobile pour la zone images */
    .images-container {
        height: calc(100vh - 320px) !important;
        min-height: 300px !important;
    }
    
    /* Scrollbar plus fine sur mobile */
    .images-container::-webkit-scrollbar {
        width: 8px;
    }
}

/* Optimisations pour tablettes */
@media (min-width: 641px) and (max-width: 1024px) {
    .images-container {
        height: calc(100vh - 340px) !important;
        min-height: 350px !important;
    }
}

/* Optimisations pour grands écrans */
@media (min-width: 1025px) {
    .images-container {
        height: calc(100vh - 350px) !important;
        min-height: 400px !important;
        max-height: calc(100vh - 180px) !important;
    }
}

/* Assure que le contenu est toujours visible */
@media (max-height: 400px) {
    .images-container {
        height: calc(100vh - 180px) !important;
        min-height: 180px !important;
    }
}
</style>
