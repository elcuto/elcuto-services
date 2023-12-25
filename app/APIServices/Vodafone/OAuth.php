<?php

namespace App\APIServices\Vodafone;


use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use App\Models\AllService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OAuth
{
    // public string $key = 'eda9ad44-9295-4886-a416-8cebe203284f';
    // public string $password = 'b9bf8f34e01b4678bace52b0f9a4f9b4';
    public string $baseURL = "https://sdp.vodafone.com.gh/oauth/token?grant_type=client_credentials";
    public $serviceid;

    public function __construct($serviceid)
    {
       $this->serviceid = $serviceid; 
    }
   


    public function generateToken()
    {
        $service = AllService::where('vf_service_id', $this->serviceid)->first();
     
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->send('POST', $this->baseURL, [
            'form_params' => [
                'client_id'=> $service!=null?$service->vf_client_id:'',
                'client_secret'=> $service!=null?$service->vf_api_secret:''
            ],
        ]);
        $res = $response->json();
        if ($response->status() == 200 || $response->status() == 201) {
            $accesstoken = AccessToken::create([
                'access_token' => $res['access_token'],
                'token_type' => $res['token_type'],
                'issued_at' => date('Y-m-d H:i:s'),
                'expires_in' => $res['expires_in'],
                'expiry_date' => Carbon::now()->addSeconds($res['expires_in'])->format('Y-m-d H:i:s'),
                'serviceid' => $this->serviceid
            ]);
            return $accesstoken;
        } else {
            return null;
        }
    }


    public  function getToken()
    {

        $tokenData  = AccessToken::where('serviceid', $this->serviceid)->orderBy('id', 'DESC')->first();
        if ($tokenData == null) {
            return $this->generateToken()->access_token;
        }
        $date1 = Carbon::parse($tokenData->issued_at);
        $date2 = Carbon::now()->format('Y-m-d H:i:s');
        if ($date1->diffInMinutes($date2) <= 50) {
            return $tokenData->access_token;
        } else {
            return $this->generateToken()->access_token;
        }
    }
}