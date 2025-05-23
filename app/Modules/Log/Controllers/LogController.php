<?php

namespace App\Modules\Log\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Log\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Modules\Log\Exports\LogsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log as LogFacade;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{
    /**
     * Afficher la liste des logs
     */
    public function index(Request $request)
    {
        // Gestion des logs système (BD)
        $query = Log::with(['user', 'actor']);
        
        // Filtrer par type de modèle
        if ($request->has('model_type') && !empty($request->model_type)) {
            $query->ofType($request->model_type);
        }
        
        // Filtrer par utilisateur
        if ($request->has('user_id') && $request->user_id > 0) {
            $query->byUser($request->user_id);
        }
        
        // Filtrer par acteur
        if ($request->has('actor_id') && $request->actor_id > 0) {
            $query->byActor($request->actor_id);
        }
        
        // Filtrer par action
        if ($request->has('action') && !empty($request->action)) {
            $query->ofAction($request->action);
        }
        
        // Filtrer par date
        if ($request->has('date_start') && !empty($request->date_start)) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        
        if ($request->has('date_end') && !empty($request->date_end)) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::orderBy('name')->get();
        $modelTypes = Log::distinct()->pluck('model_type');
        $actions = [
            'create' => 'Création',
            'update' => 'Modification',
            'delete' => 'Suppression',
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'role_change' => 'Changement de rôle',
            'permission_change' => 'Changement de permission'
        ];
        
        // Charger la configuration pour la partie fichiers de logs
        $config = config('log');
        
        return view('Log::logs.index', compact('logs', 'users', 'modelTypes', 'actions', 'config'));
    }

    /**
     * Afficher les détails d'un log
     */
    public function show(Log $log)
    {
        return view('Log::logs.show', compact('log'));
    }

    /**
     * Afficher les logs d'un utilisateur spécifique
     */
    public function userLogs(User $user)
    {
        return redirect()->route('personnels.Log.index', ['user_id' => $user->id]);
    }

    /**
     * Exporter les logs au format Excel
     */
    public function export(Request $request)
    {
        return Excel::download(new LogsExport($request), 'logs_systeme.xlsx');
    }

    /**
     * Supprimer un log
     */
    public function destroy(Log $log)
    {
        $log->delete();
        return redirect()->route('personnels.Log.index')->with('success', 'L\'entrée du journal a été supprimée avec succès.');
    }

    /**
     * Vider les logs
     */
    public function clear(Request $request)
    {
        // Vérifier si une date limite est spécifiée
        if ($request->has('older_than')) {
            Log::where('created_at', '<', $request->older_than)->delete();
            $message = 'Les logs antérieurs à la date spécifiée ont été supprimés avec succès.';
        } else {
            Log::truncate();
            $message = 'Tous les logs ont été supprimés avec succès.';
        }

        return redirect()->route('personnels.Log.index')->with('success', $message);
    }

    /**
     * Page de configuration des logs
     */
    public function settings()
    {
        // Charger la configuration existante si elle existe
        $configPath = storage_path('app/modules/log/config.json');
        $config = [];
        
        if (File::exists($configPath)) {
            $config = json_decode(File::get($configPath), true) ?? [];
        }

        return view('Log::admin.settings', compact('config'));
    }

    /**
     * Récupère les fichiers de logs
     */
    public function getLogs(Request $request)
    {
        $logPath = storage_path('logs');
        $logFiles = glob($logPath . '/*.log');
        
        $logs = [];
        foreach ($logFiles as $file) {
            $filename = basename($file);
            $logs[] = [
                'name' => $filename,
                'size' => $this->formatBytes(filesize($file)),
                'modified' => date('Y-m-d H:i:s', filemtime($file)),
                'path' => $file
            ];
        }
        
        // Tri des logs par date de modification (plus récent en premier)
        usort($logs, function($a, $b) {
            return strtotime($b['modified']) - strtotime($a['modified']);
        });
        
        return response()->json($logs);
    }

    /**
     * Récupère le contenu d'un fichier de log
     */
    public function viewLog(Request $request)
    {
        $request->validate([
            'file' => 'required|string'
        ]);
        
        $filePath = storage_path('logs/' . basename($request->file));
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Fichier de log introuvable'], 404);
        }
        
        $content = file_get_contents($filePath);
        $logEntries = $this->parseLogContent($content);
        
        return response()->json($logEntries);
    }

    /**
     * Exporte les logs au format Excel
     */
    public function exportLogs(Request $request)
    {
        $request->validate([
            'file' => 'required|string'
        ]);
        
        $filename = basename($request->file);
        return Excel::download(new LogsExport($filename), 'logs_export_' . now()->format('Y-m-d_His') . '.xlsx');
    }

    /**
     * Supprime un fichier de log
     */
    public function deleteLog(Request $request)
    {
        $request->validate([
            'file' => 'required|string'
        ]);
        
        $filePath = storage_path('logs/' . basename($request->file));
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Fichier de log introuvable'], 404);
        }
        
        if (unlink($filePath)) {
            return response()->json(['success' => 'Fichier supprimé avec succès']);
        }
        
        return response()->json(['error' => 'Impossible de supprimer le fichier'], 500);
    }

    /**
     * Nettoie tous les fichiers de log
     */
    public function clearAllLogs()
    {
        $logPath = storage_path('logs');
        $logFiles = glob($logPath . '/*.log');
        
        $deletedCount = 0;
        foreach ($logFiles as $file) {
            if (unlink($file)) {
                $deletedCount++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => $deletedCount . ' fichier(s) de log supprimé(s)'
        ]);
    }

    /**
     * Parse le contenu d'un fichier de log
     */
    private function parseLogContent($content)
    {
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*?)(?=\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]|$)/s';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        $logEntries = [];
        foreach ($matches as $match) {
            $logEntries[] = [
                'datetime' => $match[1],
                'level' => $match[2],
                'channel' => $match[3],
                'message' => trim($match[4])
            ];
        }
        
        return $logEntries;
    }

    /**
     * Formate les tailles de fichiers en unités lisibles
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
