<?php

namespace App\Http\Controllers\Admin\View\Sidebar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JsonReadController extends Controller
{
    public function __invoke(Request $request)
    {
        $jsonFilePath = storage_path('app/json/sidebar.json');

        if (!file_exists($jsonFilePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $jsonData = file_get_contents($jsonFilePath);
        $data = json_decode($jsonData, true);

        return response()->json($data);
    }

    
}
