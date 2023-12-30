<?php

namespace App\APIServices;

use App\APIServices\Vodafone\OAuth;
use App\Models\SMSSending;
use App\Models\SMSSent;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SMSMessaging
{
    public string $baseURL = "https://sdp.vodafone.com.gh/vfgh/gw/messaging/v1/outbound";
    public string $smsbaseURL = "https://winlogger.elcutoconsult.com/api/get-sms";


    public function __construct()
    {
        
    }

    public function sendContentToCustomer(SMSSending $sms, $serviceid)
    {
        $oauth = new OAuth($serviceid);

        $response = Http::withoutVerifying()->withHeaders([
            'Authorization' => 'Bearer ' . $oauth->getToken(),
            'Content-Type' => 'application/json',
        ])->post($this->baseURL, [
            "address" => [$sms->address],
            "senderAddress" => $sms->senderAddress,
            "outboundSMSTextMessage" => [
                "message" =>$sms->outboundSMSTextMessage
            ],
            "clientCorrelator" => $sms->clientCorrelator,
            "receiptRequest" => [
                "notifyURL" => $sms->notifyURL,
                "callbackData" => $sms->callbackData
            ],
            "senderName" => $sms->senderName
        ]);

        // Log::info($response->status());

        switch ($response->status()) {
            case 200:
            case 201:
            case 202:
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


    public function getMessageForDay($msisdn, $serviceId, $offerId){
        $response = Http::post($this->smsbaseURL, [
                'msisdn' => $msisdn,
                'serviceId' => $serviceId,
                'offerId' => $offerId
        ]);
        
        if($response->successful()){
            $res = $response->object();
            return [
                'status' => 'OK',
                'message' => $res->message
            ];
        }else{
            return [
                'status' => 'ERR',
            ];
        } 
    }



}