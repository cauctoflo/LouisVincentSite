<?php

namespace App\Modules\Personnels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'module',
        'action',
        'description',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
    
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_permissions');
    }
    
    public static function getByModule($module)
    {
        return static::where('module', $module)->get();
    }
    
    public function getDisplayNameAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->action)) . ' - ' . ucfirst($this->module);
    }
}