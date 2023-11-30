<?php

namespace App\Http\Controllers\AT;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessATPromoMORequest;
use App\TigoGhTimwe\TigoMegaPromoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MONotificationsController extends Controller
{
    public function __construct()
    {
        // Set the default connection for this controller
        DB::setDefaultConnection('at_pgsql');
    }
    public function moReceivedSMS(Request $request, $partnerRole)
    {
        $correlationId = $request->requestIdentifier;
        $transactionUUID = $request->requestIdentifier;
        $resp_code = "";
        $externalTxnId = $request->header('external-tx-id');
        $requestId = $request->requestIdentifier;

        try {
            // INSERT INTO PARTNER NOTIF MO REQUEST TABLE
            DB::table("tb_partner_notif_mo_request")->insert([
                "partner_role" => $partnerRole,
                "external_txid" => $externalTxnId,
                "event_insert_date" => date('Y-m-d H:i:s'),
                "product_id" => $request->productId,
                "price_point_id" => $request->pricepointId,
                "mcc" => $request->mcc,
                "mnc" => $request->mnc,
                "text" => $request->text,
                "msisdn" => $request->msisdn,
                "large_account" => $request->shortcode,
                "transaction_uuid" => $transactionUUID,
                "tags" => json_encode($request->tag)
            ]);


            if(in_array($request->productId, [7530 , 9901, 22997])){
                $actionType = strtoupper($request->text);
                ProcessATPromoMORequest::dispatch($request, $actionType);
            }else{
                Log::info("===========UNDEFINED MO HANDLER===========");
                Log::info($request->all());
                Log::info("===========UNDEFINED MO HANDLER===========");
            }

            return response()->json([
                "responseData" => [
                    "transactionUUID" => $transactionUUID,
                    "correlationId" => $correlationId
                ],
                "message" => "Successful",
                "inError" => false,
                "requestId" => $requestId,
                "code" => $resp_code
            ]);

        } catch (\Exception $e) {
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
