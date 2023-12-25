<?php

namespace App\APIServices\Vodafone;

use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use App\Models\DirectCharge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\APIServices\Vodafone\OAuth;

class DirectDebitCharging
{
    // public static string $baseURL = "https://sdp.vodafone.com.gh/vfgh/gw/charging/v1/charge";
    public static string $baseURL = "https://sdp.vodafone.com.gh/vfgh/gw/charging/v1/charge";

    public static function chargeCustomer( $mtsms, $serviceid)
    {
        $oauth = new OAuth($serviceid);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' .$oauth->getToken(),
            'Content-Type' => 'application/json',
        ])->put(self::$baseURL, [
            //new
            "amount" =>  (float)$mtsms->amount,
            "clientChargeTransactionId" => $mtsms->clientChargeTransactionId,
            "clientRequestId" => $mtsms->clientRequestId,
            "channel" => $mtsms->channel,
            "msisdn" => $mtsms->msisdn,
            "offer" => $mtsms->offer,
            "description" => $mtsms->description != null ? $mtsms->description : $mtsms->offer,
            "unit" => 1,
            "parameters" => []
        ]);

        Log::info($response->json());
        $statusCode = $response->status();
        switch ($statusCode) {
            case 200:
            case 201:
                return [
                    'status' => 'OK',
                    'data' => $response->json()
                ];
                break;
            default:
                return [
                    'status' => 'ERR',
                    'data' => $response->json()
                ];
        }
    }
}