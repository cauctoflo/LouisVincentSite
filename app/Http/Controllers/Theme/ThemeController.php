<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ThemeController extends Controller
{
    public function getTheme()
    {
        $themes = [];
        $themePath = resource_path('views/Theme');
        
        if(File::isDirectory($themePath)) {
            $directories = File::directories($themePath);
            foreach ($directories as $directory) {
                $themes[] = basename($directory);
            }
        }        
        $currentTheme = Setting::getValue('theme');

        if (in_array($currentTheme, $themes)) {
            return $currentTheme;
        } else {
            return "default";
        }
    }
}
