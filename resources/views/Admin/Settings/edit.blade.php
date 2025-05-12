@extends('layouts.app')

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
        
            <form action="/admin/settings/{{ $setting->id }}" method="POST" class="space-y-4">
                @csrf                
                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Valeur</label>
                    <input type="text" 
                           name="value" 
                           id="value"
                           value="{{ $setting->value }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="flex justify-end gap-3">
                    <a href="/admin/settings" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection