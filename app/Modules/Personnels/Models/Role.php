<?php

namespace App\Modules\Personnels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Personnels\Models\Permission;

class Role extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'is_default',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Relation avec les utilisateurs
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
    
    /**
     * Vérifie si le rôle a une permission spécifique
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->where('slug', $permission)->exists();
        }
        
        if ($permission instanceof Permission) {
            return $this->permissions()->where('id', $permission->id)->exists();
        }
        
        return false;
    }
    
    
    /**
     * Attribution d'une permission au rôle
     */
    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permissionModel = Permission::where('slug', $permission)->first();
            if ($permissionModel && !$this->hasPermission($permissionModel)) {
                $this->permissions()->attach($permissionModel->id);
            }
        } elseif ($permission instanceof Permission) {
            if (!$this->hasPermission($permission)) {
                $this->permissions()->attach($permission->id);
            }
        }
        
        return $this;
    }
    
    /**
     * Retrait d'une permission du rôle
     */
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permissionModel = Permission::where('slug', $permission)->first();
            if ($permissionModel) {
                $this->permissions()->detach($permissionModel->id);
            }
        } elseif ($permission instanceof Permission) {
            $this->permissions()->detach($permission->id);
        }
        
        return $this;
    }
    
    public function syncPermissions($permissions)
    {
        if (is_array($permissions)) {
            $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id');
            $this->permissions()->sync($permissionIds);
        }
        
        return $this;
    }
    
    public function getAllPermissions()
    {
        if (!$this->relationLoaded('permissions')) {
            $this->load('permissions');
        }
        return $this->permissions ?? collect();
    }
    
    public function getPermissionIds()
    {
        return $this->getAllPermissions()->pluck('id')->toArray();
    }
} 