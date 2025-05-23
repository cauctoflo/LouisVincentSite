<?php

namespace App\Modules\Personnels\Exports;

use App\Modules\Personnels\Models\UserLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class UserLogsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = UserLog::with(['user', 'actor']);
        
        // Filtrer par utilisateur si demandé
        if ($this->request->has('user_id') && $this->request->user_id > 0) {
            $query->where('user_id', $this->request->user_id);
        }
        
        // Filtrer par action si demandé
        if ($this->request->has('action') && !empty($this->request->action)) {
            $query->where('action', $this->request->action);
        }
        
        // Filtrer par date si demandé
        if ($this->request->has('date_start') && !empty($this->request->date_start)) {
            $query->whereDate('created_at', '>=', $this->request->date_start);
        }
        
        if ($this->request->has('date_end') && !empty($this->request->date_end)) {
            $query->whereDate('created_at', '<=', $this->request->date_end);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Date',
            'Utilisateur',
            'Action',
            'Par',
            'Détails'
        ];
    }

    public function map($log): array
    {
        $actions = [
            'create' => 'Création',
            'update' => 'Modification',
            'delete' => 'Suppression',
            'role_change' => 'Changement de rôle',
            'permission_change' => 'Changement de permission'
        ];

        return [
            $log->created_at->format('d/m/Y H:i'),
            $log->user->name ?? 'Utilisateur supprimé',
            $actions[$log->action] ?? ucfirst($log->action),
            $log->actor->name ?? 'Système',
            json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        ];
    }
} 