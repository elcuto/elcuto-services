<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shortcode;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function newService() : View
    {
        $shortcodes = Shortcode::all();
        return view("admin.newservice", compact("shortcodes"));
    }
}
