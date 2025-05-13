<?php

namespace App\Http\Controllers\Admin\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewDispatchController extends Controller
{
    

    const PATH = 'Admin.';


    public function Index()
    {
        return view(self::PATH . 'Dashboard.index');
    }
}
