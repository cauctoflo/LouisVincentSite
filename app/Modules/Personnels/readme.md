# Système de Gestion des Permissions et des Rôles

Ce module implémente un système complet de gestion des permissions et des rôles pour votre application Laravel.

## Fonctionnalités

- Gestion des permissions individuelles pour chaque utilisateur
- Création et configuration de rôles (groupes de permissions)
- Attribution de rôles aux utilisateurs
- Vérification des permissions via différentes méthodes
- Interface d'administration pour configurer les permissions

## Structure du module

- **Models/**
  - `Role.php` - Modèle pour les rôles (groupes de permissions)
  - Le modèle `User` de Laravel est étendu avec les fonctionnalités de permissions

- **Controllers/**
  - `PermissionController.php` - Gestion des permissions individuelles
  - `RoleController.php` - Gestion des rôles

- **Migrations/**
  - `add_permissions_to_users_table.php` - Ajout des colonnes nécessaires à la table users

- **Views/**
  - `roles/` - Vues pour la gestion des rôles
  - `permissions/` - Vues pour la gestion des permissions

- **Providers/**
  - `PermissionServiceProvider.php` - Enregistrement des directives Blade et Gates
  - `PermissionMiddleware.php` - Middleware pour vérifier les permissions

## Installation

1. Exécutez la migration pour ajouter les colonnes nécessaires à la table `users` et créer la table des rôles:
   ```bash
   php artisan migrate
   ```

2. Enregistrez le `PermissionServiceProvider` dans `config/app.php`:
   ```php
   'providers' => [
       // ...
       App\Modules\Personnels\Providers\PermissionServiceProvider::class,
   ],
   ```

3. Enregistrez le middleware dans `app/Http/Kernel.php`:
   ```php
   protected $routeMiddleware = [
       // ...
       'permission' => \App\Modules\Personnels\Providers\PermissionMiddleware::class,
   ];
   ```

## Utilisation

### Vérification des permissions dans le code

```php
// Vérifier si l'utilisateur a une permission spécifique
if ($user->hasPermission('dashboard.view')) {
    // L'utilisateur a la permission
}

// Vérifier si l'utilisateur a un rôle spécifique
if ($user->hasRole('admin')) {
    // L'utilisateur a le rôle
}

// Vérifier si l'utilisateur a accès via une permission ou un rôle
if ($user->hasAccess('users.edit')) {
    // L'utilisateur a accès
}
```

### Vérification des permissions dans les vues Blade

```blade
@permission('nom.permission')
    <!-- Contenu accessible uniquement avec cette permission -->
@endpermission

@role('nom_du_role')
    <!-- Contenu accessible uniquement avec ce rôle -->
@endrole

@hasAccess('nom.permission')
    <!-- Contenu accessible avec la permission directe ou via un rôle -->
@endhasAccess



@if(auth()->check() && auth()->user()->hasPermission('nom.permission'))
@endif
```

### Protection des routes

```php
// Protection d'une route spécifique
Route::get('/admin/dashboard', 'DashboardController@index')
    ->middleware('permission:dashboard.view');

// Protection d'un groupe de routes
Route::prefix('admin/users')->middleware('permission:users.manage')->group(function () {
    // Routes protégées ici
});
```

### Gestion des permissions

Pour gérer les permissions, accédez aux URL suivantes:

- `/admin/roles` - Gestion des rôles
- `/admin/permissions` - Gestion des permissions individuelles

## Personnalisation des permissions

Pour ajouter de nouvelles permissions, modifiez la propriété `$availablePermissions` dans `PermissionController.php`:

```php
private $availablePermissions = [
    'dashboard.view' => 'Voir le tableau de bord',
    'users.view' => 'Voir les utilisateurs',
    // Ajoutez vos propres permissions ici
];
```

## Remarques importantes

- Un utilisateur avec `is_admin = true` a automatiquement toutes les permissions.
- Les permissions peuvent être attribuées directement à un utilisateur ou via des rôles.
- Un utilisateur peut avoir plusieurs rôles.
- Les permissions sont stockées au format JSON dans la base de données.

## Exemple de flux d'utilisation

1. Créez des rôles avec des ensembles spécifiques de permissions
2. Attribuez ces rôles aux utilisateurs
3. Pour les cas particuliers, ajoutez des permissions individuelles aux utilisateurs
4. Utilisez les directives Blade ou les middlewares pour contrôler l'accès