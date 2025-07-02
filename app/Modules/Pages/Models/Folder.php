<?php

namespace App\Modules\Pages\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Log\Traits\Loggable;

class Folder extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'section_id',
        'parent_id',
        'created_by',
        'order_index',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Log attributes to be recorded
     */
    protected $logAttributes = [
        'name',
        'slug',
        'description',
        'section_id',
        'parent_id',
        'order_index',
        'is_active'
    ];

    /**
     * Custom descriptions for log events
     */
    protected $logDescriptions = [
        'created' => 'Dossier créé',
        'updated' => 'Dossier mis à jour',
        'deleted' => 'Dossier supprimé'
    ];

    /**
     * Relation avec la section
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Relation avec le dossier parent
     */
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    /**
     * Relation avec les sous-dossiers
     */
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id')->orderBy('order_index');
    }

    /**
     * Relation avec les pages
     */
    public function pages()
    {
        return $this->hasMany(Page::class)->orderBy('order_index');
    }

    /**
     * Relation avec l'utilisateur créateur
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope pour les dossiers actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les dossiers racine (sans parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Génère automatiquement le slug à partir du nom
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }

    /**
     * Récupère l'URL publique du dossier
     */
    public function getPublicUrlAttribute()
    {
        return route('pages.folders.show', [
            'section' => $this->section->slug,
            'folder' => $this->slug
        ]);
    }

    /**
     * Récupère le chemin complet du dossier
     */
    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Vérifie si ce dossier est un ancêtre du dossier donné
     */
    public function isAncestorOf(Folder $folder)
    {
        $parent = $folder->parent;
        
        while ($parent) {
            if ($parent->id === $this->id) {
                return true;
            }
            $parent = $parent->parent;
        }
        
        return false;
    }
}
