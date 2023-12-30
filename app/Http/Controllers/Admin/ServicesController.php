<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllService;
use App\Models\Shortcode;
use App\Models\ThirdParty;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function newService() : View
    {
        $shortcodes = Shortcode::all();
        $parties = ThirdParty::all();
        return view("admin.newservice", compact("shortcodes", "parties"));
    }

    public function saveService(Request $request){
        try{
            AllService::create([
                "service_name" => $request->service_name,
                "service_description" => $request->description,
                "shortcode" => $request->shortcode,
                "service_network" => $request->network,
                "at_service_id" => $request->at_service_id,
                "vf_service_id" => $request->vf_service_id,
                "vf_offer_id" => $request->vf_offer_id,
                "vf_client_id" => $request->vf_client_id,
                "vf_api_secret" => $request->vf_api_key,
                "call_back_url" => $request->callback_url
            ]);
            return response()->json([
                "status" => "success",
                "message" => "Service successfully registered"
            ]);
        }catch(Exception $e){
            Log::error($e);
            return response()->json([
                "status" => "error",
                "message" => "Unable to save new service. Please  try again"
            ]);
        }
    }
}
