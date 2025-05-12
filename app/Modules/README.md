# Système de Modules

Ce système de modules permet d'organiser votre application Laravel en modules indépendants, chacun contenant ses propres contrôleurs, modèles, migrations, vues, etc.

## Structure d'un Module

Chaque module suivra cette structure :

```
app/
└── Modules/
    └── NomDuModule/
        ├── Controllers/      # Contrôleurs du module
        ├── Models/           # Modèles du module
        ├── Migrations/       # Migrations du module
        ├── Views/            # Vues du module
        ├── Routes/           # Routes du module (web.php et api.php)
        ├── Services/         # Services du module
        └── Providers/        # Fournisseurs de services du module
```

## Commandes disponibles

### Création d'un module

Pour créer un nouveau module avec toute la structure nécessaire :

```bash
php artisan module:create NomDuModule
```

Cela va créer tous les dossiers nécessaires et générer un ServiceProvider de base pour le module.

### Création de composants dans un module

#### Créer un modèle

```bash
php artisan module:make-model NomDuModele NomDuModule
```

Options :
- `--migration` ou `-m` : Crée également une migration pour le modèle
- `--controller` ou `-c` : Crée également un contrôleur pour le modèle
- `--resource` ou `-r` : Crée un contrôleur de ressource pour le modèle

Exemple :
```bash
php artisan module:make-model Eleve Internat -m -c
```

#### Créer une migration

```bash
php artisan module:make-migration nom_de_la_migration NomDuModule
```

Options :
- `--create=table_name` : Crée une migration pour créer une nouvelle table
- `--table=table_name` : Crée une migration pour modifier une table existante

Exemple :
```bash
php artisan module:make-migration create_eleves_table Internat --create=eleves
```

#### Créer un contrôleur

```bash
php artisan module:make-controller NomDuController NomDuModule
```

Options :
- `--model=NomDuModele` : Crée un contrôleur de ressource lié à un modèle
- `--resource` ou `-r` : Crée un contrôleur de ressource
- `--api` : Crée un contrôleur d'API

Exemple :
```bash
php artisan module:make-controller EleveController Internat --model=Eleve
```

#### Créer des vues

```bash
php artisan module:make-view nom_de_la_vue NomDuModule
```

Options :
- `--resource` : Crée un ensemble de vues pour une ressource (index, create, edit, show)

Exemple :
```bash
php artisan module:make-view eleve Internat --resource
```

#### Créer un service

```bash
php artisan module:make-service NomDuService NomDuModule
```

Exemple :
```bash
php artisan module:make-service ImportationEleveService Internat
```

### Déplacement de composants existants vers un module

#### Déplacer un contrôleur

```bash
php artisan module:move-controller NomDuControleur NomDuModule
```

#### Déplacer un modèle

```bash
php artisan module:move-model NomDuModele NomDuModule
```

#### Déplacer une migration

```bash
php artisan module:move-migration nom_du_fichier_migration NomDuModule
```

#### Déplacer des vues

```bash
php artisan module:move-view chemin/vers/vue NomDuModule
```

### Suppression d'un module

Pour supprimer un module et ses tables en base de données :

```bash
php artisan module:delete NomDuModule
```

Options :
- `--force` : Force la suppression sans demander de confirmation
- `--keep-tables` : Conserve les tables en base de données

Cette commande :
1. Détecte automatiquement les tables associées au module
2. Supprime les tables de la base de données
3. Supprime les entrées correspondantes dans la table `migrations`
4. Supprime le répertoire du module

Exemple :
```bash
php artisan module:delete Internat
```

## Migrations

Pour exécuter toutes les migrations, y compris celles des modules :

```bash
php artisan migrate
```

## Comment accéder aux vues d'un module

Dans vos contrôleurs, vous pouvez accéder aux vues d'un module comme ceci :

```php
return view('NomDuModule::chemin.vers.vue');
```

## Chargement automatique des modules

Tous les modules sont automatiquement chargés par l'application au démarrage. Chaque module doit contenir un ServiceProvider dans le dossier `Providers` qui est responsable du chargement des routes, vues, migrations, etc. du module. 