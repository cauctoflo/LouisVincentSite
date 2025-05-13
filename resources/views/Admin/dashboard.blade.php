@extends('layouts.admin')

@section('content')
    <!-- Time period selector -->
    <div class="flex justify-end mb-6">
        <div class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-600 shadow-sm border border-gray-200">
            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
            Période : <span class="font-semibold ml-1">Cette semaine</span>
            <i class="fas fa-chevron-down ml-2 text-gray-400"></i>
        </div>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total customers -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Total étudiants</h3>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-900">1,789</p>
                        <p class="ml-2 text-sm font-medium text-blue-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            2.5%
                        </p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-user-graduate text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Taux de réussite</h3>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-900">97.5%</p>
                        <p class="ml-2 text-sm font-medium text-blue-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            0.5%
                        </p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total orders -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Total enseignants</h3>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-900">125</p>
                        <p class="ml-2 text-sm font-medium text-blue-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            3.2%
                        </p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total returns -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-500">Taux de mentions</h3>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-900">72%</p>
                        <p class="ml-2 text-sm font-medium text-blue-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            1.2%
                        </p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-medal text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main chart -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-medium text-gray-900">Statistiques d'admission</h3>
                
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-blue-500 mr-1.5"></div>
                        <span class="text-xs text-gray-600">Admissions</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-blue-200 mr-1.5"></div>
                        <span class="text-xs text-gray-600">Taux d'acceptation</span>
                    </div>
                </div>
            </div>
            
            <div class="h-64 relative">
                <!-- Chart would be rendered here with JS -->
                <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                    <span class="text-sm">Graphique sera chargé ici</span>
                </div>
            </div>
            
            <div class="mt-4 px-6">
                <div class="flex items-center justify-between bg-blue-50 rounded-lg p-4">
                    <div>
                        <div class="text-sm font-medium text-gray-500">Taux moyen</div>
                        <div class="text-xl font-semibold text-blue-600">92.5%</div>
                    </div>
                    <div class="text-blue-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        2.5%
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie chart -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-base font-medium text-gray-900 mb-6">Répartition par filière</h3>
            
            <div class="h-64 relative">
                <!-- Pie chart placeholder -->
                <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                    <span class="text-sm">Graphique circulaire</span>
                </div>
            </div>
            
            <div class="mt-6 space-y-3">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-600 mr-2"></div>
                    <span class="text-sm text-gray-600 flex-1">Générale</span>
                    <span class="text-sm font-medium text-gray-900">48%</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-400 mr-2"></div>
                    <span class="text-sm text-gray-600 flex-1">STI2D</span>
                    <span class="text-sm font-medium text-gray-900">25%</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-300 mr-2"></div>
                    <span class="text-sm text-gray-600 flex-1">STL</span>
                    <span class="text-sm font-medium text-gray-900">18%</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-gray-300 mr-2"></div>
                    <span class="text-sm text-gray-600 flex-1">Autres</span>
                    <span class="text-sm font-medium text-gray-900">9%</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent activity section -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-medium text-gray-900">Activités récentes</h3>
                <button class="text-sm text-blue-600 hover:text-blue-800">Voir tout</button>
            </div>
            
            <div class="space-y-5">
                <!-- Activity item -->
                <div class="flex">
                    <div class="mr-4 flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-calendar-plus text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Calendrier scolaire mis à jour</p>
                        <p class="text-sm text-gray-500">Le calendrier des examens du troisième trimestre a été publié</p>
                        <p class="text-xs text-gray-400 mt-1">Il y a 2 heures</p>
                    </div>
                </div>
                
                <!-- Activity item -->
                <div class="flex">
                    <div class="mr-4 flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Nouvel enseignant ajouté</p>
                        <p class="text-sm text-gray-500">M. Dupont a rejoint l'équipe pédagogique en mathématiques</p>
                        <p class="text-xs text-gray-400 mt-1">Hier</p>
                    </div>
                </div>
                
                <!-- Activity item -->
                <div class="flex">
                    <div class="mr-4 flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-file-upload text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Nouveau document partagé</p>
                        <p class="text-sm text-gray-500">La plaquette de présentation du lycée a été mise à jour</p>
                        <p class="text-xs text-gray-400 mt-1">Il y a 2 jours</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 