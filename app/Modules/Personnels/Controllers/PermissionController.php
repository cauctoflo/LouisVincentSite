<?php

namespace App\Modules\Personnels\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Log\Services\LogService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Service de journalisation
     *
     * @var LogService
     */
    protected $logService;

    /**
     * Constructeur
     *
     * @param LogService $logService
     */
    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
    
    /**
     * Liste des permissions disponibles dans le système
     */
    private $availablePermissions = [
        'dashboard.view' => 'Voir le tableau de bord',
        'users.view' => 'Voir les utilisateurs',
        'users.create' => 'Créer des utilisateurs',
        'users.edit' => 'Modifier des utilisateurs',
        'users.delete' => 'Supprimer des utilisateurs',
        'roles.view' => 'Voir les rôles',
        'roles.create' => 'Créer des rôles',
        'roles.edit' => 'Modifier des rôles',
        'roles.delete' => 'Supprimer des rôles',
        'permissions.manage' => 'Gérer les permissions',
        
        // Permissions du module Personnel
        'personnel.view' => 'Voir les personnels',
        'personnel.create' => 'Créer des personnels',
        'personnel.edit' => 'Modifier des personnels',
        'personnel.delete' => 'Supprimer des personnels',
        
        // Permissions plus granulaires pour les rôles
        'roles.assign' => 'Attribuer des rôles aux utilisateurs',
        'roles.view_only' => 'Voir les rôles sans pouvoir les modifier',
        
        // Permissions spécifiques pour les vues
        'views.personnel' => 'Accéder aux vues du module Personnel',
        'views.roles' => 'Accéder aux vues de gestion des rôles',
        'views.permissions' => 'Accéder aux vues de gestion des permissions',
        
        // Autres permissions utiles
        'personnel.view_profile' => 'Voir les profils des personnels',
        'personnel.view_self' => 'Voir uniquement son propre profil',
        'personnel.edit_self' => 'Modifier uniquement son propre profil',
        
        // Permissions pour les logs
        'logs.view' => 'Voir tous les journaux d\'activité',
        'logs.view_user' => 'Voir les journaux d\'activité d\'un utilisateur spécifique',
        'logs.export' => 'Exporter les journaux d\'activité',
        'logs.delete' => 'Supprimer les journaux d\'activité',
        
        // Ajoutez d'autres permissions selon vos besoins
    ];
    
    /**
     * Afficher la liste des utilisateurs pour gérer leurs permissions
     */
    public function index()
    {
        $users = User::all();
        
        return view('Personnels::permissions.index', compact('users'));
    }
    
    /**
     * Afficher le formulaire de gestion des permissions d'un utilisateur
     */
    public function edit(User $user)
    {
        $availablePermissions = $this->availablePermissions;
        
        return view('Personnels::permissions.edit', compact('user', 'availablePermissions'));
    }
    
    /**
     * Mettre à jour les permissions d'un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);
        
        // Sauvegarder les anciennes permissions
        $oldPermissions = $user->permissions ?? [];
        
        // Mettre à jour les permissions
        $newPermissions = $request->permissions ?? [];
        $user->update([
            'permissions' => $newPermissions,
        ]);

        // Préparer les listes de permissions pour l'affichage
        $oldPermissionsList = array_map(function($perm) {
            return config('permissions.'.$perm, $perm);
        }, $oldPermissions);

        $newPermissionsList = array_map(function($perm) {
            return config('permissions.'.$perm, $perm);
        }, $newPermissions);

        $addedPermissions = array_values(array_diff($newPermissions, $oldPermissions));
        $removedPermissions = array_values(array_diff($oldPermissions, $newPermissions));

        $addedPermissionsList = array_map(function($perm) {
            return config('permissions.'.$perm, $perm);
        }, $addedPermissions);

        $removedPermissionsList = array_map(function($perm) {
            return config('permissions.'.$perm, $perm);
        }, $removedPermissions);
        
        // Log la modification des permissions
        $this->logService->log('permissions_update', $user, [
            'message' => "Modification des permissions de l'utilisateur {$user->name}",
            'description' => "Les permissions de l'utilisateur ont été mises à jour",
            'details' => [
                'utilisateur' => [
                    'nom' => $user->name,
                    'email' => $user->email
                ],
                'modifications' => [
                    'permissions_avant' => $oldPermissionsList ?: ['Aucune permission'],
                    'permissions_après' => $newPermissionsList ?: ['Aucune permission'],
                    'permissions_ajoutées' => $addedPermissionsList ?: ['Aucune permission ajoutée'],
                    'permissions_retirées' => $removedPermissionsList ?: ['Aucune permission retirée']
                ],
                'résumé_des_changements' => [
                    'nombre_avant' => count($oldPermissions),
                    'nombre_après' => count($newPermissions),
                    'nombre_ajoutées' => count($addedPermissions),
                    'nombre_retirées' => count($removedPermissions)
                ]
            ],
            'technical_details' => [
                'user_id' => $user->id,
                'old_permissions' => $oldPermissions,
                'new_permissions' => $newPermissions,
                'added_raw' => $addedPermissions,
                'removed_raw' => $removedPermissions
            ]
        ]);
        
        return redirect()->route('personnels.permissions.index')
            ->with('success', 'Permissions mises à jour avec succès');
    }
    
    /**
     * Obtenir la liste des permissions disponibles
     */
    public function getAvailablePermissions()
    {
        return $this->availablePermissions;
    }
} 