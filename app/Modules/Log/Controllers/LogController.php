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
        
        try {
            $content = file_get_contents($filePath);
            $logEntries = $this->parseLogContent($content);
            
            return response()->json($logEntries);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la lecture du fichier: ' . $e->getMessage()
            ], 500);
        }
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
        // Format standard des logs Laravel
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*?)(?=\n\[|\Z)/s';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        $logEntries = [];
        
        if (!empty($matches)) {
            foreach ($matches as $match) {
                $logEntries[] = [
                    'date' => $match[1],
                    'channel' => $match[2],
                    'level' => $match[3],
                    'message' => trim($match[4])
                ];
            }
        } else {
            // Format alternatif (pour les versions plus récentes de Laravel)
            $lines = explode("\n", $content);
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                
                // Essayer de repérer les éléments habituels d'une ligne de log
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (.+)/', $line, $lineMatch)) {
                    $logTime = $lineMatch[1];
                    $restOfLine = $lineMatch[2];
                    
                    // Déterminer le niveau de log à partir de la ligne
                    $level = 'info';
                    $channel = 'app';
                    
                    if (strpos($restOfLine, 'ERROR') !== false) {
                        $level = 'error';
                    } elseif (strpos($restOfLine, 'WARNING') !== false) {
                        $level = 'warning';
                    } elseif (strpos($restOfLine, 'CRITICAL') !== false) {
                        $level = 'critical';
                    } elseif (strpos($restOfLine, 'DEBUG') !== false) {
                        $level = 'debug';
                    } elseif (strpos($restOfLine, 'NOTICE') !== false) {
                        $level = 'notice';
                    }
                    
                    $logEntries[] = [
                        'date' => $logTime,
                        'channel' => $channel,
                        'level' => $level,
                        'message' => $restOfLine
                    ];
                }
            }
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
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Récupère la configuration actuelle
     */
    public function getConfig()
    {
        $configPath = storage_path('app/modules/log/config.json');
        if (File::exists($configPath)) {
            return response()->json(json_decode(File::get($configPath), true));
        }

        // Renvoyer la configuration par défaut depuis le fichier de configuration
        return response()->json(config('log'));
    }

    /**
     * Sauvegarde la configuration
     */
    public function saveConfig(Request $request)
    {
        try {
            $config = $request->all();
            
            // Valider les données essentielles
            if (!isset($config['user_actions_enabled'])) {
                $config['user_actions_enabled'] = false;
            }
            
            if (!isset($config['actions_to_log'])) {
                $config['actions_to_log'] = [];
            }
            
            if (!isset($config['excluded_columns'])) {
                $config['excluded_columns'] = [];
            }
            
            if (!isset($config['log_files'])) {
                $config['log_files'] = [
                    'max_age_days' => 30,
                    'max_size_mb' => 100,
                    'auto_clean' => false
                ];
            }
            
            if (!isset($config['levels'])) {
                $config['levels'] = [];
            }
            
            // Créer le répertoire de stockage s'il n'existe pas
            $dir = storage_path('app/modules/log');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            
            // Sauvegarder la configuration
            File::put(
                storage_path('app/modules/log/config.json'),
                json_encode($config, JSON_PRETTY_PRINT)
            );

            // Mettre à jour le fichier de configuration si nécessaire
            $this->updateConfigFile($config);
            
            return response()->json([
                'success' => true,
                'message' => 'Configuration sauvegardée avec succès.'
            ]);
        } catch (\Exception $e) {
            LogFacade::error('Erreur lors de la sauvegarde de la configuration des logs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour le fichier de configuration basé sur les paramètres utilisateur
     */
    private function updateConfigFile(array $config)
    {
        // Cette méthode pourrait être utilisée pour mettre à jour le fichier de configuration
        // du système selon les paramètres utilisateur (par exemple, mettre à jour config/log.php)
        // Mais cela nécessiterait des permissions d'écriture sur le fichier de config
        
        // Pour l'instant, on se contente de stocker dans le JSON
        return true;
    }

    /**
     * Récupère les logs récents de la base de données
     */
    public function getDbLogs(Request $request)
    {
        try {
            // Récupérer les logs avec les relations user et actor
            $logs = Log::with(['user', 'actor'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            return response()->json([
                'success' => true,
                'logs' => $logs
            ]);
        } catch (\Exception $e) {
            LogFacade::error('Erreur lors de la récupération des logs de la base de données: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des logs: ' . $e->getMessage()
            ], 500);
        }
    }
}
