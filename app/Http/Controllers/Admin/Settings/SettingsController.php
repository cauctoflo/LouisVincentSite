<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{

    const VIEW_PATH = "admin.settings.";

    public function index()
    {
        return view("admin.settings.index");
    }

    public function edit(int $id)
    {
        $setting = Setting::find($id);
        if ($setting) {
            $name = $setting->key;
            if (str_contains($name, ".")) {
                $name = explode(".", $name)[0];
            }
            $view_path = $this::VIEW_PATH . $name .".edit";
            return view($view_path, ["setting" => $setting]);
        }
        else {
            return redirect()->route("personnels.settings.index")->with(["not_found" => "Erreur : le paramètre cherché n'a pas été trouvé."]);
        }
    }

    public function store(Request $request, Setting $setting)
    {
        $setting->value = $request->input("value");
        $setting->save();
        return redirect()->route("personnels.settings.index")->with("success", "Le paramètre a bien été modifié");
    }
}
