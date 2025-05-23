<?php

namespace App\Modules\Personnels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'permissions',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Relation avec les utilisateurs
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
    
    /**
     * Vérifie si le rôle a une permission spécifique
     */
    public function hasPermission($permission)
    {
        if (is_null($this->permissions)) {
            return false;
        }
        
        return in_array($permission, $this->permissions);
    }
    
    /**
     * Attribution d'une permission au rôle
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
     * Retrait d'une permission du rôle
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
} 