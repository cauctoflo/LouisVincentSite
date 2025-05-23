<?php

namespace App\Modules\Log\Exports;

use App\Modules\Log\Models\Log;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LogsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;
    protected $filename;
    protected $isFileExport = false;
    protected $logEntries = null;

    /**
     * @param Request|string $requestOrFilename
     */
    public function __construct($requestOrFilename)
    {
        if ($requestOrFilename instanceof Request) {
            $this->request = $requestOrFilename;
            $this->isFileExport = false;
        } else {
            $this->filename = $requestOrFilename;
            $this->isFileExport = true;
            $this->parseLogFile();
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->isFileExport) {
            return collect($this->logEntries);
        }

        $query = Log::with(['user', 'actor']);
        
        // Filtrer par type de modèle
        if ($this->request->has('model_type') && !empty($this->request->model_type)) {
            $query->ofType($this->request->model_type);
        }
        
        // Filtrer par utilisateur
        if ($this->request->has('user_id') && $this->request->user_id > 0) {
            $query->byUser($this->request->user_id);
        }
        
        // Filtrer par acteur
        if ($this->request->has('actor_id') && $this->request->actor_id > 0) {
            $query->byActor($this->request->actor_id);
        }
        
        // Filtrer par action
        if ($this->request->has('action') && !empty($this->request->action)) {
            $query->ofAction($this->request->action);
        }
        
        // Filtrer par date
        if ($this->request->has('date_start') && !empty($this->request->date_start)) {
            $query->whereDate('created_at', '>=', $this->request->date_start);
        }
        
        if ($this->request->has('date_end') && !empty($this->request->date_end)) {
            $query->whereDate('created_at', '<=', $this->request->date_end);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        if ($this->isFileExport) {
            return [
                'Date et heure',
                'Niveau',
                'Canal',
                'Message'
            ];
        }

        return [
            'ID',
            'Date et heure',
            'Utilisateur',
            'Acteur',
            'Type de modèle',
            'ID modèle',
            'Action',
            'Détails'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        if ($this->isFileExport) {
            return [
                $row['datetime'] ?? '',
                $row['level'] ?? '',
                $row['channel'] ?? '',
                $row['message'] ?? ''
            ];
        }

        return [
            $row->id,
            $row->created_at->format('d/m/Y H:i:s'),
            $row->user ? $row->user->name : 'N/A',
            $row->actor ? $row->actor->name : 'N/A',
            $row->model_type ?? 'N/A',
            $row->model_id ?? 'N/A',
            $this->getActionLabel($row->action),
            $row->properties ? json_encode($row->properties) : 'N/A'
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Convertit les codes d'action en libellés
     */
    private function getActionLabel($action)
    {
        $labels = [
            'create' => 'Création',
            'update' => 'Modification',
            'delete' => 'Suppression',
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'role_change' => 'Changement de rôle',
            'permission_change' => 'Changement de permission'
        ];

        return $labels[$action] ?? $action;
    }

    /**
     * Parse le fichier de log pour l'export
     */
    private function parseLogFile()
    {
        $filePath = storage_path('logs/' . basename($this->filename));
        
        if (!file_exists($filePath)) {
            $this->logEntries = [];
            return;
        }
        
        $content = file_get_contents($filePath);
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*?)(?=\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]|$)/s';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        $this->logEntries = [];
        foreach ($matches as $match) {
            $this->logEntries[] = [
                'datetime' => $match[1],
                'level' => $match[2],
                'channel' => $match[3],
                'message' => trim($match[4])
            ];
        }
    }
} 