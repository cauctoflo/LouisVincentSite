<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        return view("admin.settings.index");
    }

    public function edit(Setting $setting)
    {
        return view("admin.settings.edit", ["setting" => $setting]);
    }

    public function store(Setting $setting)
    {
        $setting->value = $_POST["value"];
        $setting->save();
        return redirect()->route("admin.settings.index")->with("success", "Le paramètre a bien été modifié");
    }
}
