@extends('layouts.admin')

@php
use App\Http\Controllers\Theme\ThemeController;
$tc = new ThemeController();
$themes = $tc->getAllThemes();
@endphp

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900">Modifier un paramètre</h1>
        <p class="text-gray-600">Modification du paramètre "{{ $setting->key }}"</p>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-md">
            <div class="flex items-start gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-md shadow-indigo-300/20">
                    <i class="fas fa-cog text-lg"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $setting->key }}</h3>
                    <p class="text-sm text-gray-600">{{ $setting->description }}</p>
                </div>
            </div>
        
            <form action="{{ route('personnels.settings.store', $setting->id) }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($themes as $theme)
                        <div class="relative">
                            <input type="radio" id="theme_{{ $theme }}" name="value" value="{{ $theme }}" 
                                {{ $setting->value == $theme ? 'checked' : '' }}
                                class="peer absolute opacity-0">
                            <label for="theme_{{ $theme }}" 
                                class="block p-4 bg-white border rounded-lg cursor-pointer
                                peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500
                                hover:border-gray-300 transition-all">
                                <div class="flex flex-col gap-2">
                                    <div class="w-full h-32 bg-gray-100 rounded-md overflow-hidden">
                                        @if(isset($theme->preview_image))
                                            <img src="{{ asset($theme->preview_image) }}" alt="{{ $theme }}" 
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h4 class="font-medium text-gray-900">{{ $theme }}</h4>
                                    <p class="text-sm text-gray-500">[Description de escription du thème{{ $theme }}]]</p>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('personnels.settings.index') }}" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection