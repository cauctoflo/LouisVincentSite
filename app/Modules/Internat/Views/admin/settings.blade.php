@extends('layouts.admin')

@section('title', 'Paramètres de l\'Internat')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Paramètres de l'Internat</h1>
                <a href="{{ route('personnels.modules.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux modules
                </a>
            </div>

            <!-- Messages d'alerte -->
            @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" class="close-alert inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
                                    <span class="sr-only">Fermer</span>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" class="close-alert inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50">
                                    <span class="sr-only">Fermer</span>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form action="{{ route('personnels.Internat.settings.save') }}" method="POST" class="divide-y divide-gray-200">
                    @csrf

                    <!-- Description -->
                    <div class="p-6 bg-white">
                        <div class="mb-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-align-left text-blue-500 mr-2"></i>
                                Description de l'Internat
                            </h2>
                            <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Décrivez l'internat et ses avantages...">{{ $config['description'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- Informations en 4 cases -->
                    <div class="p-6 bg-gradient-to-br from-blue-50 to-white">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Statistiques principales
                            </div>
                        </h2>
                        <div id="stats-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @for($i = 0; $i < 4; $i++)
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 stat-item">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-sm font-medium text-gray-900">Statistique {{ $i + 1 }}</h3>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Chiffre clé (pensez à l'unité) </label>
                                            <input type="text" name="informations[{{ $i }}][key_number]" 
                                                value="{{ $config['informations'][$i]['key_number'] ?? '' }}" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                                placeholder="ex: 150" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Phrase</label>
                                            <input type="text" name="informations[{{ $i }}][phrase]" 
                                                value="{{ $config['informations'][$i]['phrase'] ?? '' }}" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                                placeholder="ex: places disponibles" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Icône</label>
                                            <div class="flex items-center space-x-2">
                                                <div class="flex-grow relative">
                                                    <input type="text" name="informations[{{ $i }}][icon]" 
                                                        value="{{ $config['informations'][$i]['icon'] ?? '' }}" 
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-4 pr-12" 
                                                        placeholder="Code de l'icône" readonly>
                                                    <div class="absolute right-0 top-0 bottom-0 w-12 flex items-center justify-center bg-blue-50 border-l border-blue-100 rounded-r-md group-hover:bg-blue-100 transition-colors duration-200">
                                                        <i class="{{ $config['informations'][$i]['icon'] ?? 'fas fa-icons' }} text-lg text-blue-500"></i>
                                                    </div>
                                                </div>
                                                <button type="button" class="choose-icon-btn inline-flex items-center px-3 py-2 border border-blue-200 shadow-sm text-sm font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                    {{ isset($config['informations'][$i]['icon']) ? 'Changer l\'icône' : 'Ajouter une icône' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- URL vidéo -->
                    <div class="p-6 bg-white">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-video text-blue-500 mr-2"></i>
                            Vidéo de présentation
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">URL de la vidéo</label>
                                <input type="url" name="video_url" value="{{ $config['video']['url'] ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="https://...">
                                <p class="mt-1 text-sm text-gray-500">Insérez l'URL de la vidéo YouTube ou Vimeo</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Titre de la vidéo</label>
                                <input type="text" name="video_title" value="{{ $config['video']['title'] ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Titre de la vidéo">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description de la vidéo</label>
                                <input type="text" name="video_description" value="{{ $config['video']['description'] ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Description de la vidéo">
                            </div>
                        </div>
                    </div>

                    <!-- Contenu des points forts -->
                    <div class="p-6 bg-gradient-to-br from-blue-50 to-white">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-list text-blue-500 mr-2"></i>
                                Points forts de l'internat
                            </div>
                            <button type="button" id="addStrength" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter un point fort
                            </button>
                        </h2>
                        <div id="strengths-container" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if(isset($config['points_forts']) && count($config['points_forts']) > 0)
                                @foreach($config['points_forts'] as $index => $point_fort)
                                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 strength-item">
                                        <div class="flex justify-between items-start mb-4">
                                            <h3 class="text-sm font-medium text-gray-900">Point fort {{ $index + 1 }}</h3>
                                            <button type="button" class="delete-strength text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                                                <input type="text" name="points_forts[{{ $index }}][titre]" value="{{ $point_fort['titre'] }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="ex: Chambres confortables" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                                <textarea name="points_forts[{{ $index }}][description]" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Description du point fort..." required>{{ $point_fort['description'] }}</textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Icône</label>
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex-grow relative">
                                                        <input type="text" name="points_forts[{{ $index }}][icon]" 
                                                            value="{{ $point_fort['icon'] ?? '' }}" 
                                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-4 pr-12" 
                                                            placeholder="Code de l'icône" readonly>
                                                        <div class="absolute right-0 top-0 bottom-0 w-12 flex items-center justify-center bg-blue-50 border-l border-blue-100 rounded-r-md group-hover:bg-blue-100 transition-colors duration-200">
                                                            <i class="{{ $point_fort['icon'] ?? 'fas fa-icons' }} text-lg text-blue-500"></i>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="choose-icon-btn inline-flex items-center px-3 py-2 border border-blue-200 shadow-sm text-sm font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                        {{ isset($point_fort['icon']) ? 'Changer l\'icône' : 'Ajouter une icône' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Template pour un nouveau point fort -->
                        <template id="strength-template">
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 strength-item">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-sm font-medium text-gray-900">Nouveau point fort</h3>
                                    <button type="button" class="delete-strength text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                                        <input type="text" name="points_forts[INDEX][titre]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="ex: Chambres confortables" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                        <textarea name="points_forts[INDEX][description]" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Description du point fort..." required></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Icône</label>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-grow relative">
                                                <input type="text" name="points_forts[INDEX][icon]" 
                                                    value="{{ $point_fort['icon'] ?? '' }}" 
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-4 pr-12" 
                                                    placeholder="Code de l'icône" readonly>
                                                <div class="absolute right-0 top-0 bottom-0 w-12 flex items-center justify-center bg-blue-50 border-l border-blue-100 rounded-r-md group-hover:bg-blue-100 transition-colors duration-200">
                                                    <i class="{{ $point_fort['icon'] ?? 'fas fa-icons' }} text-lg text-blue-500"></i>
                                                </div>
                                            </div>
                                            <button type="button" class="choose-icon-btn inline-flex items-center px-3 py-2 border border-blue-200 shadow-sm text-sm font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                        {{ isset($point_fort['icon']) ? 'Changer l\'icône' : 'Ajouter une icône' }}
                                                    </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Témoignages -->
                    <div class="p-6 bg-white">
                        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-quote-right text-blue-500 mr-2"></i>
                                Témoignages d'élèves
                            </div>
                            <button type="button" id="addTestimonial" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter un témoignage
                            </button>
                        </h2>
                        <div id="testimonials-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($config['temoignages']) && count($config['temoignages']) > 0)
                                @foreach($config['temoignages'] as $index => $temoignage)
                                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 testimonial-item">
                                        <div class="flex justify-between items-start mb-4">
                                            <h3 class="text-sm font-medium text-gray-900">Témoignage {{ $index + 1 }}</h3>
                                            <button type="button" class="delete-testimonial text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'élève</label>
                                                <input type="text" name="temoignages[{{ $index }}][nom]" value="{{ $temoignage['nom'] }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="ex: Marie L." required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                                <input type="text" name="temoignages[{{ $index }}][statut]" value="{{ $temoignage['statut'] }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="ex: Élève en Terminale" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Témoignage</label>
                                                <textarea name="temoignages[{{ $index }}][texte]" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Témoignage de l'élève..." required>{{ $temoignage['texte'] }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Template pour un nouveau témoignage -->
                        <template id="testimonial-template">
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 testimonial-item">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-sm font-medium text-gray-900">Nouveau témoignage</h3>
                                    <button type="button" class="delete-testimonial text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'élève</label>
                                        <input type="text" name="temoignages[INDEX][nom]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="ex: Marie L." required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                        <input type="text" name="temoignages[INDEX][statut]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="ex: Élève en Terminale" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Témoignage</label>
                                        <textarea name="temoignages[INDEX][texte]" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Témoignage de l'élève..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Boutons de soumission -->
                    <div class="px-6 py-4 bg-gray-50 flex items-center justify-end space-x-3">
                        <a href="{{ route('personnels.modules.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Annuler
                        </a>
                        <button type="submit" id="submitForm" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const strengthsContainer = document.getElementById('strengths-container');
        const strengthTemplate = document.getElementById('strength-template');
        const addStrengthButton = document.getElementById('addStrength');
        let strengthCount = {{ isset($config['points_forts']) ? count($config['points_forts']) : 0 }};

        // Fonction pour vérifier s'il s'agit du dernier élément
        function isLastElement(element, selector) {
            return document.querySelectorAll(selector).length <= 1;
        }

        // Validation du formulaire
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher la soumission par défaut

            const strengthItems = document.querySelectorAll('.strength-item');
            const testimonialItems = document.querySelectorAll('.testimonial-item');
            let errorMessages = [];

            // Vérification du nombre minimum de points forts et témoignages
            if (strengthItems.length === 0) {
                errorMessages.push('Vous devez ajouter au moins un point fort');
            }

            if (testimonialItems.length === 0) {
                errorMessages.push('Vous devez ajouter au moins un témoignage');
            }

            // Vérification des champs des points forts
            strengthItems.forEach((item, index) => {
                const titre = item.querySelector('[name$="[titre]"]').value.trim();
                const description = item.querySelector('[name$="[description]"]').value.trim();
                
                if (!titre || !description) {
                    errorMessages.push(`Le point fort ${index + 1} doit avoir un titre et une description`);
                }
            });

            // Vérification des champs des témoignages
            testimonialItems.forEach((item, index) => {
                const nom = item.querySelector('[name$="[nom]"]').value.trim();
                const statut = item.querySelector('[name$="[statut]"]').value.trim();
                const texte = item.querySelector('[name$="[texte]"]').value.trim();
                
                if (!nom || !statut || !texte) {
                    errorMessages.push(`Le témoignage ${index + 1} doit être complet (nom, statut et texte)`);
                }
            });

            // Affichage des erreurs s'il y en a
            if (errorMessages.length > 0) {
                const errorHTML = errorMessages.join('\n');
                alert(errorHTML);
                return false;
            }

            // Si tout est valide, soumettre le formulaire
            form.submit();
        });

        // Fonction pour ajouter un nouveau point fort
        function addStrength(titre = '', description = '', icon = '') {
            const clone = strengthTemplate.content.cloneNode(true);
            
            // Mettre à jour les noms des champs avec l'index actuel
            clone.querySelectorAll('[name*="INDEX"]').forEach(input => {
                input.name = input.name.replace('INDEX', strengthCount);
            });

            // Ajouter le gestionnaire de suppression
            const deleteButton = clone.querySelector('.delete-strength');
            deleteButton.addEventListener('click', function() {
                const strengthItem = this.closest('.strength-item');
                
                // Empêcher la suppression si c'est le dernier point fort
                if (isLastElement(strengthItem, '.strength-item')) {
                    alert('Vous devez conserver au moins un point fort.');
                    return;
                }

                strengthItem.style.opacity = '0';
                strengthItem.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    strengthItem.remove();
                }, 300);
            });

            // Ajouter une animation d'entrée
            const strengthDiv = clone.querySelector('.strength-item');
            strengthDiv.style.opacity = '0';
            strengthDiv.style.transform = 'translateY(20px)';
            
            // Pré-remplir les champs si des valeurs sont fournies
            if (titre || description || icon) {
                clone.querySelector('[name$="[titre]"]').value = titre;
                clone.querySelector('[name$="[description]"]').value = description;
                clone.querySelector('[name$="[icon]"]').value = icon;
            }

            strengthsContainer.appendChild(clone);

            // Déclencher l'animation
            setTimeout(() => {
                strengthDiv.style.transition = 'all 0.3s ease-out';
                strengthDiv.style.opacity = '1';
                strengthDiv.style.transform = 'translateY(0)';
            }, 10);

            strengthCount++;
        }

        // Ajouter un gestionnaire d'événements pour le bouton d'ajout
        addStrengthButton.addEventListener('click', () => addStrength());

        // Ajouter les gestionnaires de suppression pour les points forts existants
        document.querySelectorAll('.delete-strength').forEach(button => {
            button.addEventListener('click', function() {
                const strengthItem = this.closest('.strength-item');
                
                // Empêcher la suppression si c'est le dernier point fort
                if (isLastElement(strengthItem, '.strength-item')) {
                    alert('Vous devez conserver au moins un point fort.');
                    return;
                }

                strengthItem.style.opacity = '0';
                strengthItem.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    strengthItem.remove();
                }, 300);
            });
        });
    });
    </script>

    <!-- Script pour les témoignages -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('testimonials-container');
        const template = document.getElementById('testimonial-template');
        const addButton = document.getElementById('addTestimonial');
        let testimonialCount = {{ isset($config['temoignages']) ? count($config['temoignages']) : 0 }};

        // Fonction pour vérifier s'il s'agit du dernier élément
        function isLastElement(element, selector) {
            return document.querySelectorAll(selector).length <= 1;
        }

        // Fonction pour ajouter un nouveau témoignage
        function addTestimonial(nom = '', statut = '', texte = '') {
            const clone = template.content.cloneNode(true);
            
            // Mettre à jour les noms des champs avec l'index actuel
            clone.querySelectorAll('[name*="INDEX"]').forEach(input => {
                input.name = input.name.replace('INDEX', testimonialCount);
            });

            // Ajouter le gestionnaire de suppression
            const deleteButton = clone.querySelector('.delete-testimonial');
            deleteButton.addEventListener('click', function() {
                const testimonialItem = this.closest('.testimonial-item');
                
                // Empêcher la suppression si c'est le dernier témoignage
                if (isLastElement(testimonialItem, '.testimonial-item')) {
                    alert('Vous devez conserver au moins un témoignage.');
                    return;
                }

                testimonialItem.style.opacity = '0';
                testimonialItem.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    testimonialItem.remove();
                }, 300);
            });

            // Ajouter une animation d'entrée
            const testimonialDiv = clone.querySelector('.testimonial-item');
            testimonialDiv.style.opacity = '0';
            testimonialDiv.style.transform = 'translateY(20px)';
            
            // Pré-remplir les champs si des valeurs sont fournies
            if (nom || statut || texte) {
                clone.querySelector('[name$="[nom]"]').value = nom;
                clone.querySelector('[name$="[statut]"]').value = statut;
                clone.querySelector('[name$="[texte]"]').value = texte;
            }

            container.appendChild(clone);

            // Déclencher l'animation
            setTimeout(() => {
                testimonialDiv.style.transition = 'all 0.3s ease-out';
                testimonialDiv.style.opacity = '1';
                testimonialDiv.style.transform = 'translateY(0)';
            }, 10);

            testimonialCount++;
        }

        // Ajouter un gestionnaire d'événements pour le bouton d'ajout
        addButton.addEventListener('click', () => addTestimonial());

        // Ajouter les gestionnaires de suppression pour les témoignages existants
        document.querySelectorAll('.delete-testimonial').forEach(button => {
            button.addEventListener('click', function() {
                const testimonialItem = this.closest('.testimonial-item');
                
                // Empêcher la suppression si c'est le dernier témoignage
                if (isLastElement(testimonialItem, '.testimonial-item')) {
                    alert('Vous devez conserver au moins un témoignage.');
                    return;
                }

                testimonialItem.style.opacity = '0';
                testimonialItem.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    testimonialItem.remove();
                }, 300);
            });
        });
    });
    </script>

    <!-- Script pour fermer les alertes -->
    <script>
        // Gestionnaire pour fermer les alertes
        document.querySelectorAll('.close-alert').forEach(button => {
            button.addEventListener('click', () => {
                const alert = button.closest('.mb-6');
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        });

        // Faire disparaître automatiquement les alertes après 5 secondes
        document.querySelectorAll('.mb-6[class*="bg-"]').forEach(alert => {
            alert.style.transition = 'all 0.3s ease-out';
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    </script>

    <!-- Modal pour la sélection d'icône -->
    <div id="iconModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" style="z-index: 1000;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <!-- En-tête du modal -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Sélection d'une icône</h3>
                        <button type="button" class="close-modal text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Fermer</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenu du modal -->
                <div class="px-6 py-4">
                    <div class="space-y-6">
                        <!-- Instructions -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-info-circle text-blue-600 mr-2 text-lg"></i>
                                <h4 class="text-blue-800 font-medium">Comment faire ?</h4>
                            </div>
                            <ol class="list-decimal list-inside space-y-2 text-blue-700 ml-1">
                                <li>Sur Font Awesome, tapez un mot-clé en anglais</li>
                                <li>Cliquez sur l'icône qui vous plaît</li>
                                <li>Copiez le code qui ressemble à : <br>
                                    <code class="inline-block mt-1 bg-white px-3 py-1 rounded border border-blue-100 text-blue-600">&lt;i class="fa-solid fa-house"&gt;&lt;/i&gt;</code>
                                </li>
                            </ol>
                        </div>

                        <!-- Suggestions -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-lightbulb text-amber-500 mr-2 text-lg"></i>
                                <h4 class="text-gray-700 font-medium">Suggestions de recherche</h4>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-50 transition-colors duration-150">
                                    <i class="fas fa-search mr-2 text-blue-500"></i>house → maison
                                </span>
                                <span class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-50 transition-colors duration-150">
                                    <i class="fas fa-search mr-2 text-blue-500"></i>bed → lit
                                </span>
                                <span class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-50 transition-colors duration-150">
                                    <i class="fas fa-search mr-2 text-blue-500"></i>book → livre
                                </span>
                                <span class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-50 transition-colors duration-150">
                                    <i class="fas fa-search mr-2 text-blue-500"></i>users → groupe
                                </span>
                            </div>
                        </div>
                        <!-- Bouton Font Awesome -->
                        <div class="flex justify-center">
                            <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-lg font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-external-link-alt mr-3 text-blue-200"></i>
                                Choisir mon icône sur Font Awesome
                            </a>
                        </div>

                        <!-- Champ de saisie -->
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <label class="block text-base font-medium text-gray-800 mb-3">Collez ici le code de votre icône</label>
                            <div class="relative">
                                <input type="text" id="iconCode" 
                                    class="block w-full px-4 py-3 pr-10 rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring-blue-500 text-gray-700 placeholder-gray-400" 
                                    placeholder='Collez le code copié depuis Font Awesome'>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-paste text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Message d'erreur -->
                        <div id="iconError" class="hidden">
                            <div class="bg-red-50 text-red-700 p-4 rounded-lg flex items-start border border-red-100">
                                <i class="fas fa-exclamation-circle mt-0.5 mr-3 text-red-500"></i>
                                <p class="text-sm">Le code n'est pas au bon format. Assurez-vous d'avoir copié tout le code de l'icône depuis Font Awesome.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pied du modal -->
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-between border-t border-gray-200">
                    <button type="button" class="close-modal-btn inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-150">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </button>
                    <button type="button" class="validate-icon inline-flex items-center px-5 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-sm hover:shadow-md">
                        Valider
                        <i class="fas fa-check ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('iconModal');
        const closeModal = modal.querySelector('.close-modal');
        const closeModalBtn = modal.querySelector('.close-modal-btn');
        const validateButton = modal.querySelector('.validate-icon');
        const iconError = document.getElementById('iconError');
        let currentIconInput = null;

        // Fonction pour ouvrir le modal
        function openIconModal(iconInput) {
            currentIconInput = iconInput;
            modal.classList.remove('hidden');
            iconError.classList.add('hidden');
            document.getElementById('iconCode').value = '';
            document.getElementById('iconCode').focus();
        }

        // Fonction pour fermer le modal
        function closeIconModal() {
            modal.classList.add('hidden');
            currentIconInput = null;
        }

        // Fonction pour valider le format de l'icône
        function validateIconFormat(iconCode) {
            const validFormat = /^<i class="[^"]+">[^<]*<\/i>$/;
            return validFormat.test(iconCode);
        }

        // Fonction pour mettre à jour l'aperçu de l'icône
        function updateIconPreview(iconClass) {
            const iconInput = currentIconInput.querySelector('input');
            const iconPreviewContainer = currentIconInput.querySelector('.absolute.right-0');
            
            // Mettre à jour la valeur de l'input
            iconInput.value = iconClass;
            
            // Mettre à jour l'icône de prévisualisation
            if (iconPreviewContainer) {
                const iconElement = iconPreviewContainer.querySelector('i');
                if (iconElement) {
                    iconElement.className = `${iconClass} text-lg text-blue-500`;
                }
            }

            // Ajouter une animation de mise à jour avec effet bleu
            iconPreviewContainer.classList.add('bg-blue-100');
            iconPreviewContainer.classList.add('animate-pulse');
            setTimeout(() => {
                iconPreviewContainer.classList.remove('animate-pulse');
                iconPreviewContainer.classList.remove('bg-blue-100');
            }, 1000);
        }

        // Fonction pour attacher les gestionnaires d'événements aux boutons d'icônes
        function attachIconButtonHandlers(container) {
            container.querySelectorAll('.choose-icon-btn').forEach(button => {
                // Supprimer l'ancien gestionnaire s'il existe
                button.removeEventListener('click', handleIconButtonClick);
                // Ajouter le nouveau gestionnaire
                button.addEventListener('click', handleIconButtonClick);
            });
        }

        // Fonction de gestion du clic sur le bouton d'icône
        function handleIconButtonClick(event) {
            const iconContainer = event.target.closest('.flex').querySelector('.relative');
            openIconModal(iconContainer);
        }

        // Gestionnaire pour le bouton de validation
        validateButton.addEventListener('click', () => {
            const iconCode = document.getElementById('iconCode').value.trim();
            
            if (!validateIconFormat(iconCode)) {
                iconError.classList.remove('hidden');
                return;
            }

            const match = iconCode.match(/class="([^"]+)"/);
            if (match && match[1]) {
                const iconClass = match[1];
                updateIconPreview(iconClass);
                closeIconModal();
            }
        });

        // Gestionnaire pour fermer le modal
        [closeModal, closeModalBtn].forEach(button => {
            button.addEventListener('click', closeIconModal);
        });

        // Attacher les gestionnaires aux boutons existants
        attachIconButtonHandlers(document);

        // Observer les changements dans le conteneur des points forts
        const strengthsContainer = document.getElementById('strengths-container');
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length) {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === 1) { // 1 = ELEMENT_NODE
                            attachIconButtonHandlers(node);
                        }
                    });
                }
            });
        });

        // Configuration de l'observer
        observer.observe(strengthsContainer, {
            childList: true,
            subtree: true
        });

        // Fermer le modal en cliquant en dehors
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeIconModal();
            }
        });

        // Masquer le message d'erreur quand l'utilisateur commence à taper
        document.getElementById('iconCode').addEventListener('input', function() {
            iconError.classList.add('hidden');
        });

        // Permettre la validation avec la touche Entrée
        document.getElementById('iconCode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                validateButton.click();
            }
        });
    });
    </script>
@endsection
