@extends('layouts.admin')

@php
    $settings = App\Models\Setting::all();
@endphp

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900">Paramètres du site</h1>
        <p class="text-gray-600">Gérez les paramètres globaux de votre site</p>
    </div>
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <p>Le paramètre a bien été modifié</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($settings as $setting)
            <a href="{{ route('personnels.settings.edit', ['setting' => $setting]) }}" class="bg-white rounded-xl p-6 border border-slate-100 shadow-md hover:shadow-xl transition-all block">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-md shadow-indigo-300/20">
                        <i class="fas fa-cog text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $setting->key }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $setting->description }}</p>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium">
                                {{ $setting->value }}
                            </span>
                            <button 
                                class="text-gray-400 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>

@endsection