<?php

namespace App\Modules\Log\Services;

use App\Modules\Log\Models\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogService
{
    /**
     * Créer une nouvelle entrée de log
     */
    public function log(string $action, ?Model $model = null, ?array $details = null, ?Model $user = null): Log
    {
        $data = [
            'action' => $action,
            'actor_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'details' => $details
        ];

        if ($model) {
            $data['model_type'] = get_class($model);
            $data['model_id'] = $model->id;
            
            // Si le modèle est un utilisateur et qu'aucun utilisateur n'est spécifié
            if ($model instanceof \App\Models\User && !$user) {
                $data['user_id'] = $model->id;
            }
        }

        if ($user) {
            $data['user_id'] = $user->id;
        }

        return Log::create($data);
    }

    /**
     * Log une création
     */
    public function logCreation(Model $model, ?array $details = null, ?Model $user = null): Log
    {
        return $this->log('create', $model, $details, $user);
    }

    /**
     * Log une mise à jour
     */
    public function logUpdate(Model $model, ?array $details = null, ?Model $user = null): Log
    {
        return $this->log('update', $model, $details, $user);
    }

    /**
     * Log une suppression
     */
    public function logDeletion(Model $model, ?array $details = null, ?Model $user = null): Log
    {
        return $this->log('delete', $model, $details, $user);
    }

    /**
     * Log une connexion
     */
    public function logLogin(Model $user): Log
    {
        return $this->log('login', $user, null, $user);
    }

    /**
     * Log une déconnexion
     */
    public function logLogout(Model $user): Log
    {
        return $this->log('logout', $user, null, $user);
    }

    /**
     * Log un changement de rôle
     */
    public function logRoleChange(Model $user, array $details): Log
    {
        return $this->log('role_change', $user, $details, $user);
    }

    /**
     * Log un changement de permission
     */
    public function logPermissionChange(Model $user, array $details): Log
    {
        return $this->log('permission_change', $user, $details, $user);
    }
} 