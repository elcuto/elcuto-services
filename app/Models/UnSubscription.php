<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UnSubscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    protected $table = 'UnSubscriptions';

    public static function createDeactivation($request){
        try{
            UnSubscription::create([
                'subscriptionId' =>  array_key_exists("subscriptionId",$request)?$request['subscriptionId']:'',
                'msisdn' =>  array_key_exists("msisdn",$request)?$request['msisdn']:'',
                'state' => 'INACTIVE',
                'offerId' =>  array_key_exists("offerId",$request) ? $request['offerId']:'',
                'offerName' =>  array_key_exists("offerName",$request) ? $request['offerName']:'',
                'transactionId' =>  array_key_exists("transactionId",$request) ? $request['transactionId']:'',
                'serviceNotificationType' => array_key_exists("serviceNotificationType",$request) ? $request['serviceNotificationType']:'',
                'serviceId' =>  array_key_exists("serviceId",$request) ? $request['serviceId']:'',
                'serviceName' =>  array_key_exists("serviceName",$request) ? $request['serviceName'] : '',
                'failureReason' =>  array_key_exists("failureReason",$request) ? $request['failureReason'] : '',
                'subscriptionStartDate' =>  array_key_exists("subscriptionStartDate",$request) ? $request['subscriptionStartDate'] : '',
                'subscriptionEndDate' =>  array_key_exists("subscriptionEndDate",$request) ? $request['subscriptionEndDate'] : '',
                'nextChargingDate' =>  array_key_exists("nextChargingDate",$request) ? $request['nextChargingDate'] : '',
                'lastRenewalDate' =>  array_key_exists("lastRenewalDate",$request) ? $request['lastRenewalDate'] : '',
                'channelType' =>  array_key_exists("channelType",$request) ? $request['channelType'] : '',
                'chargingPeriod' =>  array_key_exists("chargingPeriod",$request) ? $request['chargingPeriod'] : '',
                'subscriptionCounter' =>  array_key_exists("subscriptionCounter",$request) ? $request['subscriptionCounter'] : '',
                'requestDate' => date('Y-m-d H:i:s'),
                'chargedAmount' =>  array_key_exists("chargedAmount",$request) ? $request['chargedAmount'] : '',
                'inTry' =>  array_key_exists("inTry",$request) ? $request['inTry'] : ''
            ]);

        }catch(Exception $e){
            Log::info($e);
            return 'ERR';
        }
    }
}
