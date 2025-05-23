@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Attribution des rôles</h1>
            <p class="text-gray-500 mt-1">Gérez les rôles assignés aux utilisateurs du système</p>
        </div>
        <a href="{{ route('personnels.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux rôles
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md animate__animated animate__fadeIn">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                <div class="ml-auto">
                    <button type="button" class="text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Attribuer un rôle -->
        @if(auth()->check() && auth()->user()->hasPermission('roles.assign'))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-indigo-900">
                    <i class="fas fa-user-tag mr-2 text-indigo-600"></i>
                    Attribuer un rôle à un utilisateur
                </h2>
            </div>
            <div class="p-6">
                <form action="{{ route('personnels.roles.assign') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Utilisateur <span class="text-red-500">*</span></label>
                            <select name="user_id" id="user_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('user_id') border-red-300 @enderror" required>
                                <option value="">Sélectionner un utilisateur</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} {{ $user->is_admin ? '(Admin)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Rôle <span class="text-red-500">*</span></label>
                            <select name="role_id" id="role_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('role_id') border-red-300 @enderror" required>
                                <option value="">Sélectionner un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                <i class="fas fa-user-tag mr-2"></i> Attribuer le rôle
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        
        <!-- Statistiques des rôles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-blue-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-medium text-blue-900">
                    <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                    Statistiques des rôles
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-indigo-600 mb-1">{{ $users->count() }}</div>
                        <div class="text-sm text-gray-500">Utilisateurs</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-1">{{ $roles->count() }}</div>
                        <div class="text-sm text-gray-500">Rôles</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-green-600 mb-1">{{ $users->filter(function($user) { return $user->roles->count() > 0; })->count() }}</div>
                        <div class="text-sm text-gray-500">Utilisateurs avec rôle</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-yellow-600 mb-1">{{ $users->filter(function($user) { return $user->roles->count() == 0; })->count() }}</div>
                        <div class="text-sm text-gray-500">Utilisateurs sans rôle</div>
                    </div>
                </div>
                @if(auth()->check() && auth()->user()->hasPermission('roles.create'))
                <div class="mt-5 flex justify-center">
                    <a href="{{ route('personnels.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                        <i class="fas fa-plus-circle mr-2"></i> Créer un nouveau rôle
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-medium text-gray-900">Rôles attribués aux utilisateurs</h2>
        </div>
        
        <div class="p-6">
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchUser" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Rechercher un utilisateur...">
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rôles</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="usersTableBody">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email ?? 'Email non disponible' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->roles->count() > 0)
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($user->roles as $role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Aucun rôle assigné</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if ($user->roles->count() > 0 && auth()->check() && auth()->user()->hasPermission('roles.assign'))
                                        <button type="button" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md font-medium hover:bg-red-100 transition-all" 
                                                onclick="openRemoveRoleModal('{{ $user->id }}', '{{ $user->name }}')">
                                            <i class="fas fa-trash mr-1"></i> Retirer
                                        </button>
                                    @elseif($user->roles->count() > 0)
                                        <span class="text-gray-400 text-sm">Retrait non autorisé</span>
                                    @else
                                        <span class="px-3 py-1.5 inline-flex opacity-50">Aucune action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-16 w-16 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 mb-4">
                                            <i class="fas fa-users text-2xl"></i>
                                        </div>
                                        <span class="text-gray-500 font-medium">Aucun utilisateur trouvé</span>
                                        <p class="text-gray-400 mt-1 max-w-md">Ajoutez des utilisateurs au système pour leur attribuer des rôles</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @if(!auth()->check() || !auth()->user()->hasPermission('roles.assign'))
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Cette page est actuellement en mode lecture seule. Vous n'avez pas les permissions nécessaires pour attribuer ou retirer des rôles.
                </p>
            </div>
        </div>
    </div>
    @endif
</div>

@if(auth()->check() && auth()->user()->hasPermission('roles.assign'))
<!-- Modal pour retirer un rôle -->
<div id="removeRoleModal" class="fixed inset-0 z-50 hidden overflow-auto bg-gray-900 bg-opacity-50 flex justify-center items-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900" id="removeRoleModalTitle">Retirer un rôle</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeRemoveRoleModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('personnels.roles.remove') }}" method="POST" id="removeRoleForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="user_id" id="removeRoleUserId">
                
                <div class="mb-4">
                    <p class="text-gray-700 mb-3">Choisissez le rôle à retirer de l'utilisateur <span id="removeRoleUserName" class="font-medium"></span> :</p>
                    
                    <select name="role_id" id="removeRoleSelect" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <!-- Options will be populated dynamically by JavaScript -->
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="closeRemoveRoleModal()">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Retirer le rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@section('scripts')
<script>
    // User roles data for modal
    const userRoles = {
        @foreach($users as $user)
            '{{ $user->id }}': [
                @foreach($user->roles as $role)
                    { id: '{{ $role->id }}', name: '{{ $role->name }}' },
                @endforeach
            ],
        @endforeach
    };

    // Search functionality
    document.getElementById('searchUser').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#usersTableBody tr');
        
        rows.forEach(row => {
            const nameCol = row.querySelector('td:first-child');
            if (!nameCol) return;
            
            const nameText = nameCol.textContent.toLowerCase();
            
            if (nameText.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    @if(auth()->check() && auth()->user()->hasPermission('roles.assign'))
    // Modal functions
    function openRemoveRoleModal(userId, userName) {
        const modal = document.getElementById('removeRoleModal');
        const userNameSpan = document.getElementById('removeRoleUserName');
        const userIdInput = document.getElementById('removeRoleUserId');
        const roleSelect = document.getElementById('removeRoleSelect');
        
        // Set user information
        userNameSpan.textContent = userName;
        userIdInput.value = userId;
        
        // Populate role select options
        roleSelect.innerHTML = '';
        const roles = userRoles[userId] || [];
        
        roles.forEach(role => {
            const option = document.createElement('option');
            option.value = role.id;
            option.textContent = role.name;
            roleSelect.appendChild(option);
        });
        
        // Show modal
        modal.classList.remove('hidden');
    }
    
    function closeRemoveRoleModal() {
        document.getElementById('removeRoleModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('removeRoleModal');
        if (event.target === modal) {
            closeRemoveRoleModal();
        }
    });
    @endif
</script>
@endsection

@endsection 