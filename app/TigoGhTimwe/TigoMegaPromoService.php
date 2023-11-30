<?php

namespace App\TigoGhTimwe;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class TigoMegaPromoService
{
    public static $default_point = 50;
    private static $default_sub_type = "MO";

    private static $product_id = 22997;

    //pricepoint ids
    private static $two_cedis = "";

    private static $fifty_pes = "59531";

    private static $thirty_pes = "";

    private static $twenty_pes = "";

    private static $ten_pes = "59531";

    private static $free_mt = "42927";

    public static function processMsgRcvd(Request $request, $actionType="START"){

        $text_messages = explode(" ", strtoupper($actionType));
        //check if promo is active
        if(config('at.promo_active') === false){
            //specify message to send to subscriber
            DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
                "msisdn" => "",
                "message" => "Thank you, the PLAY 4 CASH PROMO has ended. Text FACTS to 4060 to receive daily facts about the world. Thank you','4062",
                "shortcode" => $request->largeAccount,
                "product_id" => self::$product_id,
                "price_point_id" => self::$free_mt,
                "priority" => 1
            ]);
            DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
                "msisdn" => "",
                "message" => "Text FACTS to 4060 to learn more Facts about the world at 20p daily.",
                "shortcode" => $request->largeAccount,
                "product_id" => 22997,
                "price_point_id" => self::$free_mt,
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
                    DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
                        "msisdn" => "",
                        "message" => "Welcome to PLAY 4 CASH PROMO! Awesome cash rewards await you. Text your answers to 4062.Text STOP to exit. 20Gp/sms. Answer more to win big!",
                        "shortcode" => $request->largeAccount,
                        "product_id" => self::$product_id,
                        "price_point_id" => self::$free_mt,
                        "priority" => 1
                    ]);

                    //Get the first question for subscriber and send
                    $message = DB::connection("at_mega_promo")->table("at_promo_msg")->orderBy("id", "DESC")->first();
                    if($message != null){
                        self::sendMessage($request->msisdn, $request->largeAccount, self::formatQuestion($message->question), self::$product_id, self::$fifty_pes, 1);
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
                    self::sendMessage($request->msisdn, $request->largeAccount, $messToSend, self::$free_mt, self::$free_mt, -2 );
                }
                else if(in_array("STOP", $text_messages) || in_array("STOPP", $text_messages)){
                    $messToSend = "You have not subscribed to the PLAY 4 CASH!. Text CASH to 4062 to join and win big.";
                    self::sendMessage($request->msisdn, $request->largeAccount, $messToSend, self::$product_id, self::$free_mt, -2 );
                }
                else{
                    //When customer is not part of the promo but sends any keywork to the shortcode
                    //1. subscribe the customer to the service at timwe
                    self::subscribeCustomer($request->msisdn, $request->largeAccount, $actionType, 1);

                    //2. send billed welcome message to the subscriber
                    $welcomeMessage = "Welcome to PLAY 4 CASH PROMO! Win your share of weekly and monthly cash prizes.Simply text CASH to 4062 now.Text costs 20Gp/sms and STOP to exit.";
                    self::sendBillingMessage($request->msisdn, $request->largeAccount,$welcomeMessage, self::$product_id, self::$ten_pes, 1);

                    //3. insert into promo subs
                    self::insertIntoPromoSubs($request->msisdn, $request->largeAccount);

                    //4. select the first message for subscriber to answer
                    //Get the first question for subscriber and send
                    $message = DB::connection("at_mega_promo")->table("at_promo_msg")->orderBy("id", "DESC")->first();
                    if($message != null){
                        self::sendMessage($request->msisdn, $request->largeAccount, self::formatQuestion($message->question), self::$product_id, self::$fifty_pes, 1);
                    }else{
                        Log::info("No Questions Found in Database For Promo. Load The Questions Now");
                    }

                }
            }
            // subscriber exists on the promo
            else {

                $subscriber = DB::connection("at_mega_promo")->table("at_promo_subs")->where("msisdn", $request->msisdn)->first();
                if($subscriber->state == 1) { //subscriber is an active member of promo
                    if (in_array("MT", $text_messages) || in_array("SUB", $text_messages)) {

                    }else if(in_array("INFO", $text_messages) || in_array("HELP", $text_messages)){

                    }else if(in_array("STOP", $text_messages) || in_array("EXIT", $text_messages)){

                    }else if(in_array("POINTS", $text_messages) || in_array("POINT", $text_messages)){

                    }
                }
                else{ // subscriber is not active member of promo

                }


            }
        }
    }


    public static function sendMessage($msisdn, $shortCode, $message, $product_id, $price_point_id, $priority){
       DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
           "msisdn" => $msisdn,
           "message" => $message,
           "shortcode" => $shortCode,
           "product_id" => $product_id,
           "price_point_id" => $price_point_id,
           "priority" => $priority
       ]);
    }

    public static function sendBillingMessage($msisdn, $shortCode, $message, $product_id, $price_point_id, $priority): void
    {
        DB::connection("at_pgsql")->table("tb_billing_sending")->insert([
            "msisdn" => $msisdn,
            "message" => $message,
            "shortcode" => $shortCode,
            "is_sent" => 0,
            "product_id" => $product_id,
            "price_point_id" => $price_point_id,
            "priority" => $priority,
            "mcc" => "620",
            "mnc" => "03",
            "date_to_send" => date("Y-m-d H:i:s"),
            "requested" => date("Y-m-d H:i:s"),
            "no_of_attempts" => 0,
        ]);
    }

    public static function subscribeCustomer($msisdn, $shortcode, $text, $priority) : void
    {
        $tracking_id = Str::uuid()->toString();
        DB::connection("")->table("tb_at_service_subscriptions")->insert([
            "user_identifier" => $msisdn,
            "user_identifier_type" => "MSISDN",
            "large_account" => $shortcode,
            "product_id" => self::$product_id,
            "external_trxn_id" => "",
            "mcc" => "620",
            "mnc" => "03",
            "sub_keyword" => $text,
            "tracking_id" => $tracking_id,
            "client_ip" => "52.13.202.149",
            "campaign_url" => "",
            "entry_channel" => "SMS",
            "insert_date" => date("Y-m-d H:i:s"),
            "no_retries" => 0,
            "priority" => $priority,
            "send_date" => date("Y-m-d H:i:s")
        ]);
    }


    public static function insertIntoPromoSubs($msisdn, $shortcode) : void
    {
        DB::connection("at_mega_promo")->table("at_promo_subs")->insert([
            "msisdn" => $msisdn,
            "reg_date" => date("Y-m-d H:i:s"),
            "point" => self::$default_point,
            "week_points" => self::$default_point,
            "total_points" => self::$default_point,
            "play_count" => 0,
            "sub_type" => self::$default_sub_type,
        ]);
    }

    public static function formatQuestion(string $message) : string
    {
        return $message."; Reply with A or B only";
    }



}
