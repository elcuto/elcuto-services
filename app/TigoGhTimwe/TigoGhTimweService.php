<?php

namespace App\TigoGhTimwe;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TigoGhTimweService{


    public static function processReceivedNotifications($request, string $notificationType, $transactionUUID)
    {
        $service = DB::connection("at_pgsql")->table("tb_timwe_services")
                            ->select("sub_table_name","service_name", "msg_table_name", "shortcode", "prefix", "price_point_mt_free", "price_point_mo_free")
                            ->where("product_id", $request->productId)->first();

        switch ($notificationType){
            case 'OPTIN':
            case 'RENEWAL':
                Log::info("TABLE  NAME".$service?->sub_table_name);
                $exists = DB::connection('test_db_pgsql')->table($service?->sub_table_name)
                            ->where("subscriber", $request->msisdn)->first();
                if(!$exists){
                    DB::connection('test_db_pgsql')->table($service?->sub_table_name)->insert([
                        "subscriber" => $request->msisdn,
                        "regdate" => date("Y-m-d H:i:s"),
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]);
                }

                //GET MESSAGE FOR THE DAY AND SEND TO SUBSCRIBER
                $messageForDay = DB::connection('test_db_pgsql')->table($service?->msg_table_name)
                                    ->whereDate("date", date("Y-m-d"))
                                    ->first();
                if($messageForDay != null){
                    $morningTime = Carbon::createFromTime(06, 00, 0);
                    $eveningTime = Carbon::createFromTime(22, 00, 0);
                    $nowTime = Carbon::now()->format('H:i:s');
                    $messageSendTime = Carbon::createFromTime(8, 00, 0);

                    if($notificationType == "OPTIN")
                    {
                        DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insertGetId([
                            "msisdn" => $request->msisdn,
                            "message" => $messageForDay->message,
                            "shortcode" => $request->largeAccount,
                            "product_id" => $request->productId,
                            "price_point_id" => $request->pricepointId,
                            "mo_transaction_uuid" => $transactionUUID,
                            "date_to_send" => $nowTime,
                            "is_sent" => 0,
                            "priority" => 2,
                            "requested" => now(),
                            "mnc" => config("at.mnc"),
                            "mcc" => config("at.mcc"),
                            "entry_channel" => "SMS",
                            "created_at" => now(),
                            "updated_at" => now(),
                        ]);
                    }
                    else if(($nowTime >= $morningTime) && ($nowTime <= $eveningTime))
                    {
                        DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
                            "msisdn" => $request->msisdn,
                            "message" => $messageForDay->message,
                            "shortcode" => $request->largeAccount,
                            "product_id" => $request->productId,
                            "price_point_id" => $request->pricepointId,
                            "mo_transaction_uuid" => $transactionUUID,
                            "date_to_send" => $nowTime,
                            "is_sent" => 0,
                            "priority" => 3,
                            "requested" => now(),
                            "mnc" => config("at.mnc"),
                            "mcc" => config("at.mcc"),
                            "entry_channel" => "SMS",
                            "created_at" => now(),
                            "updated_at" => now(),
                        ]);
                    }else {
                        DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
                            "msisdn" => $request->msisdn,
                            "message" => $messageForDay->message,
                            "shortcode" => $request->largeAccount,
                            "product_id" => $request->productId,
                            "price_point_id" => $request->pricepointId,
                            "mo_transaction_uuid" => $transactionUUID,
                            "date_to_send" => $messageSendTime,
                            "priority" => 3,
                            "is_sent" => 0,
                            "requested" => now(),
                            "mnc" => config("at.mnc"),
                            "mcc" => config("at.mcc"),
                            "entry_channel" => "SMS",
                            "created_at" => now(),
                            "updated_at" => now(),
                        ]);
                    }
                }else{
                        Log::info("APP_INFO: Message for day has not been set for " .$service?->msg_table_name." for ".date("Y-m-d"));
                }
                break;
            case "OPTOUT":
                DB::connection("test_db_pgsql")->table($service?->sub_table_name)->where("subscriber", $request->msisdn)->delete();
                //send an unsubscription message
                DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
                    "msisdn" => $request->msisdn,
                    "message" => "You have successfully unsubcsribed from the ".$service?->service_name.". Text START ".strtoupper($service?->service_name)." to 4060 to subscribe again",
                    "shortcode" => $request->largeAccount,
                    "product_id" => $request->productId,
                    "price_point_id" => $request->pricepointId,
                    "mo_transaction_uuid" => $transactionUUID,
                    "priority" => 3,
                    "is_sent" => 0,
                    "requested" => now(),
                    "date_to_send" => now(),
                    "mnc" => config("at.mnc"),
                    "mcc" => config("at.mcc"),
                    "entry_channel" => "SMS",
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);

                break;
        }
    }
}
