@extends('layouts.app')

@section('title', 'Configuration des logs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Configuration des logs système</h5>
                    <div>
                        <button id="save-config" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="log-settings-form">
                        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                                    Général
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tracked-models-tab" data-toggle="tab" href="#tracked-models" role="tab">
                                    Modèles suivis
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="log-files-tab" data-toggle="tab" href="#log-files" role="tab">
                                    Fichiers de log
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="levels-tab" data-toggle="tab" href="#levels" role="tab">
                                    Niveaux de log
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="settingsTabsContent">
                            <!-- Onglet Général -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="user_actions_enabled" name="user_actions_enabled" {{ $config['user_actions_enabled'] ?? false ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="user_actions_enabled">Activer le logging des actions utilisateurs</label>
                                    </div>
                                </div>

                                <h6 class="mt-4">Actions à logger</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="actions_create" name="actions_to_log[create]" {{ $config['actions_to_log']['create'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="actions_create">Création</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="actions_update" name="actions_to_log[update]" {{ $config['actions_to_log']['update'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="actions_update">Modification</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="actions_delete" name="actions_to_log[delete]" {{ $config['actions_to_log']['delete'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="actions_delete">Suppression</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="actions_login" name="actions_to_log[login]" {{ $config['actions_to_log']['login'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="actions_login">Connexion</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="actions_logout" name="actions_to_log[logout]" {{ $config['actions_to_log']['logout'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="actions_logout">Déconnexion</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="actions_role_change" name="actions_to_log[role_change]" {{ $config['actions_to_log']['role_change'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="actions_role_change">Changement de rôle</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="actions_permission_change" name="actions_to_log[permission_change]" {{ $config['actions_to_log']['permission_change'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="actions_permission_change">Changement de permission</label>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mt-4">Interface utilisateur</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="items_per_page">Éléments par page</label>
                                            <input type="number" class="form-control" id="items_per_page" name="ui[items_per_page]" value="{{ $config['ui']['items_per_page'] ?? 20 }}" min="5" max="100">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="refresh_interval">Intervalle de rafraîchissement (secondes, 0 = désactivé)</label>
                                            <input type="number" class="form-control" id="refresh_interval" name="ui[refresh_interval]" value="{{ $config['ui']['refresh_interval'] ?? 0 }}" min="0" max="3600">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_format">Format de date</label>
                                            <input type="text" class="form-control" id="date_format" name="ui[date_format]" value="{{ $config['ui']['date_format'] ?? 'd/m/Y H:i:s' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet Modèles suivis -->
                            <div class="tab-pane fade" id="tracked-models" role="tabpanel">
                                <div class="form-group">
                                    <label for="tracked_models">Modèles à suivre</label>
                                    <p class="text-muted">Entrez les noms complets des classes des modèles à suivre (un par ligne)</p>
                                    <textarea class="form-control" id="tracked_models" name="tracked_models" rows="10">{{ implode("\n", $config['tracked_models'] ?? []) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="excluded_columns">Colonnes à exclure</label>
                                    <p class="text-muted">Entrez les noms des colonnes à exclure des détails de logs (un par ligne)</p>
                                    <textarea class="form-control" id="excluded_columns" name="excluded_columns" rows="5">{{ implode("\n", $config['excluded_columns'] ?? []) }}</textarea>
                                </div>
                            </div>

                            <!-- Onglet Fichiers de log -->
                            <div class="tab-pane fade" id="log-files" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="max_age_days">Âge maximum des logs (jours)</label>
                                            <input type="number" class="form-control" id="max_age_days" name="log_files[max_age_days]" value="{{ $config['log_files']['max_age_days'] ?? 30 }}" min="1" max="365">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="max_size_mb">Taille maximale (Mo)</label>
                                            <input type="number" class="form-control" id="max_size_mb" name="log_files[max_size_mb]" value="{{ $config['log_files']['max_size_mb'] ?? 100 }}" min="10" max="1000">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mt-4">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="auto_clean" name="log_files[auto_clean]" {{ $config['log_files']['auto_clean'] ?? false ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="auto_clean">Nettoyage automatique</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mt-3">Fichiers de log actuels</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="logs-table">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Taille</th>
                                                <th>Dernière modification</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="text-center">Chargement des fichiers de log...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <button type="button" id="refresh-logs" class="btn btn-secondary">
                                        <i class="fas fa-sync"></i> Rafraîchir
                                    </button>
                                    <button type="button" id="clear-all-logs" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Supprimer tous les logs
                                    </button>
                                </div>
                            </div>

                            <!-- Onglet Niveaux de log -->
                            <div class="tab-pane fade" id="levels" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Niveaux de log à afficher</h6>
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="level_emergency" name="levels[emergency]" {{ $config['levels']['emergency'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="level_emergency">Emergency</label>
                                        </div>
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="level_alert" name="levels[alert]" {{ $config['levels']['alert'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="level_alert">Alert</label>
                                        </div>
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="level_critical" name="levels[critical]" {{ $config['levels']['critical'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="level_critical">Critical</label>
                                        </div>
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="level_error" name="levels[error]" {{ $config['levels']['error'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="level_error">Error</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>&nbsp;</h6>
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="level_warning" name="levels[warning]" {{ $config['levels']['warning'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="level_warning">Warning</label>
                                        </div>
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="level_notice" name="levels[notice]" {{ $config['levels']['notice'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="level_notice">Notice</label>
                                        </div>
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="level_info" name="levels[info]" {{ $config['levels']['info'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="level_info">Info</label>
                                        </div>
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="level_debug" name="levels[debug]" {{ $config['levels']['debug'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="level_debug">Debug</label>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mt-4">Canaux de log</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="channel_stack" name="channels[stack]" {{ $config['channels']['stack'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="channel_stack">Stack</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="channel_daily" name="channels[daily]" {{ $config['channels']['daily'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="channel_daily">Daily</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="channel_slack" name="channels[slack]" {{ $config['channels']['slack'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="channel_slack">Slack</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch mb-2">
                                            <input type="checkbox" class="custom-control-input" id="channel_database" name="channels[database]" {{ $config['channels']['database'] ?? false ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="channel_database">Database</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de visualisation des logs -->
    <div class="modal fade" id="viewLogModal" tabindex="-1" role="dialog" aria-labelledby="viewLogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewLogModalLabel">Contenu du fichier de log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="filter-all">Tous</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="filter-error">Erreurs</button>
                                <button type="button" class="btn btn-sm btn-outline-warning" id="filter-warning">Avertissements</button>
                                <button type="button" class="btn btn-sm btn-outline-info" id="filter-info">Info</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="filter-debug">Debug</button>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" id="export-log">
                                <i class="fas fa-file-export"></i> Exporter
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm" id="log-content-table">
                            <thead>
                                <tr>
                                    <th>Date et heure</th>
                                    <th>Niveau</th>
                                    <th>Canal</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">Chargement du contenu...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Chargement des fichiers de log
        function loadLogFiles() {
            $.ajax({
                url: '/api/logs/files',
                method: 'GET',
                success: function(response) {
                    if (response.length === 0) {
                        $('#logs-table tbody').html('<tr><td colspan="4" class="text-center">Aucun fichier de log trouvé</td></tr>');
                        return;
                    }
                    
                    let html = '';
                    $.each(response, function(index, log) {
                        html += `<tr>
                            <td>${log.name}</td>
                            <td>${log.size}</td>
                            <td>${log.modified}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-log" data-file="${log.name}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary export-log" data-file="${log.name}">
                                    <i class="fas fa-file-export"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-log" data-file="${log.name}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                    
                    $('#logs-table tbody').html(html);
                },
                error: function() {
                    $('#logs-table tbody').html('<tr><td colspan="4" class="text-center text-danger">Erreur lors du chargement des fichiers de log</td></tr>');
                }
            });
        }

        // Événement de rafraîchissement des fichiers de log
        $('#refresh-logs').click(function() {
            loadLogFiles();
        });

        // Événement de suppression d'un fichier de log
        $(document).on('click', '.delete-log', function() {
            const file = $(this).data('file');
            
            if (confirm(`Êtes-vous sûr de vouloir supprimer le fichier ${file} ?`)) {
                $.ajax({
                    url: '/api/logs/delete',
                    method: 'POST',
                    data: { file: file },
                    success: function(response) {
                        toastr.success('Fichier de log supprimé avec succès');
                        loadLogFiles();
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.error || 'Erreur lors de la suppression du fichier');
                    }
                });
            }
        });

        // Événement de suppression de tous les fichiers de log
        $('#clear-all-logs').click(function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer TOUS les fichiers de log ? Cette action est irréversible.')) {
                $.ajax({
                    url: '/api/logs/clear-all',
                    method: 'POST',
                    success: function(response) {
                        toastr.success(response.message);
                        loadLogFiles();
                    },
                    error: function() {
                        toastr.error('Erreur lors de la suppression des fichiers de log');
                    }
                });
            }
        });

        // Affichage du contenu d'un fichier de log
        $(document).on('click', '.view-log', function() {
            const file = $(this).data('file');
            $('#viewLogModalLabel').text(`Contenu du fichier: ${file}`);
            
            // Stocker le nom du fichier courant pour l'exportation
            $('#export-log').data('file', file);
            
            $.ajax({
                url: '/api/logs/view',
                method: 'GET',
                data: { file: file },
                success: function(entries) {
                    if (entries.length === 0) {
                        $('#log-content-table tbody').html('<tr><td colspan="4" class="text-center">Aucune entrée trouvée dans ce fichier</td></tr>');
                        return;
                    }
                    
                    let html = '';
                    $.each(entries, function(index, entry) {
                        const levelClass = getLevelClass(entry.level);
                        html += `<tr class="log-entry ${entry.level.toLowerCase()}">
                            <td>${entry.datetime}</td>
                            <td><span class="badge ${levelClass}">${entry.level}</span></td>
                            <td>${entry.channel}</td>
                            <td>${entry.message}</td>
                        </tr>`;
                    });
                    
                    $('#log-content-table tbody').html(html);
                    $('#viewLogModal').modal('show');
                },
                error: function() {
                    toastr.error('Erreur lors de la lecture du fichier de log');
                }
            });
        });

        // Fonction pour déterminer la classe CSS selon le niveau de log
        function getLevelClass(level) {
            level = level.toLowerCase();
            switch (level) {
                case 'emergency':
                case 'alert':
                case 'critical':
                case 'error':
                    return 'badge-danger';
                case 'warning':
                    return 'badge-warning';
                case 'notice':
                case 'info':
                    return 'badge-info';
                case 'debug':
                    return 'badge-secondary';
                default:
                    return 'badge-light';
            }
        }

        // Événement d'exportation d'un fichier de log
        $(document).on('click', '.export-log, #export-log', function() {
            const file = $(this).data('file');
            window.location.href = `/api/logs/export?file=${file}`;
        });

        // Filtres pour les entrées de log
        $('#filter-all').click(function() {
            $('.log-entry').show();
        });

        $('#filter-error').click(function() {
            $('.log-entry').hide();
            $('.log-entry.emergency, .log-entry.alert, .log-entry.critical, .log-entry.error').show();
        });

        $('#filter-warning').click(function() {
            $('.log-entry').hide();
            $('.log-entry.warning').show();
        });

        $('#filter-info').click(function() {
            $('.log-entry').hide();
            $('.log-entry.info, .log-entry.notice').show();
        });

        $('#filter-debug').click(function() {
            $('.log-entry').hide();
            $('.log-entry.debug').show();
        });

        // Sauvegarde de la configuration
        $('#save-config').click(function() {
            const formData = new FormData($('#log-settings-form')[0]);
            const config = {};
            
            // Convertir les données de formulaire en objet JSON
            for (let [key, value] of formData.entries()) {
                if (key.includes('[')) {
                    // Gestion des tableaux et objets imbriqués
                    const matches = key.match(/^([^\[]+)(?:\[([^\]]+)\])?(?:\[([^\]]+)\])?/);
                    if (matches) {
                        const mainKey = matches[1];
                        const subKey = matches[2];
                        const subSubKey = matches[3];
                        
                        if (!config[mainKey]) {
                            config[mainKey] = subSubKey ? { [subKey]: {} } : {};
                        }
                        
                        if (subSubKey) {
                            if (!config[mainKey][subKey]) {
                                config[mainKey][subKey] = {};
                            }
                            config[mainKey][subKey][subSubKey] = value === 'on' ? true : value;
                        } else if (subKey) {
                            config[mainKey][subKey] = value === 'on' ? true : value;
                        }
                    }
                } else {
                    config[key] = value;
                }
            }
            
            // Traitement spécial pour les champs texte qui sont des tableaux
            if ($('#tracked_models').val()) {
                config.tracked_models = $('#tracked_models').val().split('\n').filter(line => line.trim() !== '');
            }
            
            if ($('#excluded_columns').val()) {
                config.excluded_columns = $('#excluded_columns').val().split('\n').filter(line => line.trim() !== '');
            }
            
            $.ajax({
                url: '/api/logs/config',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(config),
                success: function(response) {
                    toastr.success('Configuration sauvegardée avec succès');
                },
                error: function() {
                    toastr.error('Erreur lors de la sauvegarde de la configuration');
                }
            });
        });

        // Chargement initial des fichiers de log
        loadLogFiles();
    });
</script>
@endsection 