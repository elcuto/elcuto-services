<?php

namespace App\TigoGhTimwe;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TigoMegaPromoService
{
    public static $default_point = 50;
    private static $default_sub_type = "MO";
    public static function processMsgRcvd(Request $request, $actionType="START"){

        $text_messages = explode(" ", strtoupper($actionType));
        //check if promo is active
        if(config('at.promo_active') === false){
            //specify message to send to subscriber
            DB::connection("at_pgsql")->table("mtsmssendingtable")->insert([
                "msisdn" => "",
                "message" => "Thank you, the PLAY 4 CASH PROMO has ended. Text FACTS to 4060 to receive daily facts about the world. Thank you','4062",
                "shortcode" => $request->largeAccount,
                "productid" => 22997,
                "pricepointid" => 42927,
                "priority" => 1
            ]);
            DB::connection("at_pgsql")->table("mtsmssendingtable")->insert([
                "msisdn" => "",
                "message" => "Text FACTS to 4060 to learn more Facts about the world at 20p daily.",
                "shortcode" => $request->largeAccount,
                "productid" => 22997,
                "pricepointid" => 42927,
                "priority" => 1
            ]);
        }
        else  //promo is active
        {
            if(in_array($request->msisdn, ["XXXXXXXXXX"])){
                return ;
            }

            $exists = DB::connection("at_mega_promo")->table("at_promo_subs")->where("msisdn", $request->msisdn)->get();
            if($exists == null || count($exists) > 0){
                //customer is a new subscriber and does not exist in the system
                if(in_array("START", $text_messages)){

                    //insert into promo_subs as new subscriber
                    DB::connection("at_mega_promo")->table("at_promo_subs")->insert([
                        "msisdn" => $request->msisdn,
                        "reg_date" => date("Y-m-d H:i:s"),
                        "point" => self::$default_point,
                        "week_points" => self::$default_point,
                        "total_points" => self::$default_point,
                        "play_count" => 0,
                        "sub_type" => self::$default_sub_type,
                    ]);

                    //sends the welcome message
                    DB::connection("at_pgsql")->table("mtsmssendingtable")->insert([
                        "msisdn" => "",
                        "message" => "Welcome to PLAY 4 CASH PROMO! Awesome cash rewards await you. Text your answers to 4062.Text STOP to exit. 20Gp/sms. Answer more to win big!",
                        "shortcode" => $request->largeAccount,
                        "productid" => 22997,
                        "pricepointid" => 42927,
                        "priority" => 1
                    ]);

                    //Get the first question for subscriber and send
                    $message = DB::connection("at_mega_promo")->table("at_promo_msg")->orderBy("id", "DESC")->first();
                    if($message != null){
                        self::sendMessage($request->msisdn, $request->largeAccount, self::formatQuestion($message->question), 22997, 42927, 1);
                    }else{
                        Log::info("No Questions Found in Database For Promo. Load The Questions Now");
                    }
                }
                else if (in_array("INFO", $text_messages) || in_array("HELP", $text_messages))
                {
                    $messToSend = "Text CASH to 4062, participate in the questions & answers, & win big! Amazing cash rewards await you! Only 20Gp/SMS.";
                    self::sendMessage($request->msisdn, $request->largeAccount, $messToSend, 22997, 42927, -2 );
                }
                else if (in_array("MT", $text_messages) || in_array("SUB", $text_messages))
                {
                    DB::connection("at_mega_promo")->table("at_promo_subs")->insert([
                        "msisdn" => $request->msisdn,
                        "reg_date" => date("Y-m-d H:i:s"),
                        "point" => self::$default_point,
                        "week_points" => self::$default_point,
                        "total_points" => self::$default_point,
                        "play_count" => 0,
                        "sub_type" => "MT",
                    ]);
                    $messToSend = "Welcome to PLAY 4 CASH PROMO! Awesome cash rewards await you. Text your answers to 4062.Text STOP to exit. 20Gp/sms. Answer more to win big!";
                    self::sendMessage($request->msisdn, $request->largeAccount, $messToSend, 22997, 42927, -2 );
                }
                else if(in_array("STOP", $text_messages) || in_array("STOPP", $text_messages)){

                }
            }
        }
    }


    public static function sendMessage($msisdn, $shortCode, $message, $product_id, $price_point_id, $priority){
       DB::connection("at_pgsql")->table("mtsmssendingtable")->insert([
           "msisdn" => $msisdn,
           "message" => $message,
           "shortcode" => $shortCode,
           "productid" => $product_id,
           "pricepointid" => $price_point_id,
           "priority" => $priority
       ]);
    }

    public static function subscribeCustomer($msisdn, ){
        DB::connection("")->table("atservicesubscriptions")->insert([
            "useridentifier" => "",
            "useridentifiertype" => "",
            "largeaccount" => "",
            "productid" => "",
            "externaltrxnid" => "",
            "mcc" => "",
            "mnc" => "",
            "subkeyword" => "",
            "trackingid" => "",
            "clientip" => "",
            "campaignurl" => "",
            "entrychannel" => "",
            "insertdate" => date("Y-m-d H:i:s"),
            "noretries" => 0,
            "priority" => "",
            "senddate" => ""
        ]);
    }

    public static function formatQuestion(string $message) : string
    {
        return $message."; Reply with A or B only";
    }
}
