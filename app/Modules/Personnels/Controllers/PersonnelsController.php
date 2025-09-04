<?php

namespace App\Modules\Personnels\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Personnels\Models\Role;
use App\Modules\Personnels\Models\Permission;
use App\Modules\Personnels\Services\UserLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PersonnelsController extends Controller
{
    /**
     * Service de journalisation des utilisateurs
     *
     * @var UserLogService
     */
    protected $userLogService;

    /**
     * Constructeur
     *
     * @param UserLogService $userLogService
     */
    public function __construct(UserLogService $userLogService)
    {
        $this->userLogService = $userLogService;
    }

    /**
     * Afficher la liste des personnels
     */
    public function index()
    {
        $users = User::all();
        
        return view('personnels.personnels.index', compact('users'));
    }
    
    /**
     * Afficher le formulaire de création d'un personnel
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy('module');
        
        return view('personnels.personnels.create', compact('roles', 'permissions'));
    }
    
    /**
     * Stocker un nouveau personnel
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean|nullable',
            'description' => 'nullable|string',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->has('is_admin'),
            'description' => $request->description,
        ];
        
        $user = User::create($userData);
        
        // Attribuer les rôles sélectionnés
        if ($request->has('roles')) {
            $user->roles()->attach($request->roles);
        }
        
        // Attribuer les permissions directes
        if ($request->has('permissions')) {
            $user->permissions()->attach($request->permissions);
        }
        
        // Journaliser la création de l'utilisateur
        $this->userLogService->logUserCreation($user, $userData);
        
        return redirect()->route('personnels.personnels.index')
            ->with('success', 'Personnel créé avec succès');
    }
    
    /**
     * Afficher un personnel spécifique
     */
    public function show(User $personnel)
    {
        // Récupérer les logs de l'utilisateur via la relation logs()

        $logs = $personnel->logs()
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
        // dd($logs);
                        
        return view('personnels.personnels.show', compact('personnel', 'logs'));
    }
    
    /**
     * Afficher le formulaire d'édition d'un personnel
     */
    public function edit(User $personnel)
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy('module');
        $userRoleIds = $personnel->getRoleIds();
        $userPermissionIds = $personnel->getPermissionIds();
        
        // Récupérer les logs récents de l'utilisateur via la relation logs()
        $logs = $personnel->logs()
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
        
        return view('personnels.personnels.edit', compact('personnel', 'roles', 'permissions', 'userRoleIds', 'userPermissionIds', 'logs'));
    }
    
    /**
     * Mettre à jour un personnel
     */
    public function update(Request $request, User $personnel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $personnel->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean|nullable',
            'description' => 'nullable|string',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        // Sauvegarder les anciennes valeurs avant la mise à jour
        $oldValues = $personnel->toArray();
        $oldRoles = $personnel->roles->pluck('id')->toArray();
        
        // Préparer les nouvelles valeurs
        $newValues = [
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin'),
            'description' => $request->description,
        ];
        
        // Mettre à jour l'utilisateur
        $personnel->name = $request->name;
        $personnel->email = $request->email;
        
        // Empêcher la modification du statut admin par soi-même
        if (auth()->id() !== $personnel->id) {
            $personnel->is_admin = $request->has('is_admin');
        }
        
        $personnel->description = $request->description;
        
        if ($request->filled('password')) {
            $personnel->password = Hash::make($request->password);
            $newValues['password'] = '[CHANGÉ]'; 
        }
        
        $personnel->save();
        
        // Gérer les rôles (sauf si l'utilisateur modifie ses propres rôles ou si c'est un admin)
        if (auth()->id() !== $personnel->id) {
            if ($request->has('roles')) {
                $personnel->roles()->sync($request->roles);
            } else {
                $personnel->roles()->detach();
            }
            
            // Gérer les permissions directes (sauf si l'utilisateur modifie ses propres permissions ou si c'est un admin)
            if (!$personnel->is_admin) {
                if ($request->has('permissions')) {
                    $personnel->permissions()->sync($request->permissions);
                } else {
                    $personnel->permissions()->detach();
                }
            }
        }
        
        // Journaliser la mise à jour de l'utilisateur
        $this->userLogService->logUserUpdate($personnel, $oldValues, $newValues);
        
        // Si les rôles ont changé, les journaliser également
        $newRoles = $request->roles ?? [];
        if ($oldRoles != $newRoles) {
            $this->userLogService->logRoleChange(
                $personnel, 
                Role::whereIn('id', $oldRoles)->pluck('name')->toArray(),
                Role::whereIn('id', $newRoles)->pluck('name')->toArray()
            );
        }
        
        return redirect()->route('personnels.personnels.index')
            ->with('success', 'Personnel mis à jour avec succès');
    }
    
    /**
     * Supprimer un personnel
     */
    public function destroy(User $personnel)
    {
        // Journaliser la suppression de l'utilisateur
        $this->userLogService->logUserDeletion($personnel);
        
        $personnel->delete();
        
        return redirect()->route('personnels.personnels.index')
            ->with('success', 'Personnel supprimé avec succès');
    }
}
 
