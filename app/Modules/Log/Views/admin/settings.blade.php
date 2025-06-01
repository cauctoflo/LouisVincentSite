@extends('layouts.admin')

@section('title', 'Paramètres des journalisation du site internet')

@section('css')
<style>
.tab-content:not(.hidden) {
    display: block !important;
}
</style>
@endsection

@section('content')
<!-- Ajout du meta tag CSRF -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Configuration des logs système</h1>
            <button type="button" id="save-config" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form id="log-settings-form" method="POST">
                @csrf
                <!-- Onglets -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-target="general">
                            Général
                        </button>
                        <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-target="log-files">
                            Fichiers de log
                        </button>
                        <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-target="levels">
                            Niveaux de log
                        </button>
                    </nav>
                </div>

                <!-- Contenu des onglets -->
                <div class="p-6">
                    <!-- Onglet Général -->
                    <div id="general" class="tab-content hidden">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Paramètres généraux</h3>
                                <p class="mt-1 text-sm text-gray-500">Configuration des options générales de journalisation.</p>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" id="user_actions_enabled" name="user_actions_enabled" {{ $config['user_actions_enabled'] ?? false ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="user_actions_enabled" class="font-medium text-gray-700">Activer le logging des actions utilisateurs</label>
                                        <p class="text-gray-500">Enregistre toutes les actions importantes effectuées par les utilisateurs.</p>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-900">Actions à logger</h4>
                                    <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach(['create' => 'Création', 'update' => 'Modification', 'delete' => 'Suppression', 'login' => 'Connexion', 'logout' => 'Déconnexion'] as $action => $label)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" 
                                                       id="actions_{{ $action }}" 
                                                       name="actions_to_log[{{ $action }}]" 
                                                       {{ isset($config['actions_to_log'][$action]) && $config['actions_to_log'][$action] ? 'checked' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="actions_{{ $action }}" class="font-medium text-gray-700">{{ $label }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <label for="excluded_columns" class="block text-sm font-medium text-gray-700">Colonnes à exclure</label>
                                    <p class="text-xs text-gray-500 mb-2">Entrez les noms des colonnes à exclure des détails de logs (un par ligne)</p>
                                    <textarea id="excluded_columns" name="excluded_columns" rows="4" 
                                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md font-mono">{{ implode("\n", $config['excluded_columns'] ?? []) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Fichiers de log -->
                    <div id="log-files" class="tab-content hidden">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Fichiers de log</h3>
                                <p class="mt-1 text-sm text-gray-500">Gestion des fichiers de journalisation.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                <div>
                                    <label for="max_age_days" class="block text-sm font-medium text-gray-700">Âge maximum des logs (jours)</label>
                                    <input type="number" id="max_age_days" name="log_files[max_age_days]" 
                                           value="{{ $config['log_files']['max_age_days'] ?? 30 }}" min="1" max="365" 
                                           class="mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div>
                                    <label for="max_size_mb" class="block text-sm font-medium text-gray-700">Taille maximale (Mo)</label>
                                    <input type="number" id="max_size_mb" name="log_files[max_size_mb]" 
                                           value="{{ $config['log_files']['max_size_mb'] ?? 100 }}" min="10" max="1000" 
                                           class="mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div class="flex items-start pt-6">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="auto_clean" name="log_files[auto_clean]" 
                                               {{ $config['log_files']['auto_clean'] ?? false ? 'checked' : '' }} 
                                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="auto_clean" class="font-medium text-gray-700">Nettoyage automatique</label>
                                        <p class="text-gray-500">Nettoie automatiquement les anciens fichiers de log.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation entre les types de logs -->
                            <div class="flex border-b border-gray-200 mb-4">
                                <button type="button" class="log-nav-button border-blue-500 text-blue-600 whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm" data-target="file-logs">
                                    Fichiers de log
                                </button>
                                <button type="button" class="log-nav-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm" data-target="db-logs">
                                    Logs de la base de données
                                </button>
                            </div>

                            <!-- Fichiers de log -->
                            <div id="file-logs" class="log-section">
                                <h4 class="text-sm font-medium text-gray-900 mb-4">Fichiers de log actuels</h4>
                                <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200" id="logs-table">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taille</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière modification</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    Chargement des fichiers de log...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Logs de la base de données -->
                            <div id="db-logs" class="log-section hidden">
                                <h4 class="text-sm font-medium text-gray-900 mb-4">Logs récents de la base de données</h4>
                                <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200" id="db-logs-table">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    Chargement des logs de la base de données...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 flex justify-center">
                                    <a href="{{ route('personnels.Log.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-list-ul mr-2"></i> Voir tous les logs
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Niveaux de log -->
                    <div id="levels" class="tab-content hidden">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Niveaux de log</h3>
                                <p class="mt-1 text-sm text-gray-500">Configuration des niveaux de log à enregistrer.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div class="space-y-4">
                                    <h4 class="text-sm font-medium text-gray-900">Niveaux critiques</h4>
                                    <div class="space-y-3">
                                        @foreach(['emergency' => 'Système inutilisable', 'alert' => 'Action immédiate requise', 'critical' => 'Conditions critiques', 'error' => 'Erreurs d\'exécution'] as $level => $description)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" id="level_{{ $level }}" name="levels[{{ $level }}]" 
                                                       {{ isset($config['levels'][$level]) && $config['levels'][$level] ? 'checked' : '' }} 
                                                       class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="level_{{ $level }}" class="font-medium text-gray-700">{{ ucfirst($level) }}</label>
                                                <p class="text-gray-500">{{ $description }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-sm font-medium text-gray-900">Niveaux informatifs</h4>
                                    <div class="space-y-3">
                                        @foreach(['warning' => 'Conditions exceptionnelles', 'notice' => 'Événements normaux mais significatifs', 'info' => 'Messages informatifs', 'debug' => 'Informations de débogage détaillées'] as $level => $description)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" id="level_{{ $level }}" name="levels[{{ $level }}]" 
                                                       {{ isset($config['levels'][$level]) && $config['levels'][$level] ? 'checked' : '' }} 
                                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="level_{{ $level }}" class="font-medium text-gray-700">{{ ucfirst($level) }}</label>
                                                <p class="text-gray-500">{{ $description }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de visualisation des logs -->
<div class="fixed inset-0 overflow-y-auto hidden" id="viewLogModal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-7xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Contenu du fichier de log <span id="modal-log-title"></span>
                        </h3>
                        <div class="mt-4">
                            <div class="flex justify-between mb-4">
                                <div class="flex space-x-2">
                                    <button type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="filter-all">Tous</button>
                                    <button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" id="filter-error">Erreurs</button>
                                    <button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" id="filter-warning">Avertissements</button>
                                    <button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="filter-info">Info</button>
                                    <button type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" id="filter-debug">Debug</button>
                                </div>
                                <button type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="export-log">
                                    <i class="fas fa-file-export mr-1.5"></i> Exporter
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <div id="logContent" class="max-h-96 overflow-y-auto">
                                    <p class="text-center text-gray-500">Chargement du contenu...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="closeLogModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activation des onglets
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        // Activer le premier onglet par défaut
        tabButtons[0].classList.add('border-blue-500', 'text-blue-600');
        tabContents[0].classList.remove('hidden');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Désactiver tous les onglets
                tabButtons.forEach(btn => {
                    btn.classList.remove('border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Activer l'onglet sélectionné
                this.classList.add('border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');
                
                const targetId = this.getAttribute('data-target');
                document.getElementById(targetId).classList.remove('hidden');
                
                // Charger les données correspondantes si nécessaire
                if (targetId === 'log-files') {
                    fetchLogFiles();
                    fetchDbLogs();
                }
            });
        });

        // Navigation entre les logs fichiers et logs BD
        const logNavButtons = document.querySelectorAll('.log-nav-button');
        const logSections = document.querySelectorAll('.log-section');
        
        logNavButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Désactiver tous les boutons
                logNavButtons.forEach(btn => {
                    btn.classList.remove('border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Cacher toutes les sections
                logSections.forEach(section => {
                    section.classList.add('hidden');
                });
                
                // Activer le bouton sélectionné
                this.classList.add('border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');
                
                // Afficher la section sélectionnée
                const targetId = this.getAttribute('data-target');
                document.getElementById(targetId).classList.remove('hidden');
            });
        });

        // Gestion des fichiers de log
        fetchLogFiles();
        fetchDbLogs();

        // Sauvegarde de la configuration
        document.getElementById('save-config').addEventListener('click', function() {
            saveConfig();
        });

        // Fermeture du modal
        document.getElementById('closeLogModal').addEventListener('click', function() {
            document.getElementById('viewLogModal').classList.add('hidden');
        });
        
        // Filtrage des logs
        document.getElementById('filter-all').addEventListener('click', function() {
            filterLogEntries('all');
        });
        
        document.getElementById('filter-error').addEventListener('click', function() {
            filterLogEntries('error');
        });
        
        document.getElementById('filter-warning').addEventListener('click', function() {
            filterLogEntries('warning');
        });
        
        document.getElementById('filter-info').addEventListener('click', function() {
            filterLogEntries('info');
        });
        
        document.getElementById('filter-debug').addEventListener('click', function() {
            filterLogEntries('debug');
        });

        // Exportation des logs
        document.getElementById('export-log').addEventListener('click', function() {
            const filename = document.getElementById('modal-log-title').textContent.trim();
            if (filename) {
                window.location.href = `{{ route("personnels.Log.exportLogs") }}?file=${encodeURIComponent(filename)}`;
            }
        });
    });

    // Fonctions pour la gestion des fichiers de log
    function fetchLogFiles() {
        fetch('{{ route("personnels.Log.getLogs") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#logs-table tbody');
            tableBody.innerHTML = '';
            
            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucun fichier de log trouvé.
                        </td>
                    </tr>
                `;
                return;
            }
            
            data.forEach(log => {
                const row = document.createElement('tr');
                row.className = 'bg-white';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${log.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.size}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.modified}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" onclick="viewLog('${log.name}')" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" onclick="deleteLog('${log.name}')" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des logs:', error);
            const tableBody = document.querySelector('#logs-table tbody');
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-red-500">
                        Erreur lors du chargement des fichiers de log.
                    </td>
                </tr>
            `;
        });
    }

    function fetchDbLogs() {
        fetch('{{ route("personnels.Log.getDbLogs") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#db-logs-table tbody');
            tableBody.innerHTML = '';
            
            if (data.logs.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucun log trouvé dans la base de données.
                        </td>
                    </tr>
                `;
                return;
            }
            
            data.logs.forEach(log => {
                const row = document.createElement('tr');
                row.className = 'bg-white hover:bg-gray-50';

                // Déterminer la classe de badge pour l'action
                let actionClass = 'bg-blue-100 text-blue-800';
                if (log.action === 'create') {
                    actionClass = 'bg-green-100 text-green-800';
                } else if (log.action === 'update') {
                    actionClass = 'bg-blue-100 text-blue-800';
                } else if (log.action === 'delete') {
                    actionClass = 'bg-red-100 text-red-800';
                } else if (['login', 'logout'].includes(log.action)) {
                    actionClass = 'bg-purple-100 text-purple-800';
                }
                
                const formattedDate = new Date(log.created_at).toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formattedDate}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${actionClass}">
                            ${log.action}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.model_type ? log.model_type.split('\\').pop() : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.user ? log.user.name : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="/personnels/log/${log.id}" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des logs:', error);
            const tableBody = document.querySelector('#db-logs-table tbody');
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-red-500">
                        Erreur lors du chargement des logs de la base de données.
                    </td>
                </tr>
            `;
        });
    }

    function viewLog(filename) {
        fetch(`{{ route("personnels.Log.viewLog") }}?file=${encodeURIComponent(filename)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Ouvrir le modal et afficher les données
            const modal = document.getElementById('viewLogModal');
            const logContent = document.getElementById('logContent');
            const modalTitle = document.getElementById('modal-log-title');
            
            modalTitle.textContent = filename;
            
            // Afficher les entrées de log formatées
            let contentHtml = '';
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(entry => {
                    let levelClass = 'text-blue-600';
                    let levelBadgeClass = 'bg-blue-100 text-blue-800';
                    
                    if (['emergency', 'alert', 'critical', 'error'].includes(entry.level.toLowerCase())) {
                        levelClass = 'text-red-600';
                        levelBadgeClass = 'bg-red-100 text-red-800';
                    } else if (['warning'].includes(entry.level.toLowerCase())) {
                        levelClass = 'text-yellow-600';
                        levelBadgeClass = 'bg-yellow-100 text-yellow-800';
                    } else if (['debug'].includes(entry.level.toLowerCase())) {
                        levelClass = 'text-gray-600';
                        levelBadgeClass = 'bg-gray-100 text-gray-800';
                    }
                    
                    contentHtml += `
                        <div class="mb-4 pb-4 border-b border-gray-200 log-entry" data-level="${entry.level.toLowerCase()}">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">${entry.date}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${levelBadgeClass}">
                                    ${entry.level.toUpperCase()}
                                </span>
                            </div>
                            <div class="mb-1 text-xs text-gray-500">${entry.channel}</div>
                            <div class="mt-1 text-sm">
                                <pre class="whitespace-pre-wrap break-words">${entry.message}</pre>
                            </div>
                        </div>
                    `;
                });
            } else {
                contentHtml = '<p class="text-center text-gray-500">Aucune entrée de log trouvée ou format de log non reconnu.</p>';
            }
            
            logContent.innerHTML = contentHtml;
            modal.classList.remove('hidden');
            
            // Réinitialiser le filtre sur "Tous"
            filterLogEntries('all');
        })
        .catch(error => {
            console.error('Erreur lors de la récupération du contenu du log:', error);
            alert('Erreur lors de la récupération du contenu du log.');
        });
    }

    function filterLogEntries(level) {
        const entries = document.querySelectorAll('.log-entry');
        
        entries.forEach(entry => {
            if (level === 'all' || entry.dataset.level === level) {
                entry.style.display = '';
            } else {
                entry.style.display = 'none';
            }
        });
        
        // Mettre à jour les styles des boutons de filtre
        document.querySelectorAll('#filter-all, #filter-error, #filter-warning, #filter-info, #filter-debug').forEach(btn => {
            btn.classList.remove('ring-2');
        });
        
        document.getElementById(`filter-${level}`).classList.add('ring-2');
    }

    function deleteLog(filename) {
        if (!confirm(`Êtes-vous sûr de vouloir supprimer le fichier de log "${filename}" ?`)) {
            return;
        }
        
        fetch('{{ route("personnels.Log.deleteLog") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                file: filename
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchLogFiles();
            } else {
                alert('Erreur: ' + (data.error || 'Une erreur est survenue'));
            }
        })
        .catch(error => {
            console.error('Erreur lors de la suppression du log:', error);
            alert('Erreur lors de la suppression du fichier de log.');
        });
    }

    function saveConfig() {
        const form = document.getElementById('log-settings-form');
        const formData = new FormData(form);
        
        // Traiter les données pour les colonnes exclues
        const excludedColumns = document.getElementById('excluded_columns').value
            .split('\n')
            .map(column => column.trim())
            .filter(column => column.length > 0);
        
        // Remplacer les listes de texte par des tableaux
        formData.delete('excluded_columns');
        
        // Convertir les données en objet pour l'envoi AJAX
        const data = {};
        formData.forEach((value, key) => {
            if (key.includes('[')) {
                const mainKey = key.substring(0, key.indexOf('['));
                const subKey = key.substring(key.indexOf('[') + 1, key.indexOf(']'));
                
                if (!data[mainKey]) {
                    data[mainKey] = {};
                }
                
                data[mainKey][subKey] = value === 'on' ? true : value;
            } else {
                data[key] = value === 'on' ? true : value;
            }
        });
        
        // Ajouter les tableaux
        data.excluded_columns = excludedColumns;
        
        // Envoyer la configuration
        fetch('{{ route("personnels.Log.saveConfig") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Configuration enregistrée avec succès');
            } else {
                alert('Erreur: ' + (data.error || 'Une erreur est survenue'));
            }
        })
        .catch(error => {
            console.error('Erreur lors de la sauvegarde de la configuration:', error);
            alert('Erreur lors de la sauvegarde de la configuration.');
        });
    }
</script>
@endsection 