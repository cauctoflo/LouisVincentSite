<?php

namespace App\Http\Controllers;

use App\Modules\WebTv\Controllers\WebTvController;
use App\Modules\Internat\Controllers\InternatController;

class HomeController extends Controller
{
    protected $webTvController;
    protected $internatController;

    public function __construct(WebTvController $webTvController, InternatController $internatController)
    {
        $this->webTvController = $webTvController;
        $this->internatController = $internatController;
    }

    public function index()
    {
        $data = [
            'liveId' => $this->webTvController->getLive(),
        ];

        return view('Theme.Default.landing.index', $data);
    }
} 