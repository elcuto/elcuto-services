<?php

namespace App\Http\Controllers\AT;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessATPromoMORequest;
use App\Jobs\ProcessATRenewalNotification;
use App\TigoGhTimwe\ElcThirdPartySMSService;
use App\TigoGhTimwe\TigoGhTimweService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OptinController extends Controller
{
    public function __construct()
    {
        // Set the default connection for this controller
        DB::setDefaultConnection('at_pgsql');
    }
    public function customerOptin(Request $request, $partnerRole){

        $externalTxnId = $request->header('external-tx-id');
        $transactionUUID = Str::uuid();
        $correlationId = $externalTxnId;
        $resp_message = "Successful";
        $inError = false;
        $requestId = $request->requestIdentifier;
        $resp_code = "";
        try{
            //INSERT USER INTO OPTIN REQUEST TABLE tb_partner_notif_user_optin_request
            DB::table("tb_partner_notif_user_optin_request")->insert([
                  "partner_role" => $partnerRole,
                  "external_txid" => $externalTxnId,
                  "event_insert_date" => date('Y-m-d H:i:s'),
                  "product_id" => $request->productId,
                  "price_point_id" =>  $request->pricepointId,
                  "mcc" => $request->mcc,
                  "mnc" => $request->mnc,
                  "msisdn" => $request->msisdn,
                  "large_account" => $request->largeAccount,
                  "entry_channel" => $request->entryChannel,
                  "transaction_uuid" => $request->transactionUUID,
                  "text" => $request->text,
                  "tags" => json_encode($request->tags)
            ]);

            //CHECK IF USER ALREADY EXISTS  OR ALREADY SUBSCRIBED TO SERVICE
            if(DB::table("tb_timwe_airteltigo_all_subs")->where('product_id', $request->productId)->where('msisdn', $request->msisdn)->exists()){
                //CUSRTOMER EXISTS TO UPDATE IT
                DB::table("tb_timwe_airteltigo_all_subs")->where('product_id', $request->productId)->where('msisdn', $request->msisdn)->update([
                    "event_insert_date" => date("Y-m-d H:i:s"),
                    "last_bill_date" => date("Y-m-d H:i:s"),
                    "keyword" => $request->text,
                    "is_active" => 1
                ]);
            }
            else{
                //DOES NOT EXIST SO INSERT INTO TABLE
                DB::table("tb_timwe_airteltigo_all_subs")->insert([
                    "partner_role" => $partnerRole,
                    "product_id" => $request->productId,
                    "product_name" => "",
                    "price_point_id" => $request->pricepointId,
                    "mcc" => $request->mcc,
                    "mnc" => $request->mnc,
                    "msisdn" => $request->msisdn,
                    "event_insert_date" => date("Y-m-d H:i:s"),
                    "large_account" => $request->largeAccount,
                    "entry_channel" => $request->entryChannel,
                    "keyword" => $request->text,
                    "other_info" => "",
                    "last_bill_date" =>  date("Y-m-d H:i:s"),
                    "is_active" => 1
                ]);
            }


            $data = (object)$request->all();
            $data->requestIdentifier = $request->requestIdentifier;

            //CHECK FOR THE PRODUCT CUSTOMER IS SUBSCRIBING TO
            if(in_array($request->productId, [7530 , 9901, 22997]))//PROMO SERVICES
            {
                ProcessATPromoMORequest::dispatch($data, "START");
            }
            else if($request->productId === 7529)
            {
                ElcThirdPartySMSService::processThirdPartyRequests($data);
            }
            else if($request->productId === 7528)
            {
                ElcThirdPartySMSService::processThirdPartyRequests($data);
            }
            else if($request->productId === 7527)
            {
                ElcThirdPartySMSService::processThirdPartyRequests($data);
            }else{
                if($request->route()->getName() == "user-optin"){
                    TigoGhTimweService::processReceivedNotifications($data, "OPTIN", $transactionUUID);
                }else if($request->route()->getName() == "renew"){
                    ProcessATRenewalNotification::dispatch($data, "RENEWAL", $transactionUUID);
//                    TigoGhTimweService::processReceivedNotifications($data, "RENEWAL", $transactionUUID);
                }else{
                    Log::info("=========== OPTIN NOT RECEIVED ON CORRECT ROUTE ===========");
                    Log::info($request->all());
                    Log::info("=========== END OPTIN NOT RECEIVED ON CORRECT ROUTE ===========");
                }
            }

            return response()->json([
                  "responseData" => [
                      "transactionUUID" => $transactionUUID,
                      "correlationId" => $correlationId
                  ],
                  "message" =>  $resp_message,
                  "inError" => $inError,
                  "requestId" => $requestId,
                  "code" => $resp_code
            ]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                "responseData" => [
                    "transactionUUID" => $transactionUUID,
                    "correlationId" => $correlationId
                ],
                "message" => "Error",
                "inError" => true,
                "requestId" => $requestId,
                "code" => $resp_code
            ]);
        }
    }

    public function customerOptout(Request $request, $partnerRole){
        $externalTxnId = $request->header('external-tx-id');
        $transactionUUID = Str::uuid();
        $correlationId = $externalTxnId;
        $resp_message = "";
        $inError = false;
        $requestId = $request->requestIdentifier;
        $resp_code = "";

        try{
            DB::table("tb_partner_notif_user_optout_request")->insert([
                "partner_role" => $partnerRole,
                "external_txid" => $externalTxnId,
                "event_insert_date" => date('Y-m-d H:i:s'),
                "product_id" => $request->productId,
                "price_point_id" =>  $request->pricepointId,
                "mcc" => $request->mcc,
                "mnc" => $request->mnc,
                "msisdn" => $request->msisdn,
                "large_account" => $request->largeAccount,
                "entry_channel" => $request->entryChannel,
                "transaction_uuid" => $request->transactionUUID,
                "tags" => json_encode($request->tags)
            ]);
            //  UPDATE TIMWE SUBS TABLE
            DB::table("tb_timwe_airteltigo_all_subs")->where("product_id", $request->productId)->where("msisdn", $request->msisdn)->update([
                "is_active" => 0,
            ]);

            $data = (object)$request->all();

            if(in_array($request->productId, [7530, 9901, 22997])) //DEACTIVATION FROM PROMO SERVICE
            {

                ProcessATPromoMORequest::dispatch($data, "STOP");
            }
            else if($request->productId === 7529)
            {
                ElcThirdPartySMSService::processThirdPartyRequests($data, "UNSUBSCRIPTION");
            }
            else if($request->productId === 7528)
            {
                ElcThirdPartySMSService::processThirdPartyRequests($data, "UNSUBSCRIPTION");
            }
            else if($request->productId === 7527)
            {
                ElcThirdPartySMSService::processThirdPartyRequests($data, "UNSUBSCRIPTION");
            }else{
                TigoGhTimweService::processReceivedNotifications($data, "OPTOUT", $transactionUUID);
            }

            return response()->json([
                "responseData" => [
                    "transactionUUID" => $transactionUUID,
                    "correlationId" => $correlationId
                ],
                "message" =>  $resp_message,
                "inError" => $inError,
                "requestId" => $requestId,
                "code" => $resp_code
            ]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                "responseData" => [
                    "transactionUUID" => $transactionUUID,
                    "correlationId" => $correlationId
                ],
                "message" => "Error",
                "inError" => true,
                "requestId" => $requestId,
                "code" => $resp_code
            ]);
        }
    }

}
