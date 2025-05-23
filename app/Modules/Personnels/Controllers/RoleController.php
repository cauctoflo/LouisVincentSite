<?php

namespace App\Modules\Personnels\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Personnels\Models\Role;
use App\Models\User;
use App\Modules\Log\Services\LogService;
use Illuminate\Http\Request;

class RoleController extends Controller
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
     * Afficher la liste des rôles
     */
    public function index()
    {
        $roles = Role::all();
        
        return view('Personnels::roles.index', compact('roles'));
    }
    
    /**
     * Afficher le formulaire de création de rôle
     */
    public function create()
    {
        return view('Personnels::roles.create');
    }
    
    /**
     * Stocker un nouveau rôle
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);
        
        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions,
        ]);

        // Log la création du rôle
        $this->logService->log('role_create', $role, [
            'message' => "Création du rôle {$role->name}",
            'description' => "Nouveau rôle créé avec les caractéristiques suivantes",
            'details' => [
                'nom' => $role->name,
                'description' => $role->description ?: 'Non définie',
                'permissions_attribuées' => $role->permissions ? array_map(function($perm) {
                    return config('permissions.'.$perm, $perm);
                }, $role->permissions) : ['Aucune permission']
            ]
        ]);
        
        return redirect()->route('personnels.roles.index')
            ->with('success', 'Rôle créé avec succès');
    }
    

    public function show(Role $role)
    {
        return redirect()->route('personnels.roles.edit', $role);
    }
    
    /**
     * Afficher le formulaire d'édition d'un rôle
     */
    public function edit(Role $role)
    {
        return view('Personnels::roles.edit', compact('role'));
    }
    
    /**
     * Mettre à jour un rôle
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);
        
        $oldData = [
            'nom' => $role->name,
            'description' => $role->description ?: 'Non définie',
            'permissions' => $role->permissions ? array_map(function($perm) {
                return config('permissions.'.$perm, $perm);
            }, $role->permissions) : ['Aucune permission']
        ];
        
        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions,
        ]);

        $newData = [
            'nom' => $role->name,
            'description' => $role->description ?: 'Non définie',
            'permissions' => $role->permissions ? array_map(function($perm) {
                return config('permissions.'.$perm, $perm);
            }, $role->permissions) : ['Aucune permission']
        ];

        // Log la mise à jour du rôle
        $this->logService->log('role_update', $role, [
            'message' => "Modification du rôle {$oldData['nom']}" . ($oldData['nom'] !== $newData['nom'] ? " en {$newData['nom']}" : ""),
            'description' => "Les caractéristiques du rôle ont été modifiées",
            'modifications' => [
                'nom' => [
                    'avant' => $oldData['nom'],
                    'après' => $newData['nom']
                ],
                'description' => [
                    'avant' => $oldData['description'],
                    'après' => $newData['description']
                ],
                'permissions' => [
                    'avant' => $oldData['permissions'],
                    'après' => $newData['permissions'],
                    'ajoutées' => array_values(array_diff($newData['permissions'], $oldData['permissions'])),
                    'retirées' => array_values(array_diff($oldData['permissions'], $newData['permissions']))
                ]
            ],
            'technical_details' => [
                'role_id' => $role->id,
                'old_data' => $oldData,
                'new_data' => $newData
            ]
        ]);
        
        return redirect()->route('personnels.roles.index')
            ->with('success', 'Rôle mis à jour avec succès');
    }
    
    /**
     * Supprimer un rôle
     */
    public function destroy(Role $role)
    {
        // Récupérer la liste des utilisateurs qui avaient ce rôle
        $usersWithRole = $role->users()->get(['id', 'name', 'email'])->map(function($user) {
            return [
                'nom' => $user->name,
                'email' => $user->email
            ];
        })->toArray();

        // Log la suppression du rôle
        $this->logService->log('role_delete', $role, [
            'message' => "Suppression du rôle {$role->name}",
            'description' => "Le rôle a été supprimé du système",
            'details' => [
                'nom_du_role' => $role->name,
                'description' => $role->description ?: 'Non définie',
                'permissions_associées' => $role->permissions ? array_map(function($perm) {
                    return config('permissions.'.$perm, $perm);
                }, $role->permissions) : ['Aucune permission'],
                'utilisateurs_impactés' => $usersWithRole ?: ['Aucun utilisateur n\'avait ce rôle']
            ]
        ]);

        $role->delete();
        
        return redirect()->route('personnels.roles.index')
            ->with('success', 'Rôle supprimé avec succès');
    }
    
    /**
     * Afficher le formulaire d'attribution des rôles aux utilisateurs
     */
    public function assignForm()
    {
        $roles = Role::all();
        $users = User::all();
        
        return view('Personnels::roles.assign', compact('roles', 'users'));
    }
    
    /**
     * Attribuer un rôle à un utilisateur
     */
    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);
        
        // Récupérer les rôles actuels avant modification
        $oldRoles = $user->roles->pluck('name')->toArray();
        
        // Ajouter le nouveau rôle
        $user->roles()->syncWithoutDetaching([$role->id]);
        
        // Récupérer les rôles après modification
        $user->refresh();
        $newRoles = $user->roles->pluck('name')->toArray();
        
        // Log l'attribution du rôle
        $this->logService->log('role_assign', $user, [
            'message' => "Attribution du rôle {$role->name} à l'utilisateur {$user->name}",
            'description' => "Un nouveau rôle a été attribué à l'utilisateur",
            'details' => [
                'utilisateur' => [
                    'nom' => $user->name,
                    'email' => $user->email
                ],
                'role_attribué' => [
                    'nom' => $role->name,
                    'description' => $role->description ?: 'Non définie'
                ],
                'roles' => [
                    'avant' => $oldRoles ?: ['Aucun rôle'],
                    'après' => $newRoles,
                    'ajouté' => $role->name
                ]
            ]
        ]);
        
        return redirect()->back()
            ->with('success', 'Rôle attribué avec succès');
    }
    
    /**
     * Retirer un rôle d'un utilisateur
     */
    public function removeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);
        
        // Récupérer les rôles actuels avant modification
        $oldRoles = $user->roles->pluck('name')->toArray();
        
        // Retirer le rôle
        $user->roles()->detach($request->role_id);
        
        // Récupérer les rôles après modification
        $user->refresh();
        $newRoles = $user->roles->pluck('name')->toArray();
        
        // Log le retrait du rôle
        $this->logService->log('role_remove', $user, [
            'message' => "Retrait du rôle {$role->name} de l'utilisateur {$user->name}",
            'description' => "Un rôle a été retiré de l'utilisateur",
            'details' => [
                'utilisateur' => [
                    'nom' => $user->name,
                    'email' => $user->email
                ],
                'role_retiré' => [
                    'nom' => $role->name,
                    'description' => $role->description ?: 'Non définie'
                ],
                'roles' => [
                    'avant' => $oldRoles,
                    'après' => $newRoles ?: ['Aucun rôle'],
                    'retiré' => $role->name
                ]
            ]
        ]);
        
        return redirect()->back()
            ->with('success', 'Rôle retiré avec succès');
    }
} 