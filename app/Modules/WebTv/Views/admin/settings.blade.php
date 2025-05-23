@extends('layouts.admin')

@section('title', 'Paramètres de la WebTV')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Paramètres de la WebTV</h1>
                <a href="{{ route('personnels.modules.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux modules
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <form action="{{ route('personnels.WebTv.settings.save') }}" method="POST">
                    @csrf
                    
                    <!-- Onglets -->
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex" aria-label="Tabs">
                            <button type="button" class="tab-button active-tab border-blue-500 text-blue-600 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-target="general-section">
                                Général
                            </button>
                            <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-target="channel-section">
                                Chaîne
                            </button>
                            <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-target="display-section">
                                Affichage
                            </button>
                            <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-target="advanced-section">
                                Avancé
                            </button>
                        </nav>
                    </div>
                    
                    <!-- Section Général -->
                    <div id="general-section" class="tab-content px-4 py-5 sm:p-6">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Paramètres généraux</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Configurez les paramètres généraux de la WebTv.
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-4">
                                    <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                                    <div class="mt-1">
                                        <input type="text" name="title" id="title" value="{{ old('title', $config['title'] ?? 'WebTV') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Le titre qui apparaîtra sur la page principale de la WebTv.</p>
                                    @error('title')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <div class="mt-1">
                                        <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description', $config['description'] ?? '') }}</textarea>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Une brève description de votre WebTv.</p>
                                    @error('description')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-3">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                    <div class="mt-1">
                                        <select id="status" name="status" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="active" {{ (old('status', $config['status'] ?? 'active') == 'active') ? 'selected' : '' }}>Actif</option>
                                            <option value="inactive" {{ (old('status', $config['status'] ?? '') == 'inactive') ? 'selected' : '' }}>Inactif</option>
                                        </select>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Détermine si la WebTV est accessible aux utilisateurs.</p>
                                    @error('status')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Chaîne -->
                    <div id="channel-section" class="tab-content px-4 py-5 sm:p-6 hidden">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Paramètres de la chaîne</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Configurez les informations de votre chaîne YouTube.
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="streaming_service" class="block text-sm font-medium text-gray-700">Service de streaming</label>
                                    <div class="mt-1">
                                        <select id="streaming_service" name="streaming_service" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="youtube" {{ (old('streaming_service', $config['streaming_service'] ?? 'youtube') == 'youtube') ? 'selected' : '' }}>YouTube</option>
                                            <option value="vimeo" {{ (old('streaming_service', $config['streaming_service'] ?? '') == 'vimeo') ? 'selected' : '' }}>Vimeo</option>
                                            <option value="dailymotion" {{ (old('streaming_service', $config['streaming_service'] ?? '') == 'dailymotion') ? 'selected' : '' }}>Dailymotion</option>
                                            <option value="custom" {{ (old('streaming_service', $config['streaming_service'] ?? '') == 'custom') ? 'selected' : '' }}>Personnalisé</option>
                                        </select>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Plateforme d'hébergement de vos vidéos.</p>
                                    @error('streaming_service')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <label for="channel_id" class="block text-sm font-medium text-gray-700">ID de la chaîne</label>
                                    <div class="mt-1">
                                        <input type="text" name="channel_id" id="channel_id" value="{{ old('channel_id', $config['channel_id'] ?? 'UCmJIRE7hK5PJTk-tUrR-lMA') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500" id="channel_id_help">
                                        L'identifiant de votre chaîne YouTube. Pour YouTube, il est sous la forme "UC...".
                                        <br>Vous pouvez le trouver dans l'URL de votre chaîne YouTube: https://www.youtube.com/channel/<strong>votre-ID-ici</strong>
                                    </p>
                                    @error('channel_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <label for="api_key" class="block text-sm font-medium text-gray-700">Clé API</label>
                                    <div class="mt-1">
                                        <input type="text" name="api_key" id="api_key" value="{{ old('api_key', $config['api_key'] ?? '') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500" id="api_key_help">
                                        Clé API pour accéder aux services YouTube Data API.
                                        <br>Vous pouvez en créer une sur la <a href="https://console.developers.google.com/" target="_blank" class="text-blue-600 hover:underline">Console Google Cloud</a>.
                                    </p>
                                    @error('api_key')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Affichage -->
                    <div id="display-section" class="tab-content px-4 py-5 sm:p-6 hidden">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Paramètres d'affichage</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Configurez l'apparence et le comportement de la WebTv.
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="max_videos" class="block text-sm font-medium text-gray-700">Nombre maximum de vidéos</label>
                                    <div class="mt-1">
                                        <input type="number" name="max_videos" id="max_videos" min="1" max="50" value="{{ old('max_videos', $config['max_videos'] ?? 10) }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Nombre maximum de vidéos à afficher.</p>
                                    @error('max_videos')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-3">
                                    <label for="default_resolution" class="block text-sm font-medium text-gray-700">Résolution par défaut</label>
                                    <div class="mt-1">
                                        <select id="default_resolution" name="default_resolution" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="360p" {{ (old('default_resolution', $config['default_resolution'] ?? '720p') == '360p') ? 'selected' : '' }}>360p</option>
                                            <option value="480p" {{ (old('default_resolution', $config['default_resolution'] ?? '720p') == '480p') ? 'selected' : '' }}>480p</option>
                                            <option value="720p" {{ (old('default_resolution', $config['default_resolution'] ?? '720p') == '720p') ? 'selected' : '' }}>720p (HD)</option>
                                            <option value="1080p" {{ (old('default_resolution', $config['default_resolution'] ?? '720p') == '1080p') ? 'selected' : '' }}>1080p (Full HD)</option>
                                        </select>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">La résolution par défaut des vidéos.</p>
                                    @error('default_resolution')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="autoplay" name="autoplay" type="checkbox" value="1" {{ old('autoplay', $config['autoplay'] ?? false) ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="autoplay" class="font-medium text-gray-700">Lecture automatique</label>
                                                <p class="text-gray-500">Les vidéos démarreront automatiquement (si supporté par le navigateur).</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show_controls" name="show_controls" type="checkbox" value="1" {{ old('show_controls', $config['show_controls'] ?? true) ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show_controls" class="font-medium text-gray-700">Afficher les contrôles</label>
                                                <p class="text-gray-500">Afficher les boutons de contrôle du lecteur (play, pause, volume, etc.).</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show_related" name="show_related" type="checkbox" value="1" {{ old('show_related', $config['show_related'] ?? false) ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show_related" class="font-medium text-gray-700">Afficher les vidéos liées</label>
                                                <p class="text-gray-500">Afficher les vidéos suggérées à la fin de la lecture.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show_title" name="show_title" type="checkbox" value="1" {{ old('show_title', $config['show_title'] ?? true) ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show_title" class="font-medium text-gray-700">Afficher les titres</label>
                                                <p class="text-gray-500">Afficher les titres des vidéos dans le lecteur.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Avancé -->
                    <div id="advanced-section" class="tab-content px-4 py-5 sm:p-6 hidden">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Paramètres avancés</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Options avancées pour la performance et la personnalisation.
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="cache_duration" class="block text-sm font-medium text-gray-700">Durée du cache (minutes)</label>
                                    <div class="mt-1">
                                        <input type="number" name="cache_duration" id="cache_duration" min="0" max="1440" value="{{ old('cache_duration', $config['cache_duration'] ?? 60) }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Durée (en minutes) pendant laquelle les données des vidéos seront mises en cache.
                                        <br>Mettez 0 pour désactiver le cache.
                                    </p>
                                    @error('cache_duration')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="sm:col-span-6">
                                    <label for="custom_css" class="block text-sm font-medium text-gray-700">CSS personnalisé</label>
                                    <div class="mt-1">
                                        <textarea id="custom_css" name="custom_css" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md font-mono">{{ old('custom_css', $config['custom_css'] ?? '') }}</textarea>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">CSS personnalisé pour modifier l'apparence de la WebTv.</p>
                                    @error('custom_css')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Enregistrer les paramètres
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des onglets
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Masquer tous les contenus d'onglets
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Désactiver tous les boutons d'onglets
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    
                    // Activer l'onglet sélectionné
                    this.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');
                    
                    // Afficher le contenu correspondant
                    const targetId = this.getAttribute('data-target');
                    document.getElementById(targetId).classList.remove('hidden');
                });
            });
            
            // Mise à jour des instructions selon le service sélectionné
            const streamingServiceSelect = document.getElementById('streaming_service');
            const channelIdHelp = document.getElementById('channel_id_help');
            const apiKeyHelp = document.getElementById('api_key_help');
            
            if (streamingServiceSelect) {
                streamingServiceSelect.addEventListener('change', function() {
                    const service = this.value;
                    
                    // Mettre à jour le libellé du champ ID de chaîne
                    const channelIdLabel = document.querySelector('label[for="channel_id"]');
                    
                    if (service === 'youtube') {
                        channelIdLabel.textContent = 'ID de la chaîne YouTube';
                        channelIdHelp.innerHTML = 'L\'identifiant de votre chaîne YouTube. Il est sous la forme "UC...".<br>Vous pouvez le trouver dans l\'URL de votre chaîne YouTube: https://www.youtube.com/channel/<strong>votre-ID-ici</strong>';
                        apiKeyHelp.innerHTML = 'Clé API pour accéder aux services YouTube Data API.<br>Vous pouvez en créer une sur la <a href="https://console.developers.google.com/" target="_blank" class="text-blue-600 hover:underline">Console Google Cloud</a>.';
                    } else if (service === 'vimeo') {
                        channelIdLabel.textContent = 'ID utilisateur Vimeo';
                        channelIdHelp.innerHTML = 'L\'identifiant de votre compte Vimeo.<br>Vous pouvez le trouver dans l\'URL de votre profil: https://vimeo.com/<strong>votre-ID-ici</strong>';
                        apiKeyHelp.innerHTML = 'Jeton d\'accès personnel Vimeo.<br>Vous pouvez en créer un sur la <a href="https://developer.vimeo.com/apps" target="_blank" class="text-blue-600 hover:underline">page des applications Vimeo</a>.';
                    } else if (service === 'dailymotion') {
                        channelIdLabel.textContent = 'ID utilisateur Dailymotion';
                        channelIdHelp.innerHTML = 'L\'identifiant de votre compte Dailymotion.<br>Vous pouvez le trouver dans l\'URL de votre profil: https://www.dailymotion.com/<strong>votre-ID-ici</strong>';
                        apiKeyHelp.innerHTML = 'Clé API pour accéder aux services Dailymotion API.<br>Vous pouvez en créer une sur la <a href="https://developer.dailymotion.com/" target="_blank" class="text-blue-600 hover:underline">page développeur Dailymotion</a>.';
                    } else {
                        channelIdLabel.textContent = 'Source personnalisée';
                        channelIdHelp.innerHTML = 'URL ou identifiant de votre source de vidéos personnalisée.';
                        apiKeyHelp.innerHTML = 'Clé API pour accéder à votre service personnalisé, si nécessaire.';
                    }
                });
                
                // Déclencher l'événement au chargement
                streamingServiceSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection 