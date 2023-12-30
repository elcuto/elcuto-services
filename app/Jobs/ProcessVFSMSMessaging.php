<?php

namespace App\Jobs;

use App\APIServices\SMSMessaging;
use App\Models\SMSSending;
use App\Models\SMSSent;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessVFSMSMessaging implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public SMSSending $sms;
    public $msisdn;
    public $shortcode;
    public $messageText;
    public $serviceid;
    public string $deactivationMessage = "Dial *463# to manage your subscription";

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($msisdn, $shortcode='4060', $message, $serviceid)
    {
        $this->msisdn = $msisdn;
        $this->shortcode = $shortcode;
        $this->messageText = $message;
        $this->serviceid = $serviceid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $messageid = Str::orderedUuid();
            
        $smssending = SMSSending::create([
            'address' => $this->msisdn,
            'senderAddress' => $this->shortcode,
            'outboundSMSTextMessage' => $this->messageText,
            'clientCorrelator' => $messageid,
            'notifyURL' => 'https://vftelenity.elcutoconsult.com/api/sms-callback',
            'senderName' => 'ELCUTO',
            'callbackData' => $messageid
        ]);
        $smsSending = $smssending->fresh();
        try {
            
            $sendsms = new SMSMessaging();

            $result = $sendsms->sendContentToCustomer($smsSending, $this->serviceid);
            $deliveryInfo = $result['data'];
            // Log::info($result);
            if ($result['status'] == 'OK') {
                // $deliveryInfo = $result['data']['deliveryInfoList']->deliveryInfo[0];
                $deliveryInfo = $result['data'];
                // Log::info('========SMS TEXT ERROR==================');
                // Log::info($deliveryInfo);
                // Log::info('========SMS TEXT ERROR==================');
                if(array_key_exists('outboundSMSMessageRequest',$deliveryInfo)){
                    $deliveryInfo = $deliveryInfo['outboundSMSMessageRequest']['deliveryInfoList']['deliveryInfo'][0];
                    SMSSent::create([
                        "address" => $smsSending->address,
                        "senderAddress" => $smsSending->senderAddress,
                        "outboundSMSTextMessage" => $smsSending->outboundSMSTextMessage,
                        "clientCorrelator" => $messageid,
                        "notifyURL" => $smsSending->notifyURL,
                        "senderName" => 'ELCUTO',
                        "callbackData" => $messageid,
                        "deliveryStatus" => array_key_exists("deliveryStatus",$deliveryInfo)?$deliveryInfo['deliveryStatus']:null,
                        "requestId" => array_key_exists("requestId",$deliveryInfo)?$deliveryInfo['requestId']:null,
                        "errorCode" => array_key_exists("errorCode",$deliveryInfo)?$deliveryInfo['errorCode']:null,
                        "errorDescription" => array_key_exists("errorDescription",$deliveryInfo)?$deliveryInfo['errorDescription']:null,
                        "sent" => 1
                    ]);

                    $smsSending->delete();

                }else{
                    SMSSent::create([
                        "address" => $smsSending->address,
                        "senderAddress" => $smsSending->senderAddress,
                        "outboundSMSTextMessage" => $smsSending->outboundSMSTextMessage,
                        "clientCorrelator" => $messageid,
                        "notifyURL" => $smsSending->notifyURL,
                        "senderName" => 'ELCUTO',
                        "callbackData" => $messageid,
                        "deliveryStatus" => array_key_exists("deliveryStatus",$deliveryInfo)?$deliveryInfo['deliveryStatus']:null,
                        "requestId" => array_key_exists("requestId",$deliveryInfo)?$deliveryInfo['requestId']:null,
                        "errorCode" => array_key_exists("code",$deliveryInfo)?$deliveryInfo['code']:null,
                        "errorDescription" => array_key_exists("message",$deliveryInfo)?$deliveryInfo['message']:null,
                        "sent" => 1
                    ]);

                    $smsSending->delete();
                    
                    Log::info('========SEND MESSAGE ERROR==================');
                    Log::info($result);
                    Log::info('========SEND MESSAGE ERROR==================');
                }

            } else {
                SMSSent::create([
                    "address" => $smsSending->address,
                    "senderAddress" => $smsSending->senderAddress,
                    "outboundSMSTextMessage" => $smsSending->outboundSMSTextMessage,
                    "clientCorrelator" => $messageid,
                    "notifyURL" => $smsSending->notifyURL,
                    "senderName" => 'ELCUTO',
                    "callbackData" => $messageid,
                    "deliveryStatus" => array_key_exists("deliveryStatus",$deliveryInfo)?$deliveryInfo['deliveryStatus']:null,
                    "requestId" => array_key_exists("requestId",$deliveryInfo)?$deliveryInfo['requestId']:null,
                    "errorCode" => array_key_exists("code",$deliveryInfo)?$deliveryInfo['code']:null,
                    "errorDescription" => array_key_exists("message",$deliveryInfo)?$deliveryInfo['message']:null,
                    "sent" => 1
                ]);
                $smsSending->delete();
                Log::info(',,,,,,,,,,,==================');
                Log::info($result);
                Log::info(',,,,,,,,,,,==================');
            }
        } catch (Exception $e) {
            $smsSending->delete();
            Log::info($e);
        }


    }


     public function checkIfDeactivationMessageSentToUser(){
         $smsSent = SMSSent::where('address', $this->msisdn)->where('created_at', '>=', Carbon::now()->toDateString())->first();
         if($smsSent){
            return true;
         }else{
            return false;
         }
    }
}