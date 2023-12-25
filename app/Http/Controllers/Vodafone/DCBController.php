<?php

namespace App\Http\Controllers\Vodafone;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\APIServices\SMSMessaging;
use App\Jobs\ProcessVFPromoMORequest;
use App\Models\SMSSending;
use App\Vodafone\PromoMechanics;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DCBController extends Controller
{
    public function assocURLTest(Request $request){
        try{
            Log::info($request->all());

            DB::connection("vf_connection")->table('DCB_MONotifications')->insert([
                'merchantId' => $request->merchantId,
                'carrierId' => $request->carrierId,
                'shortCode' => $request->shortCode,
                'accountIdType' => array_key_exists('accountIdType', $request->accountInfo) ? $request->accountInfo['accountIdType'] : '',
                'accountId' => array_key_exists('accountId', $request->accountInfo) ? $request->accountInfo['accountId'] : '',
                'msisdn' => array_key_exists('accountId', $request->accountInfo) ? $request->accountInfo['accountId'] : '',
                'smsText' => $request->smsText,
                'carrierTransactionId' => $request->carrierTransactionId,
                'actionType' => 'ADD',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $msisdn = array_key_exists('accountId', $request->accountInfo) ? $request->accountInfo['accountId'] : '';

            switch($request->shortCode){
                case '4063': //allowed promo shortcode
                case '625001': //allowed promo shortcode
                    $textMsg = strtoupper($request->smsText);
                    Log::info("CONTROLLER SMS => ".$request->smsText);
                    ProcessVFPromoMORequest::dispatch($msisdn, $request->carrierTransactionId, $request->shortCode,  $textMsg)->onConnection('redis');
                    Log::info("Promo Called");
                    break;
                    return response()->json('OK');
    
                default:
                    Log::info('DCB: Shortcode Not Recognized => '.$request->shortCode);
                    return response()->json('OK');
            }

            return response()->json('OK');

        }catch(Exception $e){
            Log::error($e);

            return response()->json("OK");
        }
    }


    public function CreateCustomerAccount(Request $request){
        try{
            Log::info($request->all());

            DB::connection("vf_connection")->table('DCB_MONotifications')->insert([
                'merchantId' => $request->filled('merchantId') ? $request->merchantId : '',
                'carrierId' => $request->filled('carrierId') ? $request->carrierId : '',
                'shortCode' => $request->filled('shortCode') ? $request->shortCode : '',
                'accountIdType' => $request->filled('shortCode') ? (array_key_exists('accountIdType', $request->accountInfo) ? $request->accountInfo['accountIdType'] : '') : '',
                'accountId' => $request->filled('shortCode') ? (array_key_exists('accountId', $request->accountInfo) ? $request->accountInfo['accountId'] : '' ) : '',
                'msisdn' => $request->filled('shortCode') ? (array_key_exists('accountId', $request->accountInfo) ? $request->accountInfo['accountId'] : '') : '',
                'smsText' => $request->filled('message') ? $request->message : '',
                'carrierTransactionId' => $request->filled('carrierTransactionId') ? $request->carrierTransactionId : '',
                'actionType' => 'ADD',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $msisdn = array_key_exists('accountId', $request->accountInfo) ? $request->accountInfo['accountId'] : '';

            switch($request->shortCode){
                case '4063': //allowed promo shortcode
                case '625001': //allowed promo shortcode
                    $textMsg = "START";
                    if($request->filled('smsText')){
                        $textMsg = strtoupper($request->message);
                    }
                    
                    ProcessVFPromoMORequest::dispatch($msisdn, $request->carrierTransactionId, $request->shortCode,  $textMsg)->onConnection('redis');
                    Log::info("Promo Called");
                    break;
                    return response()->json('OK');
    
                default:
                    Log::info('DCB: Shortcode Not Recognized => '.$request->shortCode);
                    return response()->json('OK');
            }

            return response()->json('OK');

        }catch(Exception $e){
            Log::error($e);

            return response()->json("OK");
        }
    }

    public function PromoMO(Request $request){
        try{
            Log::info($request->all());

            $msisdn = '';
            $textMsg = 'INFO';
            $shortCode = '4063';
            $carrierTransactionId ='';


            $inboundSMSMessageNotification = $request->inboundSMSMessageNotification;
            if(array_key_exists('inboundSMSMessage', $inboundSMSMessageNotification)){
                $data = $inboundSMSMessageNotification['inboundSMSMessage'];
                $msisdn = array_key_exists('senderAddress', $data) ? $data['senderAddress']  : '';
                $textMsg = array_key_exists('message', $data) ? $data['message'] : '';
                $carrierTransactionId = array_key_exists('messageId', $data) ? $data['messageId'] : '';
                $shortCode = array_key_exists('destinationAddress', $data) ? $data['destinationAddress'] : 'short:4063';
                DB::connection("vf_connection")->table('DCB_MONotifications')->insert([
                    'merchantId' => array_key_exists('messageId', $data) ? $data['messageId'] : '',
                    'carrierId' => array_key_exists('dcs', $data) ? $data['dcs'] : '',
                    'shortCode' => array_key_exists('destinationAddress', $data) ? $data['destinationAddress'] : '',
                    'accountIdType' => 'MSISDN',
                    'accountId' =>  array_key_exists('dcs', $data) ? $data['dcs'] : '',
                    'msisdn' =>  array_key_exists('senderAddress', $data) ? $data['senderAddress']  : '',
                    'smsText' => array_key_exists('message', $data) ? $data['message'] : '',
                    'carrierTransactionId' => array_key_exists('messageId', $data) ? $data['messageId'] : '',
                    'actionType' => 'MO',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }


            

            
            switch($shortCode){
                case '4063': //allowed promo shortcode
                case 'short:4063': //allowed promo shortcode
                    // $textMsg = "";
                    // if($request->filled('smsText')){
                    //     $textMsg = strtoupper($request->message);
                    // }
                    
                    ProcessVFPromoMORequest::dispatch($msisdn, $carrierTransactionId, $shortCode,  $textMsg)->onConnection('redis');
                    Log::info("Promo Called");
                    break;
                    return response()->json('OK');
    
                default:
                    Log::info('DCB: Shortcode Not Recognized => '.$request->shortCode);
                    return response()->json('OK');
            }

            return response()->json('OK');

        }catch(Exception $e){
            Log::error($e);

            return response()->json("OK");
        }
    }

    public function DeleteCustomerAccount(Request $request){
        try{
            DB::connection("vf_connection")->table('DCB_MONotifications')->insert([
                'merchantId' => null,
                'carrierId' => $request->carrierId,
                'shortCode' => $request->shortCode,
                'accountIdType' => $request->accountIdType,
                'accountId' => $request->accountId,
                'msisdn' => $request->accountId,
                'smsText' => 'STOPTWP',
                'carrierTransactionId' => null,
                'actionType' => "DELETE"
            ]);

            switch($request->shortCode){
                case '4063': //allowed promo keywords
                case 4063: //allowed promo keywords
                case 'short:4063':
                    PromoMechanics::processRequest($request->accountId, null, $request->shortCode,  'STOP');
                    break;
    
                default:
                    Log::info('DCB: Shortcode Not Recognized => '.$request->shortCode);
                    return response()->json('OK');
            }

            return response()->json('OK');

        }catch(Exception $e){
            Log::error($e);

            return response()->json("OK");
        }
    }


    public function sendTestMT(){

        try{
            $messageid = Str::orderedUuid();
            $smssending = SMSSending::create([
                'address' => '233204052513',
                'senderAddress' => '4063',
                'outboundSMSTextMessage' => "Test Message",
                'clientCorrelator' => $messageid,
                'notifyURL' => 'https://vftelenity.elcutoconsult.com/api/sms-callback',
                'senderName' => 'ELCUTO',
                'callbackData' => $messageid
            ]);

            $sendsms = new SMSMessaging();

            $result = $sendsms->sendContentToCustomer($smssending, '1252');
            $deliveryInfo = $result['data'];

            return response()->json([
                'status' => 'success',
                'message' => 'SMS Queued',
                'data' => $deliveryInfo
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ]);
        }
        
    }
}
