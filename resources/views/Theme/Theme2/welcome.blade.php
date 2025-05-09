@extends('layouts.app')

@section('content')
{{dd("Theme 2")}}
<div class="relative min-h-screen overflow-hidden bg-white">
    <!-- Hero Section -->
    <div class="relative overflow-hidden py-6">
        <!-- Top Bar -->
        <div class="bg-blue-700 text-white py-1.5">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 flex justify-between items-center text-sm">
                <div class="flex space-x-4">
                    <a href="tel:0387664822" class="hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        03 87 66 48 22
                    </a>
                    <a href="mailto:contact@louis-vincent.fr" class="hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        contact@louis-vincent.fr
                    </a>
                </div>
                <div class="flex space-x-3">
                    <a href="#" class="hover:text-blue-200">ENT</a>
                    <a href="#" class="hover:text-blue-200">Pronote</a>
                </div>
            </div>
        </div>
        
        <!-- Navigation Bar -->
        <div class="relative z-10 mx-auto flex max-w-7xl items-center justify-between px-6 pt-6 pb-4 lg:px-8 border-b border-gray-200">
            <div class="flex items-center">
                <div class="mr-4 h-16 w-16 overflow-hidden rounded-xl bg-blue-700 shadow-md">
                    <div class="flex h-full w-full items-center justify-center text-xl font-bold text-white">
                        LV
                    </div>
                </div>
                <div>
                    <div class="font-title text-2xl font-bold text-blue-800">LYCÉE</div>
                    <div class="font-title text-3xl font-bold text-blue-700">LOUIS VINCENT</div>
                    <div class="text-sm text-gray-600 mt-1">Un lycée d'excellence à Metz</div>
                </div>
            </div>
            
            <div class="hidden space-x-1 md:flex">
                <a href="#" class="px-3 py-2 text-gray-700 hover:text-blue-700 font-medium">Accueil</a>
                <a href="#" class="px-3 py-2 text-gray-700 hover:text-blue-700 font-medium">Le lycée</a>
                <a href="#" class="px-3 py-2 text-gray-700 hover:text-blue-700 font-medium">Formation</a>
                <a href="#" class="px-3 py-2 text-gray-700 hover:text-blue-700 font-medium">Vie lycéenne</a>
                <a href="#" class="px-3 py-2 text-gray-700 hover:text-blue-700 font-medium">International</a>
                <a href="#" class="px-3 py-2 text-gray-700 hover:text-blue-700 font-medium">Actualités</a>
            </div>
            
            <div class="hidden md:block">
                <a href="#contact" class="rounded-md bg-blue-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 transition-colors">Nous contacter</a>
            </div>
            
            <div class="md:hidden">
                <button class="text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Hero Content -->
        <div class="relative z-10 mx-auto mt-8 max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                <div class="text-center lg:text-left">                    
                    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                        L'avenir se construit <br/>
                        <span class="text-blue-700">au lycée Louis Vincent</span>
                    </h1>
                    
                    <p class="mt-6 max-w-xl text-lg text-gray-600">
                        Un établissement d'excellence qui allie tradition et innovation pour former les talents de demain dans un environnement stimulant.
                    </p>
                    
                    <div class="mt-10 flex flex-col items-center justify-center space-y-4 sm:flex-row sm:space-x-4 sm:space-y-0 lg:justify-start">
                        <a href="#presentation" class="group flex w-full items-center justify-center rounded-md bg-blue-700 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 transition-colors sm:w-auto">
                            Découvrir le lycée
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        
                        <a href="#" class="group flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-8 py-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition-colors sm:w-auto">
                            Visite virtuelle
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Hero Image Section -->
                <div class="relative mx-auto h-[500px] w-full max-w-lg">
                    <!-- Main Image -->
                    <div class="absolute left-1/2 top-1/2 h-[70%] w-[70%] -translate-x-1/2 -translate-y-1/2 transform overflow-hidden rounded-lg border border-gray-200 shadow-xl">
                        <img src="{{ asset('images/lycee-facade.jpg') }}" alt="Lycée Louis Vincent" class="h-full w-full object-cover" />
                    </div>
                    
                    <!-- Decorative Images -->
                    <div class="absolute right-0 top-10 h-40 w-40 overflow-hidden rounded-lg border border-gray-200 shadow-lg">
                        <img src="{{ asset('images/students-working.jpg') }}" alt="Élèves en cours" class="h-full w-full object-cover" />
                    </div>
                    
                    <div class="absolute bottom-10 left-0 h-40 w-40 overflow-hidden rounded-lg border border-gray-200 shadow-lg">
                        <img src="{{ asset('images/science-lab.jpg') }}" alt="Laboratoire de sciences" class="h-full w-full object-cover" />
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute left-[15%] top-[20%] flex h-16 w-16 items-center justify-center rounded-lg bg-green-600 text-2xl font-bold text-white shadow-lg">
                        <span>A+</span>
                    </div>
                    
                    <div class="absolute bottom-[25%] right-[15%] flex h-20 w-20 items-center justify-center rounded-full bg-blue-700 text-white shadow-lg">
                        <div class="text-center">
                            <div class="text-2xl font-bold">98%</div>
                            <div class="text-xs">de réussite</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="relative z-10 w-full bg-gray-100 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                <div class="flex flex-col items-center rounded-lg bg-white px-4 py-8 shadow-sm">
                    <div class="text-3xl font-bold text-blue-700">97.9<span class="text-blue-600">%</span></div>
                    <div class="mt-2 text-center text-sm text-gray-600">Taux de réussite au baccalauréat</div>
                </div>
                
                <div class="flex flex-col items-center rounded-lg bg-white px-4 py-8 shadow-sm">
                    <div class="text-3xl font-bold text-blue-700">72<span class="text-blue-600">%</span></div>
                    <div class="mt-2 text-center text-sm text-gray-600">Taux de mention au baccalauréat</div>
                </div>
                
                <div class="flex flex-col items-center rounded-lg bg-white px-4 py-8 shadow-sm">
                    <div class="text-3xl font-bold text-blue-700">430</div>
                    <div class="mt-2 text-center text-sm text-gray-600">Élèves en terminale</div>
                </div>
                
                <div class="flex flex-col items-center rounded-lg bg-white px-4 py-8 shadow-sm">
                    <div class="text-3xl font-bold text-blue-700">1892</div>
                    <div class="mt-2 text-center text-sm text-gray-600">Année de fondation</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="relative z-10 py-16 bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold text-gray-900">Un lycée aux multiples facettes</h2>
                <div class="mt-2 h-1 w-20 bg-blue-700 mx-auto"></div>
                <p class="mx-auto mt-4 max-w-2xl text-gray-600">Ce qui nous distingue et fait notre réputation depuis plus d'un siècle</p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Feature Card 1 -->
                <div class="group relative overflow-hidden rounded-lg border border-gray-200 bg-white p-8 transition-all duration-300 hover:shadow-lg">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    
                    <h3 class="mb-3 text-xl font-bold text-gray-900">Excellence académique</h3>
                    <p class="text-gray-600">Des enseignements d'excellence et des résultats exceptionnels au baccalauréat, année après année.</p>
                </div>
                
                <!-- Feature Card 2 -->
                <div class="group relative overflow-hidden rounded-lg border border-gray-200 bg-white p-8 transition-all duration-300 hover:shadow-lg">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    
                    <h3 class="mb-3 text-xl font-bold text-gray-900">Cadre historique exceptionnel</h3>
                    <p class="text-gray-600">Au cœur de la cité Impériale, notre établissement allie histoire et modernité dans un cadre inspirant.</p>
                </div>
                
                <!-- Feature Card 3 -->
                <div class="group relative overflow-hidden rounded-lg border border-gray-200 bg-white p-8 transition-all duration-300 hover:shadow-lg">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    
                    <h3 class="mb-3 text-xl font-bold text-gray-900">Innovation pédagogique</h3>
                    <p class="text-gray-600">Des projets innovants et des méthodes d'enseignement modernes pour préparer les élèves au monde de demain.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Word from Principal Section -->
    <div class="relative z-10 py-16 bg-gray-100">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-xl bg-white shadow-md">
                <div class="grid md:grid-cols-2">
                    <!-- Image Column -->
                    <div class="relative h-64 md:h-auto">
                        <img src="{{ asset('images/proviseur.jpg') }}" alt="M. PALLEZ" class="h-full w-full object-cover" />
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4 md:hidden">
                            <h3 class="text-xl font-bold text-white">M. PALLEZ</h3>
                            <div class="text-sm font-medium text-blue-200">Proviseur</div>
                        </div>
                    </div>
                    
                    <!-- Text Column -->
                    <div class="p-8">
                        <div class="mb-8 hidden md:block">
                            <h3 class="text-2xl font-bold text-gray-900">M. PALLEZ</h3>
                            <div class="text-lg font-medium text-blue-700">Proviseur</div>
                        </div>
                        
                        <h2 class="mb-6 text-2xl font-bold text-gray-900">Le mot du Proviseur</h2>
                        
                        <div class="prose text-gray-600">
                            <p class="mb-4">
                                <span class="text-2xl font-serif text-blue-700">"</span>
                                Je vous souhaite la bienvenue sur le site du lycée Louis Vincent.
                            </p>
                            
                            <p class="mb-4">
                                Que votre visite soit amicale, informative ou furtive, elle vous fera découvrir les multiples aspects du lycée. Et à n'en pas douter, elle vous emmènera à la rencontre de « l'esprit Louis Vincent » : c'est-à-dire l'équilibre entre l'environnement intellectuel de grande qualité, la convivialité, le foisonnement d'initiatives et de projets, et l'intensité d'échanges dans les domaines culturels, artistiques, humanitaires ou sportifs.
                            </p>
                            
                            <p>
                                Fortement ancré dans l'histoire de sa ville et de sa région, le lycée Louis Vincent continue toujours de développer les ambitions, les talents, les compétences et les potentiels de tous ses élèves. Et l'ensemble de la communauté éducative travaille à former des citoyens cultivés, responsables, autonomes et ouverts sur tous les mondes : ce qu'est et ce que sera encore l'excellence.
                                <span class="text-2xl font-serif text-blue-700">"</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Admin Team Section -->
    <div id="presentation" class="relative z-10 py-16 bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold text-gray-900">Direction & Administration</h2>
                <div class="mt-2 h-1 w-20 bg-blue-700 mx-auto"></div>
                <p class="mx-auto mt-4 max-w-2xl text-gray-600">Une équipe expérimentée et passionnée, dédiée à l'épanouissement et à la réussite des élèves</p>
            </div>
            
            <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-3">
                <!-- Team Member 1 -->
                <div class="group relative">
                    <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg shadow-md">
                        <img src="{{ asset('images/proviseur.jpg') }}" alt="M. PALLEZ" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    </div>
                    
                    <div class="relative -mt-16 mx-4 rounded-lg bg-white p-6 shadow-md">
                        <div class="absolute -right-3 -top-3 flex h-10 w-10 items-center justify-center rounded-full bg-blue-700 text-white shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900">M. PALLEZ</h3>
                        <div class="mb-3 mt-1 text-sm font-medium text-blue-700">Proviseur</div>
                        
                        <p class="text-sm text-gray-600">
                            Ancien enseignant passionné, il met son expérience au service de l'établissement et de la réussite des élèves.
                        </p>
                        
                        <div class="mt-4 flex space-x-3">
                            <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-blue-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-blue-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Team Member 2 -->
                <div class="group relative">
                    <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg shadow-md">
                        <img src="{{ asset('images/proviseur-adjoint.jpg') }}" alt="Proviseur adjoint" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    </div>
                    
                    <div class="relative -mt-16 mx-4 rounded-lg bg-white p-6 shadow-md">
                        <div class="absolute -right-3 -top-3 flex h-10 w-10 items-center justify-center rounded-full bg-blue-700 text-white shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900">Proviseur Adjoint</h3>
                        <div class="mb-3 mt-1 text-sm font-medium text-blue-700">Administration</div>
                        
                        <p class="text-sm text-gray-600">
                            Coordination des équipes pédagogiques et suivi personnalisé des élèves pour créer les conditions optimales d'apprentissage.
                        </p>
                        
                        <div class="mt-4 flex space-x-3">
                            <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-blue-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-blue-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Team Member 3 -->
                <div class="group relative">
                    <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg shadow-md">
                        <img src="{{ asset('images/secretariat.jpg') }}" alt="Secrétariat" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    </div>
                    
                    <div class="relative -mt-16 mx-4 rounded-lg bg-white p-6 shadow-md">
                        <div class="absolute -right-3 -top-3 flex h-10 w-10 items-center justify-center rounded-full bg-blue-700 text-white shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900">Secrétariat</h3>
                        <div class="mb-3 mt-1 text-sm font-medium text-blue-700">Administration</div>
                        
                        <p class="text-sm text-gray-600">
                            À votre écoute pour toutes les démarches administratives, inscriptions et demandes d'informations concernant la scolarité.
                        </p>
                        
                        <div class="mt-4 flex space-x-3">
                            <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-blue-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-colors hover:bg-blue-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- News Section -->
    <div class="relative z-10 bg-gray-100 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold text-gray-900">Actualités du lycée</h2>
                <div class="mt-2 h-1 w-20 bg-blue-700 mx-auto"></div>
                <p class="mx-auto mt-4 max-w-2xl text-gray-600">Restez informés des derniers événements et des moments forts de la vie lycéenne</p>
            </div>
            
            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
                <!-- News Card 1 -->
                <div class="group overflow-hidden rounded-lg bg-white shadow-md transition-all duration-300 hover:shadow-lg">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset('images/actu1.jpg') }}" alt="Actualité" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <div class="mb-1 inline-block rounded-md bg-blue-700 px-2 py-1 text-xs font-medium text-white">20 Juin 2024</div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="mb-2 text-xl font-bold text-gray-900 group-hover:text-blue-700 transition-colors">Bienvenue au lycée Louis Vincent</h3>
                        <p class="mb-4 text-gray-600">Portes ouvertes de rentrée sur préalable aux nouveaux inscrits</p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="h-8 w-8 overflow-hidden rounded-full">
                                    <img src="{{ asset('images/avatar1.jpg') }}" alt="Avatar" class="h-full w-full object-cover" />
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-900">Administration</div>
                                    <div class="text-xs text-gray-500">Service vie scolaire</div>
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-sm font-medium text-blue-700 hover:text-blue-800 transition-colors">
                                Lire la suite
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- News Card 2 -->
                <div class="group overflow-hidden rounded-lg bg-white shadow-md transition-all duration-300 hover:shadow-lg">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset('images/actu2.jpg') }}" alt="Actualité" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <div class="mb-1 inline-block rounded-md bg-blue-700 px-2 py-1 text-xs font-medium text-white">15 Juin 2024</div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="mb-2 text-xl font-bold text-gray-900 group-hover:text-blue-700 transition-colors">Résultats du Baccalauréat 2024</h3>
                        <p class="mb-4 text-gray-600">Félicitations à tous nos bacheliers pour leur excellents résultats!</p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="h-8 w-8 overflow-hidden rounded-full">
                                    <img src="{{ asset('images/avatar2.jpg') }}" alt="Avatar" class="h-full w-full object-cover" />
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-900">M. PALLEZ</div>
                                    <div class="text-xs text-gray-500">Proviseur</div>
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-sm font-medium text-blue-700 hover:text-blue-800 transition-colors">
                                Lire la suite
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- News Card 3 -->
                <div class="group overflow-hidden rounded-lg bg-white shadow-md transition-all duration-300 hover:shadow-lg">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset('images/actu3.jpg') }}" alt="Actualité" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <div class="mb-1 inline-block rounded-md bg-blue-700 px-2 py-1 text-xs font-medium text-white">10 Juin 2024</div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="mb-2 text-xl font-bold text-gray-900 group-hover:text-blue-700 transition-colors">Concours d'éloquence</h3>
                        <p class="mb-4 text-gray-600">Les élèves de terminale brillent lors du concours régional d'éloquence</p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="h-8 w-8 overflow-hidden rounded-full">
                                    <img src="{{ asset('images/avatar3.jpg') }}" alt="Avatar" class="h-full w-full object-cover" />
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-900">Mme Laurent</div>
                                    <div class="text-xs text-gray-500">Enseignante de Français</div>
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-sm font-medium text-blue-700 hover:text-blue-800 transition-colors">
                                Lire la suite
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 flex justify-center">
                <a href="#" class="rounded-md border border-blue-700 bg-white px-8 py-3 text-sm font-semibold text-blue-700 shadow-sm hover:bg-blue-700 hover:text-white transition-colors">
                    Voir toutes les actualités
                </a>
            </div>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="relative z-10 py-16 bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold text-gray-900">La vie à Louis Vincent</h2>
                <div class="mt-2 h-1 w-20 bg-blue-700 mx-auto"></div>
                <p class="mx-auto mt-4 max-w-2xl text-gray-600">Explorez nos projets et découvrez la vie lycéenne à travers nos associations et initiatives</p>
            </div>
            
            <div class="grid gap-10 md:grid-cols-2">
                <!-- Project Card 1 -->
                <div class="group overflow-hidden rounded-lg shadow-md transition-all duration-300 hover:shadow-lg">
                    <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
                        <img src="{{ asset('images/theatre.jpg') }}" alt="Théâtre" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 flex items-end">
                            <div>
                                <div class="mb-2 inline-block rounded-md bg-blue-700 px-2 py-1 text-xs font-medium text-white">30 Janvier 2024</div>
                                <h3 class="text-2xl font-bold text-white">Pièces de théâtre</h3>
                                <p class="text-white/80">
                                    Les élèves de l'option théâtre présentent leurs créations
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-white">
                        <p class="mb-6 text-gray-600">
                            Les élèves de l'option théâtre présentent leurs créations avec passion et talent devant un public conquis lors de la représentation annuelle.
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <img src="{{ asset('images/student1.jpg') }}" alt="Étudiant" class="h-8 w-8 rounded-full border-2 border-white object-cover" />
                                <img src="{{ asset('images/student2.jpg') }}" alt="Étudiant" class="h-8 w-8 rounded-full border-2 border-white object-cover" />
                                <img src="{{ asset('images/student3.jpg') }}" alt="Étudiant" class="h-8 w-8 rounded-full border-2 border-white object-cover" />
                                <div class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-blue-700 text-xs font-medium text-white">+12</div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-sm font-medium text-blue-700 hover:text-blue-800 transition-colors">
                                Voir le projet
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Project Card 2 -->
                <div class="group overflow-hidden rounded-lg shadow-md transition-all duration-300 hover:shadow-lg">
                    <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
                        <img src="{{ asset('images/code.jpg') }}" alt="Projet web" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 flex items-end">
                            <div>
                                <div class="mb-2 inline-block rounded-md bg-blue-700 px-2 py-1 text-xs font-medium text-white">2024-2025</div>
                                <h3 class="text-2xl font-bold text-white">Projet web des NSI</h3>
                                <p class="text-white/80">
                                    Développement de projets web innovants
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-white">
                        <p class="mb-6 text-gray-600">
                            Les élèves de NSI développent des projets innovants pour la refonte du site web du lycée avec des technologies modernes et adaptées.
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <img src="{{ asset('images/student4.jpg') }}" alt="Étudiant" class="h-8 w-8 rounded-full border-2 border-white object-cover" />
                                <img src="{{ asset('images/student5.jpg') }}" alt="Étudiant" class="h-8 w-8 rounded-full border-2 border-white object-cover" />
                                <img src="{{ asset('images/student6.jpg') }}" alt="Étudiant" class="h-8 w-8 rounded-full border-2 border-white object-cover" />
                                <div class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-blue-700 text-xs font-medium text-white">+8</div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-sm font-medium text-blue-700 hover:text-blue-800 transition-colors">
                                Voir le projet
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-16 text-center">
                <a href="#" class="rounded-md border border-blue-700 bg-white px-8 py-3 text-sm font-semibold text-blue-700 shadow-sm hover:bg-blue-700 hover:text-white transition-colors">
                    Tous nos projets
                </a>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="relative z-10 bg-gray-100 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="text-3xl font-bold text-gray-900">Contactez-nous</h2>
                <div class="mt-2 h-1 w-20 bg-blue-700 mx-auto"></div>
                <p class="mx-auto mt-4 max-w-2xl text-gray-600">Besoin d'information ? Notre équipe est disponible pour vous accompagner dans vos démarches</p>
            </div>
            
            <div class="rounded-lg bg-white shadow-md overflow-hidden">
                <div class="grid md:grid-cols-3">
                    <!-- Contact Info -->
                    <div class="bg-blue-700 p-8 text-white">
                        <h3 class="text-xl font-bold mb-6">Informations</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <p class="font-semibold mb-1">Adresse</p>
                                    <p>2 Rue de Verdun, 57000 Metz</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <p class="font-semibold mb-1">Téléphone</p>
                                    <p>03 87 66 48 22</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="font-semibold mb-1">Email</p>
                                    <p>contact@louis-vincent.fr</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="font-semibold mb-1">Horaires</p>
                                    <p>Lundi - Vendredi: 7h30 - 18h30</p>
                                    <p>Samedi: 8h00 - 12h00</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex space-x-4">
                            <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white transition-colors hover:bg-white hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </a>
                            <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white transition-colors hover:bg-white hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white transition-colors hover:bg-white hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
    <div id="contact" class="relative z-10 overflow-hidden bg-[#080F34] py-24">
        <div class="absolute inset-0 z-0">
            <div class="absolute -left-20 -top-20 h-96 w-96 rounded-full bg-blue-600/10 blur-3xl"></div>
            <div class="absolute right-0 top-20 h-72 w-72 rounded-full bg-purple-600/10 blur-3xl"></div>
            <div class="absolute bottom-20 left-1/3 h-80 w-80 rounded-full bg-cyan-600/10 blur-3xl"></div>
        </div>
        
        <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mb-16 text-center">
                <div class="mb-3 inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-1 text-xs backdrop-blur-sm">
                    <span class="text-white/80">Nous sommes à l'écoute</span>
                </div>
                <h2 class="text-3xl font-bold text-white">Contactez-nous</h2>
                <p class="mx-auto mt-4 max-w-2xl text-white/70">Besoin d'information ? Notre équipe est disponible pour vous accompagner dans vos démarches</p>
            </div>
            
            <div class="grid gap-10 md:grid-cols-3">
                <!-- Contact Info -->
                <div class="space-y-10 md:col-span-1">
                    <!-- Info Card 1 -->
                    <div class="group overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-6 backdrop-blur-sm transition-all duration-300 hover:border-blue-500/30 hover:bg-white/10">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 shadow-lg shadow-blue-600/20">
                            <div class="text-3xl font-bold text-white">LV</div>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-6">Informations</h3>
                        
                        <div class="flex items-start mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold mb-1">Adresse</p>
                                <p>2 Rue de Verdun, 57000 Metz</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <p class="font-semibold mb-1">Téléphone</p>
                                <p>03 87 66 48 22</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="font-semibold mb-1">Email</p>
                                <p>contact@louis-vincent.fr</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-span-2 bg-gray-50 rounded-xl p-8">
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                            <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        
                        <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold transition">Envoyer le message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-white/10 bg-[#070B2A] py-12">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-5">
                <!-- Column 1 - Logo & Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center">
                        <div class="mr-4 h-12 w-12 overflow-hidden rounded-xl bg-gradient-to-tr from-blue-600 to-purple-600 p-0.5">
                            <div class="flex h-full w-full items-center justify-center rounded-xl bg-[#070B2A] text-lg font-bold text-white">
                                LV
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-white">Louis Vincent</div>
                    </div>
                    
                    <p class="mt-4 max-w-md text-white/70">
                        Un établissement d'excellence au cœur de Metz qui forme depuis des générations les talents de demain dans un cadre historique exceptionnel.
                    </p>
                    
                    <div class="mt-6 flex space-x-4">
                        <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/70 transition-colors hover:bg-blue-600 hover:border-blue-600 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                            </svg>
                        </a>
                        <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/70 transition-colors hover:bg-blue-600 hover:border-blue-600 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/70 transition-colors hover:bg-blue-600 hover:border-blue-600 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white/70 transition-colors hover:bg-blue-600 hover:border-blue-600 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Column 2 - Liens rapides -->
                <div>
                    <h3 class="mb-6 text-lg font-bold text-white">Liens rapides</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Accueil</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Établissement</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Formations</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Actualités</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Contact</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Column 3 - Infos pratiques -->
                <div>
                    <h3 class="mb-6 text-lg font-bold text-white">Infos pratiques</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Calendrier scolaire</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Restauration</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Internat</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Orientation</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block text-white/70 transition-colors hover:text-blue-400">Accessibilité</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Column 4 - Horaires -->
                <div>
                    <h3 class="mb-6 text-lg font-bold text-white">Horaires d'ouverture</h3>
                    <ul class="space-y-3 text-white/70">
                        <li class="flex items-center">
                            <div class="mr-2 h-2 w-2 rounded-full bg-green-500"></div>
                            <span>Lundi - Vendredi: 7h30 - 18h30</span>
                        </li>
                        <li class="flex items-center">
                            <div class="mr-2 h-2 w-2 rounded-full bg-blue-500"></div>
                            <span>Samedi: 8h00 - 12h00</span>
                        </li>
                        <li class="flex items-center">
                            <div class="mr-2 h-2 w-2 rounded-full bg-red-500"></div>
                            <span>Dimanche: Fermé</span>
                        </li>
                    </ul>
                    
                    <div class="mt-6">
                        <a href="#" class="inline-flex items-center rounded-full bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-1 text-sm text-white shadow-lg shadow-blue-600/20 transition-all duration-300 hover:shadow-xl hover:shadow-blue-600/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Calendrier des événements
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 border-t border-white/10 pt-8">
                <div class="flex flex-col items-center justify-between space-y-4 md:flex-row md:space-y-0">
                    <div class="text-sm text-white/50">
                        © 2024 Lycée Louis Vincent | Tous droits réservés
                    </div>
                    
                    <div class="flex space-x-6 text-sm text-white/50">
                        <a href="#" class="transition-colors hover:text-white">Mentions légales</a>
                        <a href="#" class="transition-colors hover:text-white">Politique de confidentialité</a>
                        <a href="#" class="transition-colors hover:text-white">Plan du site</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to top button -->
    <div class="fixed bottom-8 right-8 z-50">
        <a href="#" class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg shadow-blue-600/20 transition-all duration-300 hover:shadow-xl hover:shadow-blue-600/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </a>
    </div>
</div>
@endsection
