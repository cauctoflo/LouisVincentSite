<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Theme\ThemeController;
class ViewDispatchController extends Controller
{
    public function getPath($view)
    {
        $themeController = new ThemeController();
        $theme = $themeController->getTheme();
        return view('Theme.' . $theme . '.' . $view);
    }

    public function index()
    {
        return $this->getPath('landing.index');
    }

}
