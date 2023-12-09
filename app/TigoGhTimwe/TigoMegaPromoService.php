<?php

namespace App\TigoGhTimwe;


use App\Events\ATServiceSubscriptionEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;


class TigoMegaPromoService
{
    public static $default_point = 50;
    private static $default_sub_type = "MO";

    private static $product_id = 22997;

    //pricepoint ids
    private static $two_cedis = "";

    private static string $fifty_pes = "59531";

    private static string $thirty_pes = "";

    private static string $twenty_pes = "";

    private static string $ten_pes = "59531";

    private static string $free_mt = "42927";

    private static string $help_message = "Text CASH to 4062, participate in the questions & answers, & win big! Your share of the weekly and monthly cash prizes awaits you! Only 20pGp/SMS.";

    private static string $unsubscription_message ="";

    private static string $welcome_message = "Welcome to PLAY 4 CASH PROMO! Awesome cash rewards await you. Text your answers to 4062.Text STOP to exit. 20Gp/sms. Answer more to win big!";

    /**
     * @param object $request
     * @param $actionType
     * @return void
     */
    public static function processMsgRcvd(object $request, string $actionType="START") : void
    {

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
            if($exists == null || count($exists) <= 0){
                //customer is a new subscriber and does not exist in the system
                if(in_array("START", $text_messages)){

                    //insert into promo_subs as new subscriber
                    self::insertIntoPromoSubs($request->msisdn, $request->largeAccount, self::$default_sub_type);

                    //sends the welcome message
                    self::sendMessage($request->msisdn, $request->largeAccount, self::$welcome_message, self::$product_id, self::$free_mt,1, $request->requestIdentifier);

                    //you can equally send a billed welcome message here
                    // self::sendBillingMessage($request->msisdn, $request->largeAccount, self::$welcome_message, self::$product_id, self::$fifty_pes,1);

                    //Get the first question for subscriber and send
                    $message = DB::connection("at_mega_promo")->table("at_promo_msg")->orderBy("id", "DESC")->first();
                    if($message != null){
                        self::sendMessage($request->msisdn, $request->largeAccount, self::formatQuestion($message->question), self::$product_id, self::$fifty_pes, 1, $request->requestIdentifier);
                    }else{
                        Log::info("No Questions Found in Database For Promo. Load The Questions Now");
                    }
                }
                else if (in_array("INFO", $text_messages) || in_array("HELP", $text_messages))
                {
                    $messToSend = "Text CASH to 4062, participate in the questions & answers, & win big! Amazing cash rewards await you! Only 20Gp/SMS.";
                    self::sendMessage($request->msisdn, $request->largeAccount, $messToSend, 22997, 42927, -2, $request->requestIdentifier );
                }
                else if (in_array("MT", $text_messages) || in_array("SUB", $text_messages))
                {
                    DB::connection("at_mega_promo")->table("at_promo_subs")->insert([
                        "msisdn" => $request->msisdn,
                        "reg_date" => date("Y-m-d H:i:s"),
                        "points" => self::$default_point,
                        "week_points" => self::$default_point,
                        "total_points" => self::$default_point,
                        "play_count" => 0,
                        "sub_type" => "MT",
                        "created_at" => now(),
                        "updated_at" => now()
                    ]);

                    $messToSend = "Welcome to PLAY 4 CASH PROMO! Awesome cash rewards await you. Text your answers to 4062.Text STOP to exit. 20Gp/sms. Answer more to win big!";
                    self::sendMessage($request->msisdn, $request->largeAccount, $messToSend, self::$free_mt, self::$free_mt, -2, $request->requestIdentifier );
                }
                else if(in_array("STOP", $text_messages) || in_array("STOPP", $text_messages)){
                    $messToSend = "You have not subscribed to the PLAY 4 CASH!. Text CASH to 4062 to join and win big.";
                    self::sendMessage($request->msisdn, $request->largeAccount, $messToSend, self::$product_id, self::$free_mt, -2, $request->requestIdentifier);
                }
                else{
                    //When customer is not part of the promo but sends any keywork to the shortcode
                    //1. subscribe the customer to the service at timwe
                    self::subscribeCustomer($request->msisdn, $request->largeAccount, $actionType, 1);

                    //2. send billed welcome message to the subscriber
                    $welcomeMessage = "Welcome to PLAY 4 CASH PROMO! Win your share of weekly and monthly cash prizes.Simply text CASH to 4062 now.Text costs 20Gp/sms and STOP to exit.";
                    self::sendBillingMessage($request->msisdn, $request->largeAccount,$welcomeMessage, self::$product_id, self::$ten_pes, 1, $request->requestIdentifier);

                    //3. insert into promo subs
                    self::insertIntoPromoSubs($request->msisdn, $request->largeAccount);

                    //4. select the first message for subscriber to answer
                    //Get the first question for subscriber and send
                    $message = DB::connection("at_mega_promo")->table("at_promo_msg")->orderBy("id", "DESC")->first();
                    if($message != null){
                        self::sendMessage($request->msisdn, $request->largeAccount, self::formatQuestion($message->question), self::$product_id, self::$fifty_pes, 1, $request->requestIdentifier);
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
                        //put customer on MT subscription
                        DB::connection("at_mega_promo")->table("at_promo_subs")->where("msisdn", $request->msisdn)->update([
                            "sub_type" => "MT"
                        ]);

                        //send MT subscription message to customer
                        $mtSubMessage = "Welcome to MT subscription on  PLAY 4 CASH PROMO! Receive promo tips @ 20Gp daily and 100 daily points to boost your chances. Text STOP to 4062  to exit.";
                        self::sendMessage($request->msisdn, $request->largeAccount,$mtSubMessage, self::$product_id, self::$free_mt, 1, $request->requestIdentifier);

                        //get subscribers next question
                        $customer = DB::connection("at_mega_promo")->table("at_promo_subs")->where("msisdn", $request->msisdn)->first();

                        $message = DB::connection("at_mega_promo")->table("at_promo_msg")->where("id", ">", $customer->qid)->orderBy("id", "DESC")->first();

                        if($message != null){
                            self::sendBillingMessage($request->msisdn, $request->largeAccount, self::formatQuestion($message->question), self::$product_id, self::$fifty_pes, 1, $request->requestIdentifier);
                        }else{
                            Log::info("No Questions Found for sending. FROM : MT subscription");
                        }

                    }
                    else if(in_array("INFO", $text_messages) || in_array("HELP", $text_messages)){
                        //customer requesting for help or info about the promo
                        self::sendMessage($request->msisdn, $request->largeAccount, self::$help_message, self::$product_id, self::$free_mt, 2, $request->requestIdentifier);
                    }
                    else if(in_array("STOP", $text_messages) || in_array("EXIT", $text_messages)){
                        //customer wants to unsubscribe from the service
                        DB::connection("at_mega_promo")->table("at_promo_subs")->where([
                            "msisdn" => $request->msisdn,
                        ])->update([
                            "state" => 0,
                            "points" => 0,
                            "week_points" => 0,
                            "total_points" => 0,
                        ]);
                        //Send unsubscription message after unsubscribing
                        self::sendMessage($request->msisdn, $request->largeAccount, self::$unsubscription_message, self::$product_id, self::$free_mt, 1, $request->requestIdentifier);
                    }
                    else if(in_array("POINTS", $text_messages) || in_array("POINT", $text_messages)){
                        //get the customers points
                        $customer_point = DB::connection("at_mega_promo")->table("at_promo_subs")->where([
                            "msisdn" => $request->msisdn,
                        ])->select("total_points")->first();
                        $point_message = "Wow! You have accumulated ".$customer_point->total_points." points.  Continue answering more questions to increase your points & be one of our daily winners.";
                        self::sendMessage($request->msisdn, $request->largeAccount, $point_message, self::$product_id, self::$free_mt, 1, $request->requestIdentifier);
                    }
                   else{

                       $nextQuestion = "";
                       $awdPoint = 0;
                       $respAnswer = "";

                       if($subscriber->quest_count > 50000000000)
                       {
                           //CUSTOMER HAS REACHED PARTICIPATING LIMIT FOR DAY
                           $limit_message = "You have ".$subscriber->total_points.". You have reached your participation limit for the TEXT TO WIN PROMO. To continue text START to 4063";
                           self::sendMessage($request->msisdn, $request->largeAccount, $limit_message, self::$product_id, self::$free_mt, 1, $request->requestIdentifier);
                           return;
                       }
                       else if(in_array(trim($actionType), ["A", "B"])){
                           //customer is answering questions
                           //get the customers last question
                           $msg = DB::connection("at_mega_promo")->table('at_promo_msg')
                               ->where('id', $subscriber->qid)->first();


                           if($msg != null){
                               $dt = Carbon::now();
                               if(in_array($dt->dayOfWeek, [0,6])) {  // 0-SUNDAY OR 6-SATURDAY
                                   $currTime = explode(" ", $dt->toDateTimeString())[1];

                                   if($currTime < '12:00:00'){
                                       if(strtoupper(trim($msg->answer)) == strtoupper(trim($actionType))){
                                           $awdPoint = 100;
                                           $respAnswer = 'Great! U got 100pts for that.';
                                       }else{
                                           $awdPoint = 50;
                                           $respAnswer = 'Wrong! U got 50pts for that.';
                                       }
                                   }
                                   else if(($currTime > '12:00:00' && $currTime < '13:59:59') || ($currTime > '18:00:00' && $currTime < '19:59:59')){
                                       //HAPPY HOUR TIME FOR WEEKENDS
                                       if((strtoupper(trim($msg->answer)) == strtoupper(trim($actionType)))){
                                           $awdPoint = 100;
                                           $respAnswer = 'Great! U got 100pts for that.';
                                       }else{
                                           $awdPoint = 50;
                                           $respAnswer = 'Wrong! U got 50pts for that.';
                                       }
                                   }
                                   else{
                                       if((strtoupper(trim($msg->answers)) == strtoupper(trim($actionType)))){
                                           $awdPoint = 100;
                                           $respAnswer = 'Great! U got 100pts for that.';
                                       }else{
                                           $awdPoint = 50;
                                           $respAnswer = 'Wrong! U got 50pts for that.';
                                       }
                                   }
                               }
                               else{
                                   //IT'S NOT SATURDAY OR SUNDAY
                                   $currTime = explode(" ", $dt->toDateTimeString())[1];
                                   if($currTime < '12:00:00'){
                                       if((strtoupper(trim($msg->answer)) == strtoupper(trim($actionType))) ){
                                           $awdPoint = 100;
                                           $respAnswer = 'Great! U got 100pts for that.';
                                       }else{
                                           $awdPoint = 50;
                                           $respAnswer = 'Wrong! U got 50pts for that.';
                                       }
                                   }
                                   else if(($currTime > '12:00:00' && $currTime < '13:59:59') || ($currTime > '18:00:00' && $currTime < '19:59:59')){
                                       //HAPPY HOUR TIME FOR WEEKDAYS
                                       if((strtoupper(trim($msg->answer)) == strtoupper(trim($actionType))) ){
                                           $awdPoint = 100;
                                           $respAnswer = 'Great! U got 100pts for that.';
                                       }else{
                                           $awdPoint = 50;
                                           $respAnswer = 'Wrong! U got 50pts for that.';
                                       }
                                   }
                                   else{
                                       if((strtoupper(trim($msg->answer)) == strtoupper(trim($actionType))) ){
                                           $awdPoint = 100;
                                           $respAnswer = 'Great! U got 100pts for that.';
                                       }else{
                                           $awdPoint = 50;
                                           $respAnswer = 'Wrong! U got 50pts for that.';
                                       }
                                   }
                               }
                           }
                       }

                       $nextMsg = DB::connection("at_mega_promo")->table("at_promo_msg")->where("id",'>', $subscriber->qid)->orderBy('id', 'ASC')->first();
                       if($nextMsg == null){
                           $nextMsg = DB::connection("at_mega_promo")->table("at_promo_msg")->orderBy('id', 'ASC')->first();
                       }
                       $nextQuestion = $nextMsg->question;

                       DB::connection("at_mega_promo")->table('at_promo_subs')
                           ->where('msisdn', $request->msisdn)
                           ->update([
                               'points' =>  DB::raw('points + '.$awdPoint),
                               'week_points' =>  DB::raw('week_points + '.$awdPoint),
                               'total_points' =>  DB::raw('total_points + '.$awdPoint),
                               'play_count' =>  DB::raw('play_count + 1'),
                               'qid' =>  $nextMsg->id,
                               'last_rcvd' => date('Y-m-d H:i:s')
                           ]);

                       $the_question = self::formatQuestion("QUESTION: ".$nextQuestion);

                       //send response to answer indicating correctness of answer
                       self::sendMessage($request->msisdn, $request->largeAccount, $respAnswer, self::$product_id, self::$free_mt, 1, $request->requestIdentifier);

                       //send the net question to subscriber
                       self::sendBillingMessage($request->msisdn, $request->largeAccount, $the_question,self::$product_id, self::$fifty_pes, 1, $request->requestIdentifier );
                   }
                }
                else{
                    // subscriber is present but not active member of promo
                    if (in_array("MT", $text_messages) || in_array("SUB", $text_messages)) {
                        //put customer on MT subscription
                        DB::connection("at_mega_promo")->table("at_promo_subs")->where("msisdn", $request->msisdn)->update([
                            "sub_type" => "MT",
                            "state" => 1
                        ]);

                        //subscribe customer again if taken off timwe subscriptions
                        self::subscribeCustomer($request->msisdn, $request->largeAccount, $actionType, 1);

                        //send MT subscription message to customer
                        $mtSubMessage = "Welcome to MT subscription on  PLAY 4 CASH PROMO! Receive promo tips @ 20Gp daily and 100 daily points to boost your chances. Text STOP to 4062  to exit.";
                        self::sendMessage($request->msisdn, $request->largeAccount,$mtSubMessage, self::$product_id, self::$free_mt, 1, $request->requestIdentifier);

                        //get subscribers next question
                        $customer = DB::connection("at_mega_promo")->table("at_promo_subs")->where("msisdn", $request->msisdn)->first();

                        $message = DB::connection("at_mega_promo")->table("at_promo_msg")->where("id", ">", $customer->qid)->orderBy("id", "DESC")->first();

                        if($message != null){
                            self::sendBillingMessage($request->msisdn, $request->largeAccount, self::formatQuestion($message->question), self::$product_id, self::$fifty_pes, 1, $request->requestIdentifier);
                        }else{
                            Log::info("No Questions Found for sending. FROM : MT subscription");
                        }
                    }
                    else if(in_array("INFO", $text_messages) || in_array("HELP", $text_messages)){
                        //customer active but requesting for help or info about the promo
                        $inactive_help_messahe = "Text CASH to 4062, participate in the questions & answers, & win big! Your share of the weekly and monthly cash prizes awaits you! Only 20pGp/SMS.";
                        self::sendMessage($request->msisdn, $request->largeAccount, $inactive_help_messahe, self::$product_id, self::$free_mt, 2, $request->requestIdentifier);
                    }
                    else if(in_array("STOP", $text_messages) || in_array("EXIT", $text_messages)){
                        //customer not active but requesting to stop from the promo
                        $inactive_stop_message = "You have not subscribed to the TEXT 4 CASH PROMO. Text CASH to 4062 to join and win big.";
                        self::sendMessage($request->msisdn, $request->largeAccount, $inactive_stop_message, self::$product_id, self::$free_mt, 2, $request->requestIdentifier);
                    }
                    else if(in_array("POINTS", $text_messages) || in_array("POINT", $text_messages)){
                        //customer not active but requesting to get points on the promo
                        $inactive_point_message = "You have not subscribed to the TEXT 4 CASH PROMO. Text CASH to 4062 to join and win big.";
                        self::sendMessage($request->msisdn, $request->largeAccount, $inactive_point_message, self::$product_id, self::$free_mt, 2, $request->requestIdentifier);
                    }
                    else{
                        // check if its any other keywords, then subscribe the customer back on the service
                        //When customer was part of the promo but sends any keywork to the shortcode
                        //1. subscribe the customer to the service at timwe
                        self::subscribeCustomer($request->msisdn, $request->largeAccount, $actionType, 1);

                        //2. send billed welcome message to the subscriber
                        $welcomeMessage = "Welcome to PLAY 4 CASH PROMO! Win your share of weekly and monthly cash prizes.Simply text CASH to 4062 now.Text costs 20Gp/sms and STOP to exit.";
                        self::sendBillingMessage($request->msisdn, $request->largeAccount,$welcomeMessage, self::$product_id, self::$ten_pes, 1, $request->requestIdentifier);

                        //3. update active status
                        DB::connection("at_mega_promo")->table("at_promo_msg")
                            ->where("msisdn", $request->msisdn)
                            ->update([
                                "state" => 1
                            ]);

                        //4. select the first message for subscriber to answer
                        //Get the first question for subscriber and send
                        $message = DB::connection("at_mega_promo")->table("at_promo_msg")->orderBy("id", "DESC")->first();
                        if($message != null){
                            self::sendMessage($request->msisdn, $request->largeAccount, self::formatQuestion($message->question), self::$product_id, self::$fifty_pes, 1, $request->requestIdentifier);
                        }else{
                            Log::info("No Questions Found in Database For Promo. Load The Questions Now");
                        }
                    }
                }


            }
        }
    }

    /**
     * @param $msisdn
     * @param $shortCode
     * @param $message
     * @param $product_id
     * @param $price_point_id
     * @param $priority
     * @return void
     */
    public static function sendMessage($msisdn, $shortCode, $message, $product_id, $price_point_id, $priority, $requestIdentifier){
       DB::connection("at_pgsql")->table("tb_mt_sms_sending")->insert([
           "msisdn" => $msisdn,
           "message" => $message,
           "shortcode" => $shortCode,
           "product_id" => $product_id,
           "price_point_id" => $price_point_id,
           "priority" => $priority,
           "is_sent" => 0,
           "requested" => date("Y-m-d H:i:s"),
           "date_to_send" => date("Y-m-d H:i:s"),
           "no_of_attempts" => 0,
           "mcc" => "620",
           "mnc" => "03",
           "mo_transaction_uuid" => $requestIdentifier,
           "created_at" => now(),
           "updated_at" => now()
       ]);
    }

    /**
     * @param $msisdn
     * @param $shortCode
     * @param $message
     * @param $product_id
     * @param $price_point_id
     * @param $priority
     * @return void
     */
    public static function sendBillingMessage($msisdn, $shortCode, $message, $product_id, $price_point_id, $priority, $requestIdentifier): void
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
            "mo_transaction_uuid" => $requestIdentifier,
            "no_of_attempts" => 0,
            "created_at" => now(),
            "updated_at" => now()
        ]);
    }

    /**
     * @param $msisdn
     * @param $shortcode
     * @param $text
     * @param $priority
     * @return void
     */
    public static function subscribeCustomer($msisdn, $shortcode, $text, $priority) : void
    {
        $tracking_id = Str::uuid()->toString();
        $subscription_id = DB::connection("")->table("tb_at_service_subscriptions")->insertGetId([
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
            "send_date" => date("Y-m-d H:i:s"),
            "created_at" => now(),
            "updated_at" => now()
        ]);

        // dispatch the subscription event that will send the request to timwe
        event(new ATServiceSubscriptionEvent($subscription_id));
    }

    /**
     * @param $msisdn
     * @param $shortcode
     * @return void
     */
    public static function insertIntoPromoSubs($msisdn, $shortcode, $sub_type="MO") : void
    {
        DB::connection("at_mega_promo")->table("at_promo_subs")->insert([
            "msisdn" => $msisdn,
            "reg_date" => date("Y-m-d H:i:s"),
            "points" => self::$default_point,
            "week_points" => self::$default_point,
            "total_points" => self::$default_point,
            "play_count" => 0,
            "sub_type" => $sub_type,
            "created_at" => now(),
            "updated_at" => now()
        ]);
    }

    /**
     * @param string $message
     * @return string
     */
    public static function formatQuestion(string $message) : string
    {
        return $message."; Reply with A or B only";
    }



}
