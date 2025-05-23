@extends('layouts.admin')

@section('content')
<div class="main-content flex-1 bg-gray-50 p-6 overflow-y-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h1 class="text-2xl font-display font-bold text-gray-900">Gestion des rôles</h1>
        @if(auth()->check() && auth()->user()->hasPermission('roles.create'))
        <a href="{{ route('personnels.roles.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-all shadow-sm">
            <i class="fas fa-plus mr-2"></i> Créer un rôle
        </a>
        @endif
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

    <!-- Search and Filter -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="searchRole" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Rechercher un rôle...">
                    </div>
                </div>
                <div class="w-full md:w-auto">
                    <select id="filterPermissions" class="block w-full border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3">
                        <option value="">Toutes les permissions</option>
                        <!-- Options would be populated with JavaScript -->
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="rolesTableBody">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 flex items-center justify-center rounded-md bg-indigo-100 text-indigo-600 mr-3">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    {{ $role->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($role->description)
                                    {{ $role->description }}
                                @else
                                    <span class="text-gray-400 italic">Aucune description</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if (is_array($role->permissions) && count($role->permissions) > 0)
                                    <div class="flex flex-wrap gap-1.5 max-w-md">
                                        @foreach ($role->permissions as $permission)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $permission }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">Aucune permission</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    @if(auth()->check() && auth()->user()->hasPermission('roles.edit'))
                                    <a href="{{ route('personnels.roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors p-1.5 rounded-full hover:bg-indigo-50" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif

                                    @if(auth()->check() && auth()->user()->hasPermission('roles.delete'))
                                    <form action="{{ route('personnels.roles.destroy', $role) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1.5 rounded-full hover:bg-red-50" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle?')" 
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif


                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 mb-4">
                                        <i class="fas fa-user-tag text-2xl"></i>
                                    </div>
                                    <span class="text-gray-500 font-medium">Aucun rôle trouvé</span>
                                    <p class="text-gray-400 mt-1 max-w-md">Commencez par créer un rôle pour gérer les permissions des utilisateurs</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    


    @if (auth()->check() && !auth()->user()->hasPermission('roles.view_only'))
    <div class="role-view-only-notice mt-6">
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">Mode lecture seule : Vous avez uniquement accès à la visualisation</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Simple client-side search functionality
    document.getElementById('searchRole').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#rolesTableBody tr');
        
        rows.forEach(row => {
            const nameCol = row.querySelector('td:first-child');
            const descCol = row.querySelector('td:nth-child(2)');
            
            if (!nameCol || !descCol) return;
            
            const nameText = nameCol.textContent.toLowerCase();
            const descText = descCol.textContent.toLowerCase();
            
            if (nameText.includes(searchText) || descText.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Function to view role details (would be implemented with a modal)
    function viewRoleDetails(roleId) {
        // Implementation would show a modal with detailed role information
        console.log('View details for role ID:', roleId);
    }
</script>
@endsection 