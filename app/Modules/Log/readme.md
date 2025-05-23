# Module de Journalisation (Log)

Ce module fournit un système de journalisation centralisé pour l'application Laravel, permettant de suivre toutes les actions importantes effectuées dans le système.

## Fonctionnalités

- Journalisation automatique des opérations CRUD
- Suivi des connexions/déconnexions
- Tracking des changements de rôles et permissions
- Capture des informations de contexte (IP, User-Agent)
- Support pour les actions personnalisées
- Export des logs au format Excel
- Interface d'administration complète

## Installation

1. Le module est automatiquement chargé via le `LogServiceProvider`
2. Assurez-vous que les migrations sont exécutées :
```bash
php artisan migrate
```

## Utilisation

### 1. Ajouter la journalisation à un modèle

```php
use App\Modules\Log\Traits\Loggable;

class VotreModele extends Model
{
    use Loggable;

    // Optionnel : Définir les attributs à journaliser
    protected $logAttributes = [
        'nom',
        'email',
        // ...
    ];

    // Optionnel : Personnaliser les descriptions des logs
    protected $logDescriptions = [
        'created' => 'Nouvel enregistrement créé',
        'updated' => 'Enregistrement mis à jour',
        'deleted' => 'Enregistrement supprimé'
    ];
}
```

### 2. Journalisation automatique

Une fois le trait `Loggable` ajouté, les actions suivantes sont automatiquement journalisées :

- Création d'enregistrement
- Modification d'enregistrement (avec détails des changements)
- Suppression d'enregistrement

### 3. Journalisation manuelle

```php
// Via le service
app(LogService::class)->log('action_personnalisee', $model, ['detail' => 'valeur']);

// Via le modèle
$model->logCustomAction('action_personnalisee', ['detail' => 'valeur']);
```

### 4. Accès aux logs

```php
// Tous les logs d'un modèle
$model->logs;

// Filtrage des logs
Log::ofType('App\Models\User')
   ->ofAction('update')
   ->byUser($userId)
   ->betweenDates($start, $end)
   ->get();
```

## Structure

### Modèle (Log.php)

Champs principaux :
- `user_id` : L'utilisateur concerné
- `actor_id` : L'utilisateur qui effectue l'action
- `action` : Type d'action
- `model_type` et `model_id` : Modèle concerné
- `details` : Détails supplémentaires
- `ip_address` et `user_agent` : Informations de connexion

### Service (LogService.php)

Méthodes disponibles :
- `log()` : Méthode générique
- `logCreation()`
- `logUpdate()`
- `logDeletion()`
- `logLogin()`
- `logLogout()`
- `logRoleChange()`
- `logPermissionChange()`

### Trait (Loggable.php)

Ajoute aux modèles :
- Journalisation automatique
- Relation `logs()`
- Méthode `logCustomAction()`

## Interface d'administration

Accessible via `/logs` avec les fonctionnalités suivantes :

- Liste paginée des logs
- Filtres avancés :
  - Par type de modèle
  - Par action
  - Par utilisateur
  - Par période
- Export Excel
- Vue détaillée des logs
- Gestion des permissions

## Permissions

Les permissions disponibles sont :
- `logs.view` : Voir les logs
- `logs.export` : Exporter les logs
- `logs.delete` : Supprimer des logs
- `logs.clear` : Vider tous les logs

## Exemples d'utilisation

### 1. Suivi des modifications d'un utilisateur

```php
// Dans UserController
public function update(Request $request, User $user)
{
    $oldRoles = $user->roles->pluck('name');
    $user->update($request->validated());
    
    // Le log de mise à jour est automatique via Loggable
    // Pour les rôles, on ajoute un log spécifique
    if ($user->roles->pluck('name') !== $oldRoles) {
        app(LogService::class)->logRoleChange($user, [
            'old' => $oldRoles,
            'new' => $user->roles->pluck('name')
        ]);
    }
}
```

### 2. Action personnalisée

```php
// Dans DocumentController
public function approve(Document $document)
{
    $document->approve();
    $document->logCustomAction('document_approved', [
        'approved_at' => now(),
        'status' => 'approved'
    ]);
}
```

### 3. Filtrage des logs

```php
// Dans LogController
public function getUserActivityLogs(User $user)
{
    return Log::query()
        ->where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('actor_id', $user->id);
        })
        ->with(['user', 'actor'])
        ->latest()
        ->paginate();
}
```

## Maintenance

Pour éviter une croissance excessive de la table des logs :

1. Configurer une politique de rétention :
```php
// Dans LogServiceProvider
$this->app->singleton(LogService::class, function ($app) {
    return new LogService([
        'retention_days' => 90 // Garder 90 jours de logs
    ]);
});
```

2. Programmer un nettoyage régulier :
```bash
# Dans le Kernel
protected $schedule = [
    // Nettoyer les vieux logs chaque semaine
    Schedule::command('logs:clean')->weekly(),
];
```

## Contribution

Pour contribuer au module :

1. Créez une branche pour votre fonctionnalité
2. Ajoutez des tests pour les nouvelles fonctionnalités
3. Soumettez une pull request

## Support

Pour toute question ou problème :
- Consultez la documentation
- Ouvrez une issue sur le dépôt
- Contactez l'équipe de développement
