# CLAUDE.md - Informations Projet Louis Vincent Site

## Vue d'ensemble du projet

Il s'agit d'une application web Laravel (version 12.0) appelée "CLIENTXCMS" qui semble être un système de gestion de contenu personnalisé avec des fonctionnalités de logging avancées.

## Stack Technologique

### Backend
- **Framework**: Laravel 12.0 (PHP ^8.0)
- **Base de données**: MySQL
- **Authentification**: Laravel Jetstream 5.3 + Sanctum 4.0
- **Interface utilisateur**: Livewire 3.0
- **Tests**: PHPUnit 11.5.3

### Frontend
- **Build Tool**: Vite 6.0.11
- **CSS Framework**: TailwindCSS 3.4.0 avec plugins (@tailwindcss/forms, @tailwindcss/typography)
- **Editor**: EditorJS 2.30.8 avec plugins (attaches, checklist, code, embed, header, link, list, quote)
- **Icônes**: Heroicons 2.2.0
- **HTTP Client**: Axios 1.8.2

## Configuration de développement

### Variables d'environnement (.env)
```
APP_NAME=CLIENTXCMS
APP_ENV=local
APP_URL=http://localhost
APP_LOCALE=fr
DB_DATABASE=louisvincent
```

### Commandes disponibles

#### PHP/Laravel
```bash
# Démarrage du serveur de développement
php artisan serve

# Migrations de base de données
php artisan migrate

# Génération de clé d'application
php artisan key:generate

# Debug et logs
php artisan pail

# Tests
vendor/bin/phpunit
# ou
php artisan test

# Code styling
vendor/bin/pint

# Cache et optimisations
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Node/Frontend
```bash
# Installation des dépendances
npm install

# Développement
npm run dev

# Build pour production
npm run build
```

#### Commande de développement complète
```bash
# Démarrage simultané du serveur, queue et Vite (définie dans composer.json)
composer run dev
```

## Structure du projet

```
/
├── app/
│   ├── Actions/        # Actions Jetstream
│   ├── Console/        # Commandes Artisan
│   ├── Helpers/        # Fonctions utilitaires
│   ├── Http/           # Controllers, Middleware, Requests
│   ├── Models/         # Modèles Eloquent
│   ├── Modules/        # Modules personnalisés (incluant Log module)
│   ├── Providers/      # Service Providers
│   └── View/           # View Composers
├── resources/          # Vues, assets, lang
├── public/             # Assets publics
├── database/           # Migrations, seeds, factories
├── routes/             # Fichiers de routes
├── config/             # Configuration
├── storage/            # Stockage logs, cache, uploads
└── tests/              # Tests automatisés
```

## Fonctionnalités principales

### Module de Logging
Le projet inclut un module de logging avancé avec les fonctionnalités suivantes :
- Suivi des activités utilisateur
- Logging des changements de modèles
- Interface de gestion des logs (`/personnels/log`)
- Export des logs vers Excel
- Configuration personnalisable

### Authentification
- Laravel Jetstream avec support des équipes
- Authentification API avec Sanctum
- Interface Livewire pour la gestion des utilisateurs

## Tests et Qualité

### Linting et formatage
```bash
# Formatage du code PHP
vendor/bin/pint

# Tests PHP
vendor/bin/phpunit
```

### Debugging
- Laravel Debugbar activé en développement
- Logs quotidiens configurés
- Niveau de debug activé en local

## Base de données
- **Type**: MySQL
- **Host**: 127.0.0.1:3306
- **Base**: louisvincent
- **Username**: root (pas de mot de passe)

## Initialisation du projet

Pour initialiser le projet :

```bash
# 1. Installer les dépendances PHP
composer install

# 2. Installer les dépendances Node.js
npm install

# 3. Copier le fichier d'environnement (déjà fait)
cp .env.example .env

# 4. Générer la clé d'application
php artisan key:generate

# 5. Exécuter les migrations
php artisan migrate

# 6. Démarrer le serveur de développement
composer run dev
# ou séparément :
# php artisan serve
# npm run dev
```

## URLs importantes
- Application: http://localhost:8000 (par défaut avec `php artisan serve`)
- Logs: http://localhost:8000/personnels/log
- Configuration logs: http://localhost:8000/personnels/log/settings

## Notes spéciales
- Locale configurée en français (APP_LOCALE=fr)
- Utilise SQLite pour les tests (database/database.sqlite)
- Support des queues synchrones en développement
- Mail configuré avec Mailpit pour les tests locaux