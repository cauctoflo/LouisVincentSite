<?php

namespace App\Modules\Log\Traits;

use App\Modules\Log\Services\LogService;
use Illuminate\Database\Eloquent\Model;

trait Loggable
{
    protected static function bootLoggable()
    {
        static::created(function (Model $model) {
            app(LogService::class)->logCreation($model);
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']); // Ignorer la mise à jour du timestamp
            
            if (!empty($changes)) {
                app(LogService::class)->logUpdate($model, [
                    'changes' => $changes,
                    'original' => array_intersect_key($model->getOriginal(), $changes)
                ]);
            }
        });

        static::deleted(function (Model $model) {
            app(LogService::class)->logDeletion($model, [
                'attributes' => $model->getAttributes()
            ]);
        });
    }

    /**
     * Relation morphMany vers les logs
     */
    public function logs()
    {
        return $this->morphMany('App\Modules\Log\Models\Log', 'model');
    }

    /**
     * Log une action personnalisée
     */
    public function logCustomAction(string $action, array $details = null)
    {
        return app(LogService::class)->log($action, $this, $details);
    }
} 