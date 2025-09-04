# Système de Modules Modulaire

## Vue d'ensemble

Ce système permet de créer des modules facilement intégrés dans la sidebar administrative avec gestion automatique des permissions et de l'état actif/inactif.

## Architecture

### 1. Interface ModuleInterface
Définit le contrat que chaque module doit implémenter.

### 2. BaseModule (Classe abstraite)
Fournit les fonctionnalités de base communes à tous les modules.

### 3. ModuleRegistry
Gère l'enregistrement et la récupération des modules.

### 4. Modules spécifiques
Chaque module hérite de BaseModule et définit sa configuration spécifique.

## Créer un nouveau module

### 1. Créer la classe du module

```php
<?php

namespace App\Modules\MonModule;

use App\Core\Module\BaseModule;

class MonModule extends BaseModule
{
    protected string $name = 'MonModule';
    protected string $displayName = 'Mon Super Module';
    protected string $description = 'Description de mon module';
    protected string $icon = 'fas fa-star'; // Icône FontAwesome
    protected int $order = 45; // Ordre dans la sidebar (plus petit = plus haut)

    public function getRoutes(): array
    {
        return [
            [
                'name' => 'Vue principale',
                'route' => 'personnels.monmodule.index',
                'icon' => 'fas fa-home',
                'permission' => 'view_monmodule' // null si pas de permission
            ],
            [
                'name' => 'Paramètres',
                'route' => 'personnels.monmodule.settings',
                'icon' => 'fas fa-cog',
                'permission' => 'manage_monmodule'
            ],
            // Action spéciale (bouton avec JavaScript)
            [
                'name' => 'Action spéciale',
                'route' => null,
                'icon' => 'fas fa-magic',
                'permission' => 'special_action',
                'action' => 'mon-action' // Référence dans sidebar-action.blade.php
            ],
            // URL externe ou custom
            [
                'name' => 'Lien externe',
                'route' => null,
                'icon' => 'fas fa-link',
                'permission' => null,
                'url' => 'https://example.com'
            ],
            // Placeholder pour module en développement
            [
                'name' => 'En développement',
                'route' => null,
                'icon' => 'fas fa-tools',
                'permission' => null,
                'isPlaceholder' => true
            ]
        ];
    }

    public function getPermissions(): array
    {
        return [
            'view_monmodule',
            'manage_monmodule',
            'special_action'
        ];
    }

    // Optionnel: forcer l'activation (pour modules core)
    public function isActive(): bool
    {
        return true; // ou parent::isActive() pour utiliser le système de fichiers
    }
}
```

### 2. Enregistrer le module

Dans `app/Providers/ModuleServiceProvider.php`, ajouter :

```php
use App\Modules\MonModule\MonModule;

// Dans la méthode boot()
ModuleRegistry::register(new MonModule());
```

### 3. Créer le fichier de configuration (optionnel)

Le module sera automatiquement "inactif" par défaut. Pour l'activer :
- Aller dans `/personnels/modules`
- Activer le module via l'interface

Ou créer manuellement le fichier :
`storage/app/modules/monmodule/config.json`
```json
{
    "status": "active"
}
```

## Types d'éléments de menu

### 1. Route standard
```php
[
    'name' => 'Ma page',
    'route' => 'personnels.module.page',
    'icon' => 'fas fa-file',
    'permission' => 'view_page'
]
```

### 2. Route avec paramètres
```php
[
    'name' => 'Filtré',
    'route' => 'personnels.module.index',
    'icon' => 'fas fa-filter',
    'permission' => 'view_filtered',
    'params' => ['filter' => 'active']
]
```

### 3. Action JavaScript
```php
[
    'name' => 'Action spéciale',
    'route' => null,
    'icon' => 'fas fa-magic',
    'permission' => 'special_action',
    'action' => 'mon-action-js'
]
```

Puis dans `resources/views/components/sidebar-action.blade.php`, ajouter :
```php
@elseif($action === 'mon-action-js')
    <button onclick="maFonction()">{{ $name }}</button>
@endif
```

### 4. URL externe/custom
```php
[
    'name' => 'Lien externe',
    'route' => null,
    'icon' => 'fas fa-external-link-alt',
    'permission' => null,
    'url' => 'https://example.com'
]
```

### 5. Placeholder
```php
[
    'name' => 'Module en développement',
    'route' => null,
    'icon' => 'fas fa-tools',
    'permission' => null,
    'isPlaceholder' => true
]
```

## Gestion des permissions

### 1. Dans le seeder
Ajouter les permissions dans `database/seeders/PermissionsSeeder.php` :

```php
'monmodule' => [
    'view_monmodule' => 'Voir mon module',
    'manage_monmodule' => 'Gérer mon module',
],
```

### 2. Protection des routes
```php
Route::middleware(['permission:view_monmodule'])->group(function () {
    // Routes protégées
});
```

### 3. Dans les vues
```php
@permission('manage_monmodule')
    <!-- Contenu protégé -->
@endpermission
```

## Configuration avancée

### Module toujours actif (core)
```php
public function isActive(): bool
{
    return true; // Ignore le système de fichiers
}
```

### Ordre personnalisé
- 1-10: Modules système (Core, Dashboard)
- 11-30: Modules utilisateurs  
- 31-50: Modules contenu
- 51-70: Modules outils
- 71-90: Modules reporting
- 91-100: Modules administration

### Icônes
Utiliser FontAwesome 6 : https://fontawesome.com/icons
Format : `fas fa-nom-icone` ou `far fa-nom-icone`

## Bonnes pratiques

1. **Nommage** : Utiliser PascalCase pour les noms de modules
2. **Permissions** : Préfixer par le nom du module (`monmodule_action`)
3. **Routes** : Utiliser le préfixe `personnels.module.`
4. **Ordre** : Laisser de l'espace entre les ordres pour insertions futures
5. **Description** : Être descriptif mais concis
6. **Icônes** : Choisir des icônes cohérentes avec la fonction