<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Theme\ThemeController;

class WelcomePageController extends Controller
{

    const THEME_PATH = "Theme.";

    public function index()
    {
        $tc = new ThemeController();
        $theme = $tc->getTheme();
        return view(self::THEME_PATH . $theme . ".welcome");
    }
}
