<?php

namespace App\Modules\Pages\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PageRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'title',
        'content',
        'excerpt',
        'created_by',
        'revision_type',
        'changes_summary'
    ];

    protected $casts = [
        'content' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relation avec la page
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Relation avec l'utilisateur créateur de la révision
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Types de révision
     */
    const REVISION_TYPES = [
        'auto_save' => 'Sauvegarde automatique',
        'manual' => 'Sauvegarde manuelle',
        'publish' => 'Publication',
        'restore' => 'Restauration'
    ];

    /**
     * Récupère le libellé du type de révision
     */
    public function getRevisionTypeLabel()
    {
        return self::REVISION_TYPES[$this->revision_type] ?? 'Inconnue';
    }
}
