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
        'permissions',
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
            'permissions' => 'json',
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
        
        if (is_null($this->permissions)) {
            return false;
        }
        
        return in_array($permission, $this->permissions);
    }
    
    /**
     * Attribution d'une permission à l'utilisateur
     */
    public function givePermission($permission)
    {
        $permissions = $this->permissions ?? [];
        
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
            $this->save();
        }
        
        return $this;
    }
    
    /**
     * Retrait d'une permission de l'utilisateur
     */
    public function removePermission($permission)
    {
        if (is_null($this->permissions)) {
            return $this;
        }
        
        $permissions = array_filter($this->permissions, function($p) use ($permission) {
            return $p !== $permission;
        });
        
        $this->permissions = $permissions;
        $this->save();
        
        return $this;
    }
    
    /**
     * Relation avec les rôles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
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
}
