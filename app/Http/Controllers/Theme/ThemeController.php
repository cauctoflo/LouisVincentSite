<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    public function getTheme()
    {
        dd(in_array(Storage::directories(resource_path())));

        $themes = [];
        $currentTheme = Setting::getValue('theme');
        return in_array($currentTheme, $themes) ? $currentTheme : "default";
    }
}
