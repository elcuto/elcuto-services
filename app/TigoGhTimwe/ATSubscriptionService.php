<?php


namespace App\TigoGhTimwe;


use App\Services\TimweEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ATSubscriptionService
{


    public static function manualSubscribe($userIdentifier, $userIdentifierType, $productId, $mcc, $mnc, $entryChannel, $largeAccount, $subKeyword, $trackingId, $clientIp, $campaignUrl){
        //this will handle the manual subscription to timwe services.
        try{

            $token = self::generateAuthenticationKey($productId);
            $response = Http::withHeaders([
                            'X-First' => 'foo',
                            'X-Second' => 'bar'
                        ])->post(config("at.base_url")."/subscription/optin/".config("at.partner_role"),
                            [
                                "userIdentifier" => $userIdentifier,
                                "userIdentifierType" => $userIdentifierType,
                                "productId" => $productId,
                                "mcc" => $mcc,
                                "mnc" => $mnc,
                                "entryChannel" => $entryChannel,
                                "largeAccount" => $largeAccount,
                                "subKeyword" => $subKeyword,
                                "trackingId" => $trackingId,
                                "clientIp" => $clientIp,
                                "campaignUrl" => $campaignUrl
                            ]
                        );
            if($response->status() == 200){
                return [
                    "status" => "success",
                    "data" => response()->object()
                ];
            }else if($response->status() == 400){
                Log::error(__CLASS__.": Invalid authentication response");
                return [
                    "status" => "error",
                    "data" => response()->object()
                ];
            }else{
                Log::error(__CLASS__.": Status Code: ".$response->status());
                return [
                    "status" => "error",
                    "data" => response()->object()
                ];
            }

        }catch (\Exception $e){
            Log::error($e);
        }

    }


    public static function manualUnSubscribe(){

    }


    private static function generateAuthenticationKey($partnerServiceId) : string
    {

        $encryption = new TimweEncryptionService(config('at.pre_shared_key'));

        $timestamp = round(microtime(true) * 1000);
        $data = $partnerServiceId."#".$timestamp;

        return $encryption->encrypt($data);

    }
}
