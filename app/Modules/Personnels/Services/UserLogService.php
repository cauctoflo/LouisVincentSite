<?php

namespace App\Modules\Personnels\Services;

use App\Models\User;
use App\Modules\Log\Models\Log;
use Illuminate\Support\Facades\Auth;

class UserLogService
{
    /**
     * Champs à exclure du log car ils ne sont pas modifiables directement ou non pertinents
     */
    protected $excludedFields = [
        'id', 
        'created_at', 
        'updated_at', 
        'remember_token', 
        'password_reset_token',
        'password',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'profile_photo_url',
        'email_verified_at'
    ];

    /**
     * Enregistre un log lors de la création d'un utilisateur
     *
     * @param User $user L'utilisateur créé
     * @param array $data Les données de l'utilisateur
     * @return Log
     */
    public function logUserCreation(User $user, array $data): Log
    {
        return $this->createLog($user, 'create', 'Création du compte utilisateur', null, $this->filterSensitiveData($data));
    }

    /**
     * Enregistre un log lors de la mise à jour d'un utilisateur
     *
     * @param User $user L'utilisateur modifié
     * @param array $oldData Les anciennes données de l'utilisateur
     * @param array $newData Les nouvelles données de l'utilisateur
     * @return Log|null
     */
    public function logUserUpdate(User $user, array $oldData, array $newData): ?Log
    {
        // Filtrer les données sensibles
        $oldDataFiltered = $this->filterSensitiveData($oldData);
        $newDataFiltered = $this->filterSensitiveData($newData);
        
        // Déterminer quels champs ont changé
        $changedFields = $this->getChangedFields($oldDataFiltered, $newDataFiltered);
        
        if (empty($changedFields)) {
            // Aucun changement significatif, on ne crée pas de log
            return null;
        }
        
        // Créer un tableau d'anciennes valeurs avec seulement les champs modifiés
        $oldValuesToLog = array_intersect_key($oldDataFiltered, $changedFields);
        // Créer un tableau de nouvelles valeurs avec seulement les champs modifiés
        $newValuesToLog = array_intersect_key($newDataFiltered, $changedFields);
        
        return $this->createLog(
            $user,
            'update',
            'Mise à jour du compte utilisateur',
            $oldValuesToLog,
            $newValuesToLog
        );
    }

    /**
     * Enregistre un log lors de la suppression d'un utilisateur
     *
     * @param User $user L'utilisateur supprimé
     * @return Log
     */
    public function logUserDeletion(User $user): Log
    {
        $userData = $user->toArray();
        
        return $this->createLog(
            $user,
            'delete',
            'Suppression du compte utilisateur',
            $this->filterSensitiveData($userData),
            null
        );
    }
    
    /**
     * Enregistre un log lors de la modification des rôles d'un utilisateur
     *
     * @param User $user L'utilisateur dont les rôles ont été modifiés
     * @param array $oldRoles Les anciens rôles
     * @param array $newRoles Les nouveaux rôles
     * @return Log|null
     */
    public function logRoleChange(User $user, array $oldRoles, array $newRoles): ?Log
    {
        // Vérifier si les rôles ont vraiment changé
        if ($this->arraysAreEqual($oldRoles, $newRoles)) {
            return null;
        }
        
        return $this->createLog(
            $user,
            'role_change',
            'Modification des rôles de l\'utilisateur',
            ['roles' => $oldRoles],
            ['roles' => $newRoles]
        );
    }
    
    /**
     * Enregistre un log lors de la modification des permissions d'un utilisateur
     *
     * @param User $user L'utilisateur dont les permissions ont été modifiées
     * @param array $oldPermissions Les anciennes permissions
     * @param array $newPermissions Les nouvelles permissions
     * @return Log|null
     */
    public function logPermissionChange(User $user, array $oldPermissions, array $newPermissions): ?Log
    {
        // Vérifier si les permissions ont vraiment changé
        if ($this->arraysAreEqual($oldPermissions, $newPermissions)) {
            return null;
        }
        
        return $this->createLog(
            $user,
            'permission_change',
            'Modification des permissions de l\'utilisateur',
            ['permissions' => $oldPermissions],
            ['permissions' => $newPermissions]
        );
    }
    
    /**
     * Crée une entrée de log
     *
     * @param User $user L'utilisateur concerné
     * @param string $action L'action effectuée
     * @param string $description Description de l'action
     * @param array|null $oldValues Les anciennes valeurs
     * @param array|null $newValues Les nouvelles valeurs
     * @return Log|null
     */
    private function createLog(User $user, string $action, string $description, ?array $oldValues, ?array $newValues): ?Log
    {
        // Si aucune donnée à journaliser, on ne crée pas de log
        if (empty($oldValues) && empty($newValues)) {
            return null;
        }
        
        return Log::create([
            'user_id' => $user->id,
            'actor_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'details' => [
                'old' => $oldValues,
                'new' => $newValues
            ],
            'model_type' => get_class($user),
            'model_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
    
    /**
     * Filtre les données sensibles des tableaux utilisateur
     *
     * @param array $data Les données à filtrer
     * @return array
     */
    private function filterSensitiveData(array $data): array
    {
        // Filtrer les champs exclus
        foreach ($this->excludedFields as $field) {
            if (isset($data[$field])) {
                unset($data[$field]);
            }
        }
        
        return $data;
    }
    
    /**
     * Détermine quels champs ont changé entre l'ancien et le nouveau tableau
     *
     * @param array $oldData Anciennes données
     * @param array $newData Nouvelles données
     * @return array Un tableau associatif des champs modifiés [champ => true]
     */
    private function getChangedFields(array $oldData, array $newData): array
    {
        $changedFields = [];
        
        // Parcourir toutes les clés de newData
        foreach ($newData as $key => $value) {
            // Vérifier si la valeur existe dans oldData et est différente
            if (!isset($oldData[$key]) || $oldData[$key] !== $value) {
                $changedFields[$key] = true;
            }
        }
        
        // Vérifier également les clés qui existent dans oldData mais pas dans newData
        foreach ($oldData as $key => $value) {
            if (!isset($newData[$key])) {
                $changedFields[$key] = true;
            }
        }
        
        return $changedFields;
    }
    
    /**
     * Compare deux tableaux pour déterminer s'ils sont égaux
     * 
     * @param array $array1
     * @param array $array2
     * @return bool
     */
    private function arraysAreEqual(array $array1, array $array2): bool
    {
        // Tri des tableaux pour les comparer indépendamment de l'ordre
        sort($array1);
        sort($array2);
        
        return $array1 === $array2;
    }
} 