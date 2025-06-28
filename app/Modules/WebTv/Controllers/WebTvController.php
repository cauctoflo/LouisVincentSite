<?php

namespace App\Modules\WebTv\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class WebTvController extends Controller
{
    /**
     * Configuration du module WebTV
     */
    protected $config;

    /**
     * Initialise le contrôleur avec la configuration actuelle
     */
    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Charge la configuration depuis le fichier JSON
     */
    protected function loadConfig()
    {
        $configPath = storage_path('app/modules/webtv/config.json');
        
        if (File::exists($configPath)) {
            $this->config = json_decode(File::get($configPath), true) ?? [];
        } else {
            // Configuration par défaut si aucun fichier n'existe
            $this->config = [
                'status' => 'active',
                'title' => 'WebTV',
                'streaming_service' => 'youtube',
                'channel_id' => 'UCmJIRE7hK5PJTk-tUrR-lMA', // ID par défaut
                'api_key' => '',
                'max_videos' => 10,
                'cache_duration' => 60 // minutes
            ];
        }
    }

    /**
     * Récupère les dernières vidéos de la chaîne YouTube
     * et le nombre d'abonnés (scrap sans API)
     */
    public function getChannel()
    {
        // Utiliser l'ID de la chaîne à partir de la configuration ou celui par défaut
        $channelId = $this->config['channel_id'] ?? 'UCmJIRE7hK5PJTk-tUrR-lMA';

        // URL du flux RSS YouTube
        $rssUrl = "https://www.youtube.com/feeds/videos.xml?channel_id={$channelId}";
        $rssContent = @file_get_contents($rssUrl);

        if (!$rssContent) {
            return response()->json(['error' => 'Impossible de récupérer le flux RSS de la chaîne.'], 500);
        }

        $xml = simplexml_load_string($rssContent);
        if (!$xml) {
            return response()->json(['error' => 'Erreur de parsing du flux RSS.'], 500);
        }

        $videos = [];
        foreach ($xml->entry as $entry) {
            $videoId = (string) $entry->children('yt', true)->videoId;
            $thumbnailUrl = "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg";
            
            $videos[] = [
                'title' => (string) $entry->title,
                'url' => (string) $entry->link['href'],
                'published' => (string) $entry->published,
                'videoId' => $videoId,
                'thumbnail' => $thumbnailUrl, // Ajout du thumbnail pour l'aperçu
                'description' => $this->getShortDescription($entry),
            ];
        }

        // Limiter le nombre de vidéos selon la configuration
        $maxVideos = $this->config['max_videos'] ?? 10;
        $videos = array_slice($videos, 0, $maxVideos);

        // Déterminer si la chaîne est en live maintenant et récupérer l'id de la vidéo en live
        $isChannelLiveNow = false;
        $liveVideoId = null;
        foreach ($videos as $video) {
            try {
                $videoStats = $this->fetchVideoStats($video['videoId']);
                if (
                    (is_array($videoStats) && !empty($videoStats['isLiveNow']) && $videoStats['isLiveNow']) ||
                    (is_object($videoStats) && !empty($videoStats->isLiveNow) && $videoStats->isLiveNow)
                ) {
                    $isChannelLiveNow = true;
                    $liveVideoId = $video['videoId'];
                    break;
                }
            } catch (\Exception $e) {
                // On ignore les erreurs individuelles et on continue
                continue;
            }
        }
        
            
        $json = @file_get_contents("https://yt.lemnoslife.com/noKey/channels?part=statistics&id={$channelId}");
        $data = json_decode($json, true);
        $subscriberCount = $data['items'][0]['statistics']['subscriberCount'] ?? null;

        // dd($subscriberCount);

        return response()->json([
            'channel_id' => $channelId,
            'channel_title' => $this->config['title'] ?? 'WebTV',
            'service' => $this->config['streaming_service'] ?? 'youtube',
            'videos' => $videos,
            'isChannelLiveNow' => $isChannelLiveNow,
            'liveVideoId' => $liveVideoId,
            'subscribers' => $subscriberCount
        ]);
    }

    /**
     * Extrait une description courte depuis l'entrée XML
     */
    protected function getShortDescription($entry)
    {
        // Essayer d'extraire la description depuis le namespace media, si disponible
        $description = '';
        
        if (isset($entry->children('media', true)->group->description)) {
            $description = (string) $entry->children('media', true)->group->description;
        } else if (isset($entry->summary)) {
            $description = (string) $entry->summary;
        } else if (isset($entry->content)) {
            $description = (string) $entry->content;
        }
        
        // Limiter la longueur de la description
        if (strlen($description) > 150) {
            $description = substr($description, 0, 147) . '...';
        }
        
        return $description;
    }

    /**
     * Récupère les statistiques d'une vidéo
     */
    public function getVideoStats(Request $request = null, string $videoId = null)
{
    if ($videoId === null) {
        $videoId = $request->get('videoId');
    }

    if (!$videoId) {
        return response()->json(['error' => 'Paramètre videoId manquant.'], 422);
    }

    $cacheKey = 'webtv_video_stats_' . $videoId;
    $cacheDuration = $this->config['cache_duration'] ?? 60;

    if ($cacheDuration > 0) {
        $cachedStats = Cache::get($cacheKey);
        if ($cachedStats) {
            // Ajout de l'attribut isChannelLiveNow à la réponse mise en cache si absent
            if (!array_key_exists('isChannelLiveNow', $cachedStats)) {
                $cachedStats['isChannelLiveNow'] = $this->isChannelLiveNow();
            }
            return response()->json($cachedStats);
        }
    }

    $videoUrl = "https://www.youtube.com/watch?v={$videoId}";
    $html = @file_get_contents($videoUrl);
    if (!$html) {
        return response()->json(['error' => 'Impossible de charger la page YouTube.'], 500);
    }

    if (preg_match('/var ytInitialPlayerResponse = ({.*?});/s', $html, $matches)) {
        $json = $matches[1];
        $data = json_decode($json, true);

        if (!$data) {
            return response()->json(['error' => 'Erreur de parsing JSON.'], 500);
        }

        $videoDetails = $data['videoDetails'] ?? [];
        $microformat = $data['microformat']['playerMicroformatRenderer'] ?? [];
        $playabilityStatus = $data['playabilityStatus'] ?? [];

        $isLiveContent = $videoDetails['isLiveContent'] ?? false;
        $hasLiveStream = isset($playabilityStatus['liveStreamability']);
        $status = $playabilityStatus['status'] ?? null;

        $isLiveNow = $isLiveContent && $hasLiveStream && $status === 'OK';
        $isUpcoming = $isLiveContent && $status === 'LIVE_STREAM_OFFLINE';

        $result = [
            'title' => $videoDetails['title'] ?? '',
            'author' => $videoDetails['author'] ?? '',
            'viewCount' => $videoDetails['viewCount'] ?? 0,
            'duration' => gmdate("H:i:s", $videoDetails['lengthSeconds'] ?? 0),
            'shortDescription' => $videoDetails['shortDescription'] ?? '',
            'uploadDate' => $microformat['uploadDate'] ?? '',
            'publishDate' => $microformat['publishDate'] ?? '',
            'isLiveContent' => $isLiveContent,
            'isLiveNow' => $isLiveNow,
            'isUpcoming' => $isUpcoming,
            'thumbnail' => isset($videoDetails['thumbnail']['thumbnails']) 
                ? end($videoDetails['thumbnail']['thumbnails'])['url'] 
                : "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg",
        ];

        if ($cacheDuration > 0) {
            Cache::put($cacheKey, $result, $cacheDuration * 60);
        }

        return response()->json($result);
    }

    return response()->json(['error' => 'Données YouTube non trouvées.'], 500);
}


    /**
     * Récupère un aperçu de la vidéo
     */
    public function getVideoPreview(Request $request)
    {
        $videoId = $request->get('videoId');

        if (!$videoId) {
            return response()->json(['error' => 'Paramètre videoId manquant.'], 422);
        }

        // Utiliser d'abord la méthode existante pour obtenir les statistiques
        $statsResponse = $this->getVideoStats($request);
        $stats = $statsResponse->getData(true);

        if (isset($stats['error'])) {
            return $statsResponse;
        }

        // Créer un aperçu plus riche en combinant les statistiques avec des données supplémentaires
        $preview = [
            'videoId' => $videoId,
            'title' => $stats['title'] ?? '',
            'author' => $stats['author'] ?? '',
            'viewCount' => $stats['viewCount'] ?? 0,
            'duration' => $stats['duration'] ?? '',
            'description' => $stats['shortDescription'] ?? '',
            'publishDate' => $stats['publishDate'] ?? '',
            'thumbnail' => $stats['thumbnail'] ?? "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg",
            'embedUrl' => "https://www.youtube.com/embed/{$videoId}",
            // Options d'intégration basées sur la configuration
            'embedOptions' => [
                'autoplay' => $this->config['autoplay'] ?? false,
                'showControls' => $this->config['show_controls'] ?? true,
                'showRelated' => $this->config['show_related'] ?? false,
                'showTitle' => $this->config['show_title'] ?? true,
                'defaultResolution' => $this->config['default_resolution'] ?? '720p'
            ]
        ];

        return response()->json($preview);
    }


    private function fetchVideoStats(string $videoId)
    {
        $cacheKey = 'webtv_video_stats_' . $videoId;
        $cacheDuration = $this->config['cache_duration'] ?? 60;

        if ($cacheDuration > 0 && $cached = Cache::get($cacheKey)) {
            return $cached;
        }

        $videoUrl = "https://www.youtube.com/watch?v={$videoId}";
        $html = @file_get_contents($videoUrl);
        if (!$html) {
            throw new \Exception('Impossible de charger la page YouTube.');
        }

        if (!preg_match('/var ytInitialPlayerResponse = ({.*?});/s', $html, $matches)) {
            throw new \Exception('Données YouTube non trouvées.');
        }

        $data = json_decode($matches[1], true);
        if (!$data) {
            throw new \Exception('Erreur de parsing JSON.');
        }

        $videoDetails = $data['videoDetails'] ?? [];
        $microformat = $data['microformat']['playerMicroformatRenderer'] ?? [];
        $playabilityStatus = $data['playabilityStatus'] ?? [];

        $isLiveContent = $videoDetails['isLiveContent'] ?? false;
        $hasLiveStream = isset($playabilityStatus['liveStreamability']);
        $status = $playabilityStatus['status'] ?? null;

        $isLiveNow = $isLiveContent && $hasLiveStream && $status === 'OK';
        $isUpcoming = $isLiveContent && $status === 'LIVE_STREAM_OFFLINE';

        $result = [
            'title' => $videoDetails['title'] ?? '',
            'author' => $videoDetails['author'] ?? '',
            'viewCount' => $videoDetails['viewCount'] ?? 0,
            'duration' => gmdate("H:i:s", $videoDetails['lengthSeconds'] ?? 0),
            'shortDescription' => $videoDetails['shortDescription'] ?? '',
            'uploadDate' => $microformat['uploadDate'] ?? '',
            'publishDate' => $microformat['publishDate'] ?? '',
            'isLiveContent' => $isLiveContent,
            'isLiveNow' => $isLiveNow,
            'isUpcoming' => $isUpcoming,
            'thumbnail' => isset($videoDetails['thumbnail']['thumbnails']) 
                ? end($videoDetails['thumbnail']['thumbnails'])['url'] 
                : "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg",
        ];

        if ($cacheDuration > 0) {
            Cache::put($cacheKey, $result, $cacheDuration * 60);
        }

        return $result;
    }


    public function getLive()
    {
        if ($this->getChannel()->getData()->isChannelLiveNow) {
            return $this->getChannel()->getData()->liveVideoId;
        }

        return null;
    }
    
    public function getAllViews()
    {
        $sum = 0;
        foreach ($this->getChannel()->getData()->videos as $video) {
            // dd($this->fetchVideoStats($video->videoId));
        }
    }

    public function getChannelSubscribers()
    {
        
    }
}