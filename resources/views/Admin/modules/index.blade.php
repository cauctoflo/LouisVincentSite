@extends('layouts.admin')

@section('title', 'Gestion des Modules')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Gestion des Modules</h1>
            
            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($modules as $module)
                        <li>
                            <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-50 rounded-md p-3">
                                        <i class="fas fa-puzzle-piece text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h2 class="text-lg font-medium text-gray-900">{{ $module['name'] }}</h2>
                                        <p class="text-sm text-gray-500">
                                            Statut: 
                                            @if($module['status'] === 'active')
                                                <span class="text-green-600 font-medium">Actif</span>
                                            @else
                                                <span class="text-red-600 font-medium">Inactif</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">


                                    @if (Route::has('personnels.' . $module['name'] . '.settings'))
                                        <a href="{{ route('personnels.' . $module['name'] . '.settings') }}" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-cog mr-2 text-gray-500"></i>
                                            Paramètres
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-5 sm:px-6 text-center text-gray-500">
                            Aucun module trouvé.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection 