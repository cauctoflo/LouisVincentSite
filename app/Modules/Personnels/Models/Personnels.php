<?php

namespace App\Modules\Personnels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Personnels\Models\Role;
use App\Traits\Loggable;

class Personnels extends Model
{
    use HasFactory, Loggable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'is_admin',
        'permissions',
        'description'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'json',
        'is_admin' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Log attributes to be recorded
     *
     * @var array
     */
    protected $logAttributes = [
        'nom',
        'is_admin',
        'permissions',
        'description'
    ];

    /**
     * Custom descriptions for log events
     *
     * @var array
     */
    protected $logDescriptions = [
        'created' => 'Personnel créé',
        'updated' => 'Personnel mis à jour',
        'deleted' => 'Personnel supprimé'
    ];
    
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
        return $this->belongsToMany(Role::class, 'personnel_role');
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