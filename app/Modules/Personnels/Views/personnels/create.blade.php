@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-display font-bold text-gray-900">Créer un nouveau personnel</h1>
        <a href="{{ route('personnels.personnels.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('personnels.personnels.store') }}" method="POST">
                @csrf
                
                <!-- Informations de base -->
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informations de base</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('name') border-red-300 @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('email') border-red-300 @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('password') border-red-300 @enderror" required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                    </div>
                    
                    <div class="space-y-2 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('description') border-red-300 @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Accès et permissions -->
                <h2 class="text-lg font-bold text-gray-900 mb-4">Accès et permissions</h2>
                
                <div class="border rounded-lg p-4 bg-gray-50 mb-8">
                    <div class="mb-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="is_admin" id="is_admin" class="rounded border-gray-300 text-primary focus:ring-primary" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_admin" class="font-medium text-gray-700">Est administrateur</label>
                                <p class="text-gray-500">Les administrateurs ont accès à toutes les fonctionnalités sans restrictions.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Attribution de rôles -->
                    <div class="mb-6">
                        <h3 class="text-md font-semibold text-gray-800 mb-3">Rôles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach(\App\Modules\Personnels\Models\Role::all() as $role)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}" class="rounded border-gray-300 text-primary focus:ring-primary" {{ (old('roles') && in_array($role->id, old('roles'))) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="role_{{ $role->id }}" class="font-medium text-gray-700">{{ $role->name }}</label>
                                        @if($role->description)
                                            <p class="text-gray-500">{{ $role->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Attribution de permissions spécifiques -->
                    <div>
                        <h3 class="text-md font-semibold text-gray-800 mb-3">Permissions spécifiques</h3>
                        <p class="text-sm text-gray-500 mb-3">Les permissions ci-dessous seront attribuées en plus de celles des rôles.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach(app(\App\Modules\Personnels\Controllers\PermissionController::class)->getAvailablePermissions() as $key => $label)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="permissions[]" id="perm_{{ $key }}" value="{{ $key }}" class="rounded border-gray-300 text-primary focus:ring-primary" {{ (old('permissions') && in_array($key, old('permissions'))) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="perm_{{ $key }}" class="font-medium text-gray-700">{{ $label }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-lg transition-all">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 