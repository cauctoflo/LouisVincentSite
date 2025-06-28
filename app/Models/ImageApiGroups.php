<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageApiGroups extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'module_imageapi_groups';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'sort',
        'icon',
        'color',
        'is_active',
        'max_size',
        'allowed_types',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sort' => 'integer',
        'is_active' => 'boolean',
        'max_size' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getStats()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'sort' => $this->sort,
            'icon' => $this->icon,
            'color' => $this->color,
            'is_active' => $this->is_active,
            'max_size' => $this->max_size,
            'allowed_types' => $this->allowed_types,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}