<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration du module Log
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient les paramètres de configuration pour le module Log.
    |
    */

    // Activation du logging des actions utilisateurs
    'user_actions_enabled' => true,

    // Types d'actions à logger
    'actions_to_log' => [
        'create' => true,
        'update' => true,
        'delete' => true,
        'login' => true,
        'logout' => true,
        'role_change' => true,
        'permission_change' => true,
    ],

    // Modèles à suivre pour le logging (classes complètes)
    'tracked_models' => [
        'App\Models\User',
        // Ajouter d'autres modèles selon les besoins
    ],

    // Colonnes à exclure des détails enregistrés
    'excluded_columns' => [
        'password',
        'remember_token',
        'api_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ],

    // Paramètres pour les fichiers de log
    'log_files' => [
        'max_age_days' => 30,      // Nombre de jours à conserver
        'max_size_mb' => 100,      // Taille maximale en Mo
        'auto_clean' => true,      // Nettoyage automatique des anciens logs
    ],

    // Paramètres pour l'interface
    'ui' => [
        'items_per_page' => 20,    // Nombre d'entrées par page dans l'interface
        'refresh_interval' => 60,  // Intervalle de rafraîchissement en secondes (0 = désactivé)
        'date_format' => 'd/m/Y H:i:s',
    ],

    // Configuration des exports
    'export' => [
        'max_records' => 5000,     // Nombre maximum d'enregistrements à exporter
        'default_format' => 'xlsx', // Format par défaut (xlsx, csv, etc.)
    ],

    // Configuration des niveaux de log à afficher
    'levels' => [
        'emergency' => true,
        'alert' => true,
        'critical' => true,
        'error' => true,
        'warning' => true,
        'notice' => true,
        'info' => true,
        'debug' => true,
    ],

    // Canaux de log à suivre
    'channels' => [
        'stack' => true,
        'daily' => true,
        'slack' => false,
        'database' => true,
    ],
]; 