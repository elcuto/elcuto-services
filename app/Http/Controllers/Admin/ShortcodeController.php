<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shortcode;
use Illuminate\Http\Request;

class ShortcodeController extends Controller
{
    public function getShortcodes(){
        $shortcodes = Shortcode::all();
        return view("admin.shortcodes", compact("shortcodes"));
    }
}
