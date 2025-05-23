<?php

namespace App\Modules\Personnels\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Personnels\Models\Role;
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
        
        return view('Personnels::personnels.index', compact('users'));
    }
    
    /**
     * Afficher le formulaire de création d'un personnel
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = app(PermissionController::class)->getAvailablePermissions();
        
        return view('Personnels::personnels.create', compact('roles', 'permissions'));
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
            'permissions.*' => 'string',
        ]);
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->has('is_admin'),
            'description' => $request->description,
            'permissions' => $request->permissions,
        ];
        
        $user = User::create($userData);
        
        // Attribuer les rôles sélectionnés
        if ($request->has('roles')) {
            $user->roles()->attach($request->roles);
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
                        
        return view('Personnels::personnels.show', compact('personnel', 'logs'));
    }
    
    /**
     * Afficher le formulaire d'édition d'un personnel
     */
    public function edit(User $personnel)
    {
        $roles = Role::all();
        $permissions = app(PermissionController::class)->getAvailablePermissions();
        $userRoleIds = $personnel->roles->pluck('id')->toArray();
        
        // Récupérer les logs récents de l'utilisateur via la relation logs()
        $logs = $personnel->logs()
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
        
        return view('Personnels::personnels.edit', compact('personnel', 'roles', 'permissions', 'userRoleIds', 'logs'));
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
            'permissions.*' => 'string',
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
            'permissions' => $request->permissions,
        ];
        
        // Mettre à jour l'utilisateur
        $personnel->name = $request->name;
        $personnel->email = $request->email;
        $personnel->is_admin = $request->has('is_admin');
        $personnel->description = $request->description;
        $personnel->permissions = $request->permissions;
        
        if ($request->filled('password')) {
            $personnel->password = Hash::make($request->password);
            $newValues['password'] = '[CHANGÉ]'; // Ne pas stocker le mot de passe en clair
        }
        
        $personnel->save();
        
        // Gérer les rôles
        if ($request->has('roles')) {
            $personnel->roles()->sync($request->roles);
        } else {
            $personnel->roles()->detach();
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
 
