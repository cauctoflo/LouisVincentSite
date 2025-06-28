<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageApi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'module_imageapi';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Name',
        'path',
        'alt_text',
        'tags',
        'token',
        'description',
        'status',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getStats() {
        return [
            'name' => $this->Name,
            'path' => $this->path,
            'alt_text' => $this->alt_text,
            'tags' => $this->tags,
            'token' => $this->token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}