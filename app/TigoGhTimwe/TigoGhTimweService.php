<?php

namespace App\TigoGhTimwe;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TigoGhTimweService{


    public static function processReceivedNotifications(Request $request, string $notificationType, $transactionUUID)
    {
        $service = DB::connection("at_pgsql")->table("tb_timwe_services")
                            ->select("sub_table_name", "msg_table_name", "shortcode", "prefix", "price_point_id_mt_free", "price_point_id_mo_free")
                            ->where("product_id", $request->productId)->first();

        switch ($notificationType){
            case 'OPTIN':
            case 'RENEWAL':
                Log::info("TABLE  NAME".$service->sub_table_name);
                $exists = DB::connection('test_db_pgsql')->table($service->sub_table_name)
                            ->where("subscriber", $request->msisdn)->first();
                if(!$exists){
                    DB::connection('test_db_pgsql')->table($service->sub_table_name)->insert([
                        "subscriber" => $request->msisdn,
                        "regdate" => date("Y-m-d H:i:s")
                    ]);
                }

                //GET MESSAGE FOR THE DAY AND SEND TO SUBSCRIBER
                $messageForDay = DB::connection('test_db_pgsql')->table($service->msg_table_name)
                                    ->whereDate("date", date("Y-m-d"))
                                    ->first();
                if($messageForDay != null){
                    $morningTime = Carbon::createFromTime(06, 00, 0);
                    $eveningTime = Carbon::createFromTime(22, 00, 0);
                    $nowTime = Carbon::now()->format('H:i:s');
                    $messageSendTime = Carbon::createFromTime(8, 00, 0);

                    if($notificationType == "OPTIN")
                    {
                        DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
                            "msisdn" => $request->msisdn,
                            "message" => $messageForDay->message,
                            "shortcode" => $request->shortcode,
                            "productid" => $request->productId,
                            "pricepointid" => $request->pricepointId,
                            "motransactionuuid" => $transactionUUID
                        ]);
                    }
                    else if(($nowTime >= $morningTime) && ($nowTime <= $eveningTime))
                    {
                        DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
                            "msisdn" => $request->msisdn,
                            "message" => $messageForDay->message,
                            "shortcode" => $request->shortcode,
                            "productid" => $request->productId,
                            "pricepointid" => $request->pricepointId,
                            "motransactionuuid" => $transactionUUID,
                            "datetosend" => $messageSendTime
                        ]);
                    }else {
                        DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
                            "msisdn" => $request->msisdn,
                            "message" => $messageForDay->message,
                            "shortcode" => $request->shortcode,
                            "productid" => $request->productId,
                            "pricepointid" => $request->pricepointId,
                            "motransactionuuid" => $transactionUUID,
                            "priority" => -1
                        ]);
                    }
                }else{
                        Log::info("APP_INFO: Message for day has not been set for " .$service->msg_table_name." for ".date("Y-m-d"));
                }
                break;
            case "OPTOUT":
                DB::connection("test_db_pgsql")->table($service->sub_table_name)->where("subscriber", $request->msisdn)->delete();
                break;
        }
    }
}
