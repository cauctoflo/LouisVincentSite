<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Modules\Personnels\Models\Role;
use App\Modules\Personnels\Models\Permission;
use App\Modules\Log\Traits\Loggable;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
    
    /**
     * Vérifie si l'utilisateur a une permission spécifique
     */
    public function hasPermission($permission)
    {
        if ($this->is_admin) {
            return true;
        }
        
        if (is_string($permission)) {
            return $this->permissions()->where('slug', $permission)->exists();
        }
        
        if ($permission instanceof Permission) {
            return $this->permissions()->where('id', $permission->id)->exists();
        }
        
        return false;
    }
    
    /**
     * Attribution d'une permission à l'utilisateur
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
     * Retrait d'une permission de l'utilisateur
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
    
    /**
     * Relation avec les rôles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }
    
    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     */
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }
    
    /**
     * Vérifie si l'utilisateur a l'accès via une permission ou un rôle
     */
    public function hasAccess($permission)
    {
        if ($this->is_admin) {
            return true;
        }
        
        if ($this->hasPermission($permission)) {
            return true;
        }
        
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function giveRole($role)
    {
        if (is_string($role)) {
            $roleModel = Role::where('name', $role)->first();
            if ($roleModel && !$this->hasRole($roleModel->name)) {
                $this->roles()->attach($roleModel->id);
            }
        } elseif ($role instanceof Role) {
            if (!$this->hasRole($role->name)) {
                $this->roles()->attach($role->id);
            }
        }
        
        return $this;
    }
    
    public function removeRole($role)
    {
        if (is_string($role)) {
            $roleModel = Role::where('name', $role)->first();
            if ($roleModel) {
                $this->roles()->detach($roleModel->id);
            }
        } elseif ($role instanceof Role) {
            $this->roles()->detach($role->id);
        }
        
        return $this;
    }
    
    public function getAllPermissions()
    {
        if (!$this->relationLoaded('permissions')) {
            $this->load('permissions');
        }
        if (!$this->relationLoaded('roles')) {
            $this->load('roles.permissions');
        }
        
        $directPermissions = $this->permissions ?? collect();
        $rolePermissions = $this->roles->flatMap(function($role) {
            return $role->getAllPermissions();
        });
        
        return $directPermissions->merge($rolePermissions)->unique('id');
    }
    
    public function getRoleIds()
    {
        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }
        return $this->roles ? $this->roles->pluck('id')->toArray() : [];
    }
    
    public function getPermissionIds()
    {
        if (!$this->relationLoaded('permissions')) {
            $this->load('permissions');
        }
        return $this->permissions ? $this->permissions->pluck('id')->toArray() : [];
    }
}
