<?php

use Illuminate\Support\Facades\Route;
use App\Modules\WebTv\Controllers\WebTvController;

/*
|--------------------------------------------------------------------------
| WebTv Module API Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('webtv')->group(function () {
    /**
     * @api {get} /api/webtv/channel Get all videos from YouTube channel
     * @apiName GetChannel
     * @apiGroup WebTv
     * @apiDescription Retrieves the latest videos from the YouTube channel
     * 
     * @apiSuccess {String} channel_id The YouTube channel ID
     * @apiSuccess {Array} videos List of videos
     * @apiSuccess {String} videos.title Video title
     * @apiSuccess {String} videos.url Video URL
     * @apiSuccess {String} videos.published Publication date
     * @apiSuccess {String} videos.videoId YouTube video ID
     */
    Route::get('/channel', [WebTvController::class, 'getChannel']);

    /**
     * @api {get} /api/webtv/video Get video statistics
     * @apiName GetVideoStats  
     * @apiGroup WebTv
     * @apiDescription Get detailed statistics for a specific YouTube video
     *
     * @apiParam {String} videoId The YouTube video ID (e.g. api/webtv/video?videoId=Gc28kxyftOA)
     *
     * @apiSuccess {String} title Video title
     * @apiSuccess {String} author Channel name
     * @apiSuccess {Number} viewCount Number of views
     * @apiSuccess {String} duration Video duration (HH:MM:SS)
     * @apiSuccess {String} shortDescription Video description
     * @apiSuccess {String} uploadDate Upload date
     * @apiSuccess {String} publishDate Publication date
     * @apiSuccess {Boolean} isLive Whether the video is a live stream
     */
    Route::get('/video', [WebTvController::class, 'getVideoStats']);
});