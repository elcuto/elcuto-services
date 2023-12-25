<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllService;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index() : View
    {
        $service_count = AllService::count();
        $at_promo_subs_count = DB::connection("at_mega_promo")->table("at_promo_subs")->select("msisdn")->distinct()->count();
        $vf_promo_subs_count = DB::connection("vf_connection")->table("PromoSubs")->select("MSISDN")->distinct()->count();
        return view("admin.index", compact("service_count", "at_promo_subs_count", "vf_promo_subs_count"));
    }
}
