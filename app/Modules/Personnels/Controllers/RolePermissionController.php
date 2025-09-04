<?php

namespace App\Modules\Personnels\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Personnels\Models\Role;
use App\Modules\Personnels\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        $permissions = Permission::all()->groupBy('module');
        
        return view('personnels.roles-permissions.index', compact('roles', 'permissions'));
    }
    
    public function createRole()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('personnels.roles-permissions.create-role', compact('permissions'));
    }
    
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'array'
        ]);
        
        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_default' => $request->boolean('is_default')
        ]);
        
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }
        
        return redirect()->route('personnels.roles-permissions.index')
            ->with('success', 'Rôle créé avec succès');
    }
    
    public function editRole(Role $role)
    {
        $permissions = Permission::all()->groupBy('module');
        $rolePermissions = $role->getPermissionIds();
        
        return view('personnels.roles-permissions.edit-role', compact('role', 'permissions', 'rolePermissions'));
    }
    
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'array'
        ]);
        
        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_default' => $request->boolean('is_default')
        ]);
        
        $role->permissions()->sync($request->permissions ?? []);
        
        return redirect()->route('personnels.roles-permissions.index')
            ->with('success', 'Rôle mis à jour avec succès');
    }
    
    public function deleteRole(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce rôle car il est assigné à des utilisateurs');
        }
        
        $role->permissions()->detach();
        $role->delete();
        
        return redirect()->route('personnels.roles-permissions.index')
            ->with('success', 'Rôle supprimé avec succès');
    }
    
    public function assignUserRole(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);
        
        $user->roles()->sync($request->roles ?? []);
        
        return back()->with('success', 'Rôles assignés avec succès');
    }
    
    public function assignUserPermission(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);
        
        $user->permissions()->sync($request->permissions ?? []);
        
        return back()->with('success', 'Permissions assignées avec succès');
    }
    
    public function getUserRoles(User $user)
    {
        $userRoles = $user->getRoleIds();
        $userPermissions = $user->getPermissionIds();
        $allRoles = Role::all();
        $allPermissions = Permission::all()->groupBy('module');
        
        return view('personnels.roles-permissions.user-roles', compact('user', 'userRoles', 'userPermissions', 'allRoles', 'allPermissions'));
    }
}