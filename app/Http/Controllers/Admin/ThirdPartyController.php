<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThirdParty;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ThirdPartyController extends Controller
{
    
    public function addThirdParty(){
        $cleint_id = Str::uuid();
        return view("admin.new_third_party", compact("cleint_id"));
    }

    public function getThirdParties(){
        $parties = ThirdParty::all();
        return view("admin.third_parties", compact("parties"));
    }

    public function saveCompany(Request $request){
        try{
            $account = ThirdParty::create([
                "name" => $request->name,
                "network" => $request->network,
                "description" => $request->description,
                "client_id" => $request->client_id,
                "email" => $request->email
            ]);

            $api_key =  $account->createToken($request->email)->plainTextToken;
            Log::info($api_key);
            if($api_key){
                $account->update([
                    "secret_key" => $api_key
                ]);
            }else{
                $account->delete();
                return response()->json([
                    "status" => "error",
                    "message" => "Unable to add third party company. Error generating Secret Key"
                ]);
            }

            return response()->json([
                "status" => "success",
                "message" => "Third part company successfully created. Proceed to add services onto the platform"
            ]);
        }catch(Exception $e){
            Log::error($e);
            return response()->json([
                "status" => "error",
                "message" => "Unable to add third party company"
            ]);
        }
    }
}
