<?php

namespace App\Modules\Log\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'actor_id',
        'action',
        'model_type',
        'model_id',
        'details',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * L'utilisateur qui a effectué l'action
     */
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * L'utilisateur concerné par l'action (si applicable)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Le modèle concerné par l'action
     */
    public function loggable()
    {
        return $this->morphTo('model');
    }

    /**
     * Scope pour filtrer par type de modèle
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('model_type', $type);
    }

    /**
     * Scope pour filtrer par action
     */
    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par utilisateur
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour filtrer par acteur
     */
    public function scopeByActor($query, $actorId)
    {
        return $query->where('actor_id', $actorId);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }
} 