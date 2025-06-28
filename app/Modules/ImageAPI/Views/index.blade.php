@extends('layouts.admin')

@section('title', 'Configuration de l\'API d\'images')

@section('content')

@php 
    $images = App\Models\ImageApi::all()->where("group_id", null);
    $folder = App\Models\ImageApiGroup::where('group_id', '!=', null)->get();
    $search = request('search', '');
    $filter = request('filter', 'all');
    $viewMode = request('view', 'grid'); 
    
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
@endphp

<div class="container mx-auto p-6 ">
    
    <h1 class="text-2xl font-bold mb-4">Gestion des Images</h1>
    <p class="mb-4">Gérez la visibilité et l'organisation de vos images</p>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="flex items-center mb-2">
                <div class="w-8 h-8 rounded-md bg-blue-100 flex items-center justify-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-600">Total des images</h3>
            </div>
            <p class="text-3xl font-bold text-gray-900 ml-10">{{ App\Models\ImageApi::count() }}</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="flex items-center mb-2">
                <div class="w-8 h-8 rounded-md bg-green-100 flex items-center justify-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-600">Images publiques</h3>
            </div>
            <p class="text-3xl font-bold text-gray-900 ml-10">{{ $publicCount }}</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="flex items-center mb-2">
                <div class="w-8 h-8 rounded-md bg-indigo-100 flex items-center justify-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-600">Images privées</h3>
            </div>
            <p class="text-3xl font-bold text-gray-900 ml-10">{{ $privateCount }}</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="flex items-center mb-2">
                <div class="w-8 h-8 rounded-md bg-amber-100 flex items-center justify-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-600">Tags uniques</h3>
            </div>
            <p class="text-3xl font-bold text-gray-900 ml-10">{{ App\Models\ImageApi::distinct('tags')->count() }}</p>
        </div>
    </div>

    <form action="{{ url()->current() }}" method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 bg-white p-4 rounded shadow">
        <div class="flex flex-col sm:flex-row gap-4 flex-1">
            <div class="relative flex-1 max-w-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input type="text" name="search" value="{{ $search }}" class="w-full pl-10 py-2 px-3 border rounded-md" placeholder="Rechercher des images...">
            </div>
            
            <select name="filter" class="w-48 border rounded-md px-3 py-2" onchange="this.form.submit()">
                <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Toutes les images</option>
                <option value="public" {{ $filter == 'public' ? 'selected' : '' }}>Images publiées</option>
                <option value="private" {{ $filter == 'private' ? 'selected' : '' }}>Images privées</option>
            </select>
            
            <!-- Champ caché pour conserver le mode d'affichage lors de la soumission du formulaire -->
            <input type="hidden" name="view" value="{{ $viewMode }}">
        </div>
        
        <div class="flex items-center gap-2">
            <div class="flex bg-gray-100 rounded-lg p-1">
                <a href="{{ url()->current() }}?search={{ $search }}&filter={{ $filter }}&view=grid" class="rounded-md px-3 h-8 {{ $viewMode == 'grid' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                        <path d="M3 9h18"></path>
                        <path d="M3 15h18"></path>
                        <path d="M9 3v18"></path>
                        <path d="M15 3v18"></path>
                    </svg>
                </a>
                <a href="{{ url()->current() }}?search={{ $search }}&filter={{ $filter }}&view=list" class="rounded-md px-3 h-8 {{ $viewMode == 'list' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M3 12h.01"></path>
                        <path d="M3 18h.01"></path>
                        <path d="M3 6h.01"></path>
                        <path d="M8 12h13"></path>
                        <path d="M8 18h13"></path>
                        <path d="M8 6h13"></path>
                    </svg>
                </a>
            </div>
            <!-- Bouton pour ouvrir le modal d'ajout d'images -->
            <button type="button" id="openAddImageModal" class="bg-blue-600 hover:bg-blue-700 text-white rounded-md h-10 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2 inline">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                Ajouter des images
            </button>
            <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md h-10 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1 inline">
                    <path d="m21 21-6.05-6.05m0 0a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z"></path>
                </svg>
                Filtrer
            </button>
        </div>
    </form>

    @if (session('message') || session('error') || session('success') || session('warning') || session('info'))
        @php
            $type = 'success';
            $message = '';
            $bgClass = 'bg-green-50';
            $textClass = 'text-green-800';
            $iconBgClass = 'bg-green-100';
            $iconTextClass = 'text-green-500';
            $iconHoverClass = 'hover:bg-green-200';
            $darkBgClass = 'dark:bg-green-800';
            $darkTextClass = 'dark:text-green-200';
            $darkHoverClass = 'dark:hover:bg-green-700';
            $icon = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>';
            
            if (session('error')) {
                $type = 'error';
                $message = session('error');
                $bgClass = 'bg-red-50';
                $textClass = 'text-red-800';
                $iconBgClass = 'bg-red-100';
                $iconTextClass = 'text-red-500';
                $iconHoverClass = 'hover:bg-red-200';
                $darkBgClass = 'dark:bg-red-800';
                $darkTextClass = 'dark:text-red-200';
                $darkHoverClass = 'dark:hover:bg-red-700';
                $icon = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.5 13L10 10l-3.5 3.5-1-1L9 9 5.5 5.5l1-1L10 8l3.5-3.5 1 1L11 9l3.5 3.5-1 1Z"/>';
            } elseif (session('warning')) {
                $type = 'warning';
                $message = session('warning');
                $bgClass = 'bg-yellow-50';
                $textClass = 'text-yellow-800';
                $iconBgClass = 'bg-yellow-100';
                $iconTextClass = 'text-yellow-500';
                $iconHoverClass = 'hover:bg-yellow-200';
                $darkBgClass = 'dark:bg-yellow-800';
                $darkTextClass = 'dark:text-yellow-200';
                $darkHoverClass = 'dark:hover:bg-yellow-700';
                $icon = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>';
            } elseif (session('info')) {
                $type = 'info';
                $message = session('info');
                $bgClass = 'bg-blue-50';
                $textClass = 'text-blue-800';
                $iconBgClass = 'bg-blue-100';
                $iconTextClass = 'text-blue-500';
                $iconHoverClass = 'hover:bg-blue-200';
                $darkBgClass = 'dark:bg-blue-800';
                $darkTextClass = 'dark:text-blue-200';
                $darkHoverClass = 'dark:hover:bg-blue-700';
                $icon = '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>';
            } else {
                $message = session('message') ?? session('success');
            }
        @endphp
        
        <div id="notification" class="fixed bottom-4 right-4 z-50 flex flex-col p-4 mb-4 rounded-lg shadow-md backdrop-blur-sm {{ str_replace('bg-', 'bg-opacity-70 bg-', $bgClass) }} {{ $darkBgClass }} max-w-xs border border-{{ substr($iconTextClass, 5) }}/10 transition-all duration-300 ease-in-out" role="alert">
            <div class="flex items-center">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 {{ $iconTextClass }} {{ str_replace('bg-', 'bg-opacity-70 bg-', $iconBgClass) }} rounded-lg {{ $darkBgClass }} {{ $darkTextClass }}">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        {!! $icon !!}
                    </svg>
                    <span class="sr-only">{{ ucfirst($type) }} icon</span>
                </div>
                <div class="ml-3 text-sm font-normal {{ $textClass }} {{ $darkTextClass }}">{{ $message }}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 {{ str_replace('bg-', 'bg-opacity-70 bg-', $bgClass) }} {{ $iconTextClass }} rounded-lg focus:ring-2 focus:ring-{{ substr($iconTextClass, 5) }} p-1.5 {{ $iconHoverClass }} inline-flex items-center justify-center h-8 w-8 {{ $darkBgClass }} {{ $darkTextClass }} {{ $darkHoverClass }}" onclick="closeNotification()" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <div class="w-full bg-gray-200 bg-opacity-40 rounded-full h-1.5 mt-3 overflow-hidden">
                <div id="notification-progress" class="h-full rounded-full {{ substr($iconBgClass, 3) }}" style="width: 100%;"></div>
            </div>
        </div>

        <script>
            function closeNotification() {
                document.getElementById('notification').style.display = 'none';
            }

            // Animation de la barre de progression
            const progressBar = document.getElementById('notification-progress');
            const notificationDuration = 5000; // 5 secondes
            
            // Force le navigateur à recalculer le DOM avant de lancer l'animation
            void progressBar.offsetWidth;
            
            // Appliquer l'animation CSS directement
            progressBar.style.transition = `width ${notificationDuration}ms linear`;
            progressBar.style.width = '0%';
            
            // Cacher la notification après le délai
            setTimeout(function() {
                closeNotification();
            }, notificationDuration);
        </script>

        <style>
            /* Assurer que la barre de progression fonctionne bien */
            #notification-progress {
                width: 100%;
                animation: progress-shrink 5s linear forwards;
            }
            
            @keyframes progress-shrink {
                0% { width: 100%; }
                100% { width: 0%; }
            }
        </style>
    @endif

    @if($viewMode == 'grid')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @else
    <div class="flex flex-col gap-3">
    @endif
        @forelse($images as $image)
            @if($viewMode == 'grid')
            <div class="bg-white rounded-lg border   border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="relative aspect-video bg-gray-100">
                    <img src="{{ asset('storage/' . $image->path) }}"  
                    alt="{{ $image->alt_text ?? 'Image ' . $image->id }}" 
                    class="w-full h-full object-cover">
                    <div class="absolute top-2 right-2">
                        <div class="inline-flex items-center rounded-full px-2.5 py-0.5 font-semibold text-xs backdrop-blur-sm bg-opacity-70 {{ $image->status == 'public' ? 'bg-blue-500 bg-opacity-60 text-white' : 'bg-gray-500 bg-opacity-60 text-white' }}">
                            @if ($image->status == "public") 
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1">
                                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"></path>
                                    <path d="M12 6v6l4.5 2.25"></path>
                                </svg>
                                Publiée
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1">
                                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"></path>
                                    <path d="M12 6v6l-4.5 2.25"></path>
                                </svg>
                                Privée
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $image->Name ?? 'image-' . $image->id . '.jpg' }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                @php
                                    $filesize = file_exists(public_path(str_replace(asset(''), '', $image->path))) 
                                        ? round(filesize(public_path(str_replace(asset(''), '', $image->path))) / (1024 * 1024), 2) 
                                        : 0;
                                @endphp
                                Modifié le: {{ $image->updated_at }}  - {{ $filesize }} MB
                            </p>
                        </div>
                            <a href="#" class="inline-flex items-center text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 py-1 px-2 rounded transition-colors" onclick="openEditModal({{ $image->id }}, '{{ $image->Name }}', '{{ $image->tags }}', '{{ $image->description ?? '' }}', '{{ $image->alt_text ?? '' }}', '{{ $image->status }}', '{{ asset('storage/' . $image->path) }}'); return false;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                    <path d="m15 5 4 4"></path>
                                </svg>
                                Modifier
                            </a>

                        <div class="relative inline-block overflow-y-full">
                        <button onclick="openMoreMenu(event)" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium rounded-md h-8 w-8 p-0 hover:bg-accent hover:text-accent-foreground" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="12" cy="5" r="1"></circle>
                                <circle cx="12" cy="19" r="1"></circle>
                            </svg>
                        </button>
                            <section class="more-menu hidden absolute right-0 z-50 mt-2 w-48 bg-white rounded-md shadow-lg py-1 max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">                            
                            <button onclick="Copylink('{{ $image->token }}')" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                <svg class="inline-block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"/>
                                    <line x1="8" y1="12" x2="16" y2="12"/>
                                </svg>
                                Copier le lien d'intégration
                            </button>
                            <script>
                                function Copylink(token) {
                                    const embedLink = `{{ url('/images/token') }}/${token}`;
                                    
                                    const tempTextarea = document.createElement('textarea');
                                    tempTextarea.value = embedLink;
                                    tempTextarea.style.position = 'absolute';
                                    tempTextarea.style.left = '-9999px'; 
                                    document.body.appendChild(tempTextarea);
                                    tempTextarea.select();

                                    try {
                                        const success = document.execCommand('copy');
                                        if (success) {
                                            const notification = document.createElement('div');
                                            notification.className = 'fixed bottom-4 right-4 z-50 bg-green-500 text-white p-3 rounded shadow-lg transform translate-x-full opacity-0 transition-all duration-500';
                                            notification.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block w-4 h-4 mr-2 animate-bounce"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg> Lien copié avec succès !`;
                                            document.body.appendChild(notification);
                                            
                                            void notification.offsetWidth;
                                            
                                            notification.classList.remove('translate-x-full', 'opacity-0');
                                            notification.classList.add('translate-x-0', 'opacity-100');
                                            
                                            setTimeout(() => {
                                                // Trigger exit animation
                                                notification.classList.remove('translate-x-0', 'opacity-100');
                                                notification.classList.add('translate-y-full', 'opacity-0');
                                                
                                                setTimeout(() => {
                                                    document.body.removeChild(notification);
                                                }, 500);
                                            }, 3000);
                                        } else {
                                            console.error('Échec de la copie du lien.');
                                        }
                                        
                                    } catch (err) {
                                        console.error('Erreur lors de la copie du lien : ', err);
                                    }
                                    document.body.removeChild(tempTextarea);
                                }


                            </script>

                            
                            
                            <button onclick="showTokenModal('{{ $image->token }}')" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                <svg class="inline-block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                </svg>
                                Voir token
                            </button>

                            <div id="tokenModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                <div class="bg-white rounded-lg p-6 max-w-sm w-full">
                                    <h3 class="text-lg font-semibold mb-4">Token de l'image</h3>
                                    <p id="tokenModalContent" class="break-all text-gray-700"></p>
                                    <div class="mt-4 text-right">
                                        <button onclick="closeTokenModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Fermer</button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function showTokenModal(token) {
                                    document.getElementById('tokenModalContent').textContent = token;
                                    document.getElementById('tokenModal').classList.remove('hidden');
                                }
                                
                                function closeTokenModal() {
                                    document.getElementById('tokenModal').classList.add('hidden');
                                }
                            </script>
                            <a href="{{ url('/images/token/' . $image->token) }}" target="_blank" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                <svg class="inline-block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                                Voir l'image
                            </a>

                            
                            
                        </section>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center space-x-2">
                            @if ($image->status == "public") 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-green-600">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <span class="text-xs text-gray-600">Visible publiquement</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-gray-400">
                                    <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"></path>
                                    <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"></path>
                                    <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"></path>
                                    <path d="m2 2 20 20"></path>
                                </svg>
                                <span class="text-xs text-gray-600">Accès restreint</span>
                            @endif
                        </div>
                    </div>
                    @if(!empty($image->tags))
                    <div class="mt-3 flex flex-wrap gap-1">
                        @foreach(is_array($image->tags) ? $image->tags : explode(',', $image->tags) as $tag)
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-800">
                                {{ trim($tag) }}
                            </span>
                        @endforeach
                    </div>
                    @endif
                    <div class="text-xs text-gray-500 mt-10">
                        ID: {{ $image->id }}
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200 flex">
                <div class="relative w-36 h-24 bg-gray-100">
                
                <img src="{{ asset('storage/' . $image->path) }}"  
                    alt="{{ $image->alt_text ?? 'Image ' . $image->id }}" 
                    class="w-full h-full object-cover">

                </div>
                <div class="p-4 flex-grow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $image->Name ?? 'image-' . $image->id . '.jpg' }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                @php
                                    $filesize = file_exists(storage_path('app/public/' . $image->path)) 
                                        ? round(filesize(storage_path('app/public/' . $image->path)) / (1024 * 1024), 2) 
                                        : 0;
                                @endphp
                                Modifié le: {{ $image->updated_at }}  - {{ $filesize }} MB
                            </p>
                            
                            <div class="flex items-center space-x-2 mt-2">
                                <div class="inline-flex items-center rounded-full px-2 py-0.5 font-semibold text-xs {{ $image->status == 'public' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                    @if ($image->status == "public") 
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3 mr-1">
                                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        Publiée
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3 mr-1">
                                            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"></path>
                                            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"></path>
                                            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"></path>
                                            <path d="m2 2 20 20"></path>
                                        </svg>
                                        Privée
                                    @endif
                                </div>
                                
                                @if(!empty($image->tags))
                                <div class="flex flex-wrap gap-1">
                                    @foreach(is_array($image->tags) ? $image->tags : explode(',', $image->tags) as $tag)
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-800">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                                @endif
                                <span class="text-xs text-gray-500">ID: {{ $image->id }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="#" class="inline-flex items-center text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 py-1 px-2 rounded transition-colors" onclick="openEditModal({{ $image->id }}, '{{ $image->Name }}', '{{ $image->tags }}', '{{ $image->description ?? '' }}', '{{ $image->alt_text ?? '' }}', '{{ $image->status }}', '{{ asset('storage/' . $image->path) }}'); return false;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                    <path d="m15 5 4 4"></path>
                                </svg>
                                Modifier
                            </a>
                            <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium rounded-md h-8 w-8 p-0 hover:bg-accent hover:text-accent-foreground" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="12" cy="5" r="1"></circle>
                                    <circle cx="12" cy="19" r="1"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-span-3 p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-400">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="8" y1="13" x2="16" y2="13"></line>
                    <line x1="8" y1="17" x2="16" y2="17"></line>
                    <line x1="10" y1="9" x2="14" y2="9"></line>
                </svg>
                <p class="text-gray-500 mb-2">Aucune image ne correspond à vos critères de recherche</p>
                <a href="{{ url()->current() }}" class="text-blue-600 hover:underline">Réinitialiser les filtres</a>
            </div>
        @endforelse
    </div>
    
    <div class="flex items-center gap-2 mt-4">
        <span class="text-sm text-gray-600">Résultats :</span>
        <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold">
            {{ count($images) }} image(s)
        </div>
    </div>



<div role="dialog" id="addImageModal" aria-describedby="modalDescription" aria-labelledby="modalTitle" class="hidden fixed left-1/2 top-1/2 z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border p-6 shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto transform transition-all duration-300 ease-out scale-95 opacity-0">
    <form action="{{ route('personnels.ImageAPI.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-col space-y-1.5 text-center sm:text-left">
            <h2 id="modalTitle" class="text-lg font-semibold leading-none tracking-tight flex items-center gap-2 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M16 5h6"></path>
                    <path d="M19 2v6"></path>
                    <path d="M21 11.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7.5"></path>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                    <circle cx="9" cy="9" r="2"></circle>
                </svg>
                Ajouter des images
            </h2>
            <p id="modalDescription" class="text-sm text-muted-foreground">
                Glissez-déposez vos images ou cliquez pour les sélectionner.
            </p>
        </div>
        <div class="space-y-6">
            <div id="dropzone" class="relative border-2 border-dashed rounded-lg p-8 text-center transition-all duration-300 border-gray-300 hover:border-blue-400 hover:bg-blue-50/30 group">
                <input type="file" name="images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" id="image-upload">
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto transition-transform duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-blue-500 transition-transform duration-300 group-hover:rotate-12">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" x2="12" y1="3" y2="15"></line>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-medium text-gray-900">Glissez vos images ici</p>
                        <p class="text-sm text-gray-600 mt-1">
                            ou <button type="button" class="text-blue-600 hover:text-blue-500 font-medium transition-colors duration-200" onclick="document.getElementById('image-upload').click()">parcourez vos fichiers</button>
                        </p>
                    </div>
                    <div id="file-list" class="hidden flex flex-wrap gap-2 justify-center mt-2"></div>
                    <p class="text-xs text-gray-500">Formats supportés: JPG, PNG, GIF, WEBP (max 10MB par fichier)</p>
                </div>
            </div>
            <div class="space-y-4">
                <div class="transition-all duration-300 hover:translate-x-1">
                    <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                </div>
                <div class="transition-all duration-300 hover:translate-x-1">
                    <label for="tag" class="block text-sm font-medium text-gray-700">Tags</label>
                    <input type="text" name="tag" id="tag" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" placeholder="Séparés par des virgules">
                </div>
                <div class="transition-all duration-300 hover:translate-x-1">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"></textarea>
                </div>
                <div class="transition-all duration-300 hover:translate-x-1">
                    <label for="alt_text" class="block text-sm font-medium text-gray-700">Texte de remplacement</label>
                    <input type="text" name="alt_text" id="alt_text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                </div>
            </div>
            <div class="space-y-3">
                <label class="text-sm font-medium">Options globales</label>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:shadow-md transition-all duration-300">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Statut des images</p>
                        <p class="text-xs text-gray-600">Définit la visibilité par défaut</p>
                    </div>
                    <select name="status" class="flex h-10 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm w-32 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="public">Public</option>
                        <option value="private">Privé</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" id="cancelUpload" class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-gray-100 h-10 px-4 py-2 transition-all duration-200">
                    Annuler
                </button>
                <button type="submit" id="submitUpload" class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium text-white h-10 px-4 py-2 bg-blue-600 hover:bg-blue-700 transition-all duration-200 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" x2="12" y1="3" y2="15"></line>
                    </svg>
                    Ajouter
                </button>
            </div>
        </div>
        <button type="button" id="closeModal" class="absolute right-4 top-4 rounded-full opacity-70 transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 bg-gray-100 p-1 hover:bg-gray-200 transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
            <span class="sr-only">Close</span>
        </button>
    </form>
</div>


<!-- Add this modal at the end of your file, just after the addImageModal -->
<div role="dialog" id="editImageModal" aria-describedby="editModalDescription" aria-labelledby="editModalTitle" class="hidden fixed left-1/2 top-1/2 z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border p-6 shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto transform transition-all duration-300 ease-out scale-95 opacity-0">
    <form id="editImageForm" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="image_id" id="edit_image_id">
        <div class="flex flex-col space-y-1.5 text-center sm:text-left">
            <h2 id="editModalTitle" class="text-lg font-semibold leading-none tracking-tight flex items-center gap-2 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                    <path d="m15 5 4 4"></path>
                </svg>
                Modifier l'image
            </h2>
            <p id="editModalDescription" class="text-sm text-muted-foreground">
                Modifiez les détails de l'image
            </p>
        </div>
        <div class="space-y-6">
            <div class="relative aspect-video bg-gray-50 rounded-lg overflow-hidden border transition-all duration-300 hover:shadow-md group">
                <img id="edit_image_preview" src="" alt="Aperçu de l'image" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-b from-black/0 to-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                    <span class="text-white text-sm font-medium">Aperçu</span>
                </div>
                <div class="absolute top-2 right-2">
                    <div class="inline-flex items-center">
                        <label for="edit-image-upload" class="cursor-pointer p-2 bg-white/80 backdrop-blur-sm rounded-full hover:bg-white shadow-sm transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600">
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                                <path d="m18 2 4 4-10 10H8v-4L18 2z"></path>
                            </svg>
                            <span class="sr-only">Changer l'image</span>
                        </label>
                        <input type="file" id="edit-image-upload" name="image" accept="image/*" class="hidden" onchange="previewEditImage(this)">
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="transition-all duration-300 hover:translate-x-1">
                    <label for="edit_title" class="block text-sm font-medium text-gray-700">Titre</label>
                    <input type="text" name="title" id="edit_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                </div>
                <div class="transition-all duration-300 hover:translate-x-1">
                    <label for="edit_tag" class="block text-sm font-medium text-gray-700">Tags (séparés par des virgules)</label>
                    <input type="text" name="tag" id="edit_tag" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                </div>
                <div class="transition-all duration-300 hover:translate-x-1">
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"></textarea>
                </div>
                <div class="transition-all duration-300 hover:translate-x-1">
                    <label for="edit_alt_text" class="block text-sm font-medium text-gray-700">Texte de remplacement</label>
                    <input type="text" name="alt_text" id="edit_alt_text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                </div>
            </div>
            <div class="space-y-3">
                <label class="text-sm font-medium">Options</label>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:shadow-md transition-all duration-300">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Statut de l'image</p>
                        <p class="text-xs text-gray-600">Définit la visibilité de l'image</p>
                    </div>
                    <select name="status" id="edit_status" class="flex h-10 items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm w-32 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="public">Public</option>
                        <option value="private">Privé</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" id="cancelEditImage" class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 transition-all duration-200 h-10 px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                    Annuler
                </button>
                <button class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 transition-all duration-200 h-10 px-4 py-2" type="button" onclick="deleteImage(document.getElementById('edit_image_id').value)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M3 6h18"></path>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                        <path d="M8 6V4c0-1 1-2 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>   
                    Suppresion
                </button>

                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium text-white h-10 px-4 py-2 bg-blue-600 hover:bg-blue-700 transition-all duration-200 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </div>
        <button type="button" id="closeEditModal" class="absolute right-4 top-4 rounded-full opacity-70 transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 bg-gray-100 p-1 hover:bg-gray-200 transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
            <span class="sr-only">Close</span>
        </button>
    </form>
</div>
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
</form>


<script>

document.querySelector('input[name="images[]"]').addEventListener('change', function() {
    const submitButton = document.getElementById('submitUpload');
    const fileList = document.getElementById('file-list');
    
    if (this.files.length > 0) {
        submitButton.disabled = false;
        
        // Show file preview thumbnails
        fileList.innerHTML = '';
        fileList.classList.remove('hidden');
        
        Array.from(this.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const thumbnail = document.createElement('div');
                    thumbnail.className = 'relative w-16 h-16 bg-gray-100 rounded-md overflow-hidden border border-gray-200';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    img.alt = file.name;
                    
                    thumbnail.appendChild(img);
                    fileList.appendChild(thumbnail);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        submitButton.disabled = true;
        fileList.innerHTML = '';
        fileList.classList.add('hidden');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('image-upload');
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    // Highlight drop zone when dragging file over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropzone.classList.add('border-blue-500', 'bg-blue-50/50');
    }
    
    function unhighlight() {
        dropzone.classList.remove('border-blue-500', 'bg-blue-50/50');
    }
    
    // Handle dropped files
    dropzone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        
        // Trigger the change event
        const event = new Event('change');
        fileInput.dispatchEvent(event);
    }
});

// Function to show modal with animation
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    
    // Trigger reflow to ensure the animation works
    void modal.offsetWidth;
    
    // Apply the active state styles
    modal.classList.remove('scale-95', 'opacity-0');
    modal.classList.add('scale-100', 'opacity-100');
}

// Function to hide modal with animation
function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    
    // Apply the inactive state styles
    modal.classList.remove('scale-100', 'opacity-100');
    modal.classList.add('scale-95', 'opacity-0');
    
    // Wait for animation to finish before hiding
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

document.getElementById('closeModal').addEventListener('click', function() {
    hideModal('addImageModal');
});

document.getElementById('cancelUpload').addEventListener('click', function() {
    hideModal('addImageModal');
});

document.getElementById('openAddImageModal').addEventListener('click', function() {
    showModal('addImageModal');
});

function openEditModal(id, name, tags, description, altText, status, imagePath) {
    document.getElementById('editImageForm').action = "{{ route('personnels.ImageAPI.update') }}";
    document.getElementById('editImageForm').method = 'POST';
    
    document.getElementById('edit_image_id').value = id;
    document.getElementById('edit_title').value = name || '';
    document.getElementById('edit_tag').value = tags || '';
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_alt_text').value = altText || '';
    document.getElementById('edit_status').value = status || 'public';
    document.getElementById('edit_image_preview').src = imagePath;
    
    showModal('editImageModal');
}

document.getElementById('closeEditModal').addEventListener('click', function() {
    hideModal('editImageModal');
});

document.getElementById('cancelEditImage').addEventListener('click', function() {
    hideModal('editImageModal');
});

// Add ripple effect to buttons
function createRipple(event) {
    const button = event.currentTarget;
    
    const circle = document.createElement('span');
    const diameter = Math.max(button.clientWidth, button.clientHeight);
    const radius = diameter / 2;
    
    circle.style.width = circle.style.height = `${diameter}px`;
    circle.style.left = `${event.clientX - (button.getBoundingClientRect().left + radius)}px`;
    circle.style.top = `${event.clientY - (button.getBoundingClientRect().top + radius)}px`;
    circle.classList.add('ripple');
    
    const ripple = button.querySelector('.ripple');
    if (ripple) {
        ripple.remove();
    }
    
    button.appendChild(circle);
}

const buttons = document.querySelectorAll('button:not([disabled])');
buttons.forEach(button => {
    button.addEventListener('click', createRipple);
});

</script>

<style>
    /* Add ripple effect styles */
    button {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s linear;
        background-color: rgba(255, 255, 255, 0.7);
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    /* Modal animations */
    #addImageModal, #editImageModal {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .scale-100 {
        transform: translate(-50%, -50%) scale(1) !important;
    }
    
    .scale-95 {
        transform: translate(-50%, -50%) scale(0.95) !important;
    }
    
    .opacity-100 {
        opacity: 1 !important;
    }
    
    /* Form field animations */
    input, textarea, select {
        transition: all 0.2s ease;
    }
    
    input:focus, textarea:focus, select:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>

<script>
function openMoreMenu(event) {
    event.stopPropagation();

    const button = event.currentTarget;
    const card = button.closest('.bg-white');
    if (!card) return;

    const menu = card.querySelector('.more-menu');
    if (!menu) return;

    // Cacher tous les autres menus
    document.querySelectorAll('.more-menu').forEach(m => {
        if (m !== menu) {
            m.classList.add('hidden');
        }
    });

    const isHidden = menu.classList.contains('hidden');

    if (isHidden) {
        menu.classList.remove('hidden');

        function closeMenu(e) {
            if (!menu.contains(e.target) && !button.contains(e.target)) {
                menu.classList.add('hidden');
                document.removeEventListener('click', closeMenu);
            }
        }

        document.addEventListener('click', closeMenu);
    } else {
        menu.classList.add('hidden');
    }
}

function deleteImage(id) {
    if(confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        let form = document.getElementById('deleteForm');
        form.action = "{{ route('personnels.ImageAPI.destroy', ['id' => ':id']) }}".replace(':id', id);
        
        let idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'image_id'; 
        idInput.value = id;
        form.appendChild(idInput);
        
        // Add method spoofing for DELETE
        let methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        // Add token
        let tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = 'token';
        tokenInput.value = '{{ Str::random(32) }}';
        form.appendChild(tokenInput);
        
        form.submit();
    }
}

function previewEditImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('edit_image_preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}</script>

@endsection