<?php

namespace App\Modules\Pages\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Log\Traits\Loggable;

class Section extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'created_by',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
        'is_active'
    ];

    /**
     * Custom descriptions for log events
     */
    protected $logDescriptions = [
        'created' => 'Section créée',
        'updated' => 'Section mise à jour',
        'deleted' => 'Section supprimée'
    ];

    /**
     * Relation avec l'utilisateur créateur
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec les dossiers
     */
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * Relation avec les pages directes (sans dossier)
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    /**
     * Relation avec les pages directes (sans dossier)
     */
    public function directPages()
    {
        return $this->hasMany(Page::class)->whereNull('folder_id');
    }

    /**
     * Relation avec les utilisateurs responsables
     */
    public function responsibles()
    {
        return $this->belongsToMany(User::class, 'section_responsibles', 'section_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Scope pour les sections actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
     * Récupère l'URL publique de la section
     */
    public function getPublicUrlAttribute()
    {
        return route('pages.sections.show', $this->slug);
    }

    /**
     * Vérifie si un utilisateur est responsable de cette section
     */
    public function isResponsible(User $user)
    {
        return $this->responsibles->contains($user) || $user->is_admin;
    }
}
