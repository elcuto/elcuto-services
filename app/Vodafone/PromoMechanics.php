<?php


namespace App\Vodafone;

use App\APIServices\SMSMessaging;
use App\Models\SMSSending;
use App\Models\SMSSent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PromoMechanics {

    public static function processRequest($msisdn, $messageId, $shortcode, $message){

        $customer = DB::connection("vf_connection")->connection("vf_connection")->table('PromoSubs')->where('MSISDN', $msisdn)->where('state', 1)->first();

        $messages = explode(" ", strtoupper($message)." ");
        Log::info("MESSGE =>>>>>>>>". $message);
        $mess1 = strtoupper( $messages[0] );
        $mess2 = strtoupper( $messages[1] );

        Log::info("MESSGE =>>>>>>>>". $mess1);
        Log::info("MESSGE =>>>>>>>>". $mess2);


        if($customer ==null){ //user not part of the promo
            
            Log::info("CUSTOMER NOT FOUND");

            if(in_array($mess1, ['HELP', 'INFO']) || in_array($mess2, ['HELP', 'INFO'])){
                //USER NOT PART OF PROMO BUT SENDS HELP TO SHORTCODE
                PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "Text WIN to 4063, participate in the Question & answers, & win big! Amazing cash rewards await you! Only 20Gp/SMS.");
            }
            else if(in_array($mess1, ['STOP','STOPTWP']) || in_array($mess2, ['STOP','STOPTWP']) ){
                //USER NOT PART OF PROMO BUT SENDS STOP TO SHORTCODE
                PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "You have not subscribed to the TEXT TO WIN!. Text WIN to 4063 to join and win big. ");
            }
            else if(in_array($mess1, ['START', 'WIN', 'PLAY']) || in_array($mess2, ['START', 'WIN', 'PLAY'])){ //PROMO KEYWORDS
                //select first question
                $nextMsg = DB::connection("vf_connection")->connection("vf_connection")->table('PromoMsg')->orderBy('Id', 'ASC')->first();

                DB::connection("vf_connection")->connection("vf_connection")->table('PromoSubs')->insert([
                    'MSISDN' => $msisdn,
                    'RegDate' => date('Y-m-d H:i:s'),
                    'Name' => null,
                    'Points' => 0,
                    'WeekPoints' => 0,
                    'TotalPoints' => 0,
                    'playCNT' => 0,
                    'Weekplay' => 0,
                    'Totalplay' => 0,
                    'QID' => $nextMsg != null ? $nextMsg->Id : 1,
                    'LastRcvd' => date('Y-m-d H:i:s'),
                    'QuestCNT' => 0,
                    'State' => 1,
                    'SubType' => "MO",
                    'PlayedLimit' => 0,
                    'Specialword' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                //SEND FIRST QUESTION TO SUB
                PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "Welcome to TEXT TO WIN PROMO! You could win your share of weekly and monthly cash prize. Text your answers to 4063.Text STOP to exit.20Gp/sms.Answer more to win big!");
                $nextMsg = DB::connection("vf_connection")->table('PromoMsg')->orderBy('Id', 'ASC')->first();
                PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "QUESTION: ".$nextMsg->Question."; Reply with A or B only");
            }
            else {
                //SEND USER SUBSCRIPTION MESSAGE
                PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "You are not part of the TEXT TO WIN PROMO. Text WIN to 4063 to Join and win BIG");
            }
            
        }
        else{  //user already taking part in the promo

            Log::info("CUSTOMER FOUND");

            if(in_array($mess1, ['HELP', 'INFO']) || in_array($mess2, ['HELP', 'INFO'])){
                //USER PART OF PROMO AND SENDS HELP TO SHORTCODE
                PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "Text MORE to win BIG. ");
            }
            else if(in_array($mess1, ['START', 'WIN', 'PLAY']) || in_array($mess2, ['START', 'WIN', 'PLAY'])){
                //USER PART OF PROMO AND SENDS HELP TO SHORTCODE
                // PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "You  are already participating in TEXT TO WIN Promo. Text A or B to 4063 to answer a question and win BIG");

                $nextMsg = DB::connection("vf_connection")->table('PromoMsg')->where('Id','>', $customer->QID)->orderBy('Id', 'ASC')->first();
                if(!$nextMsg){
                    $nextMsg = DB::connection("vf_connection")->table('PromoMsg')->orderBy('Id', 'ASC')->first();
                }
                DB::connection("vf_connection")->table('PromoSubs')->where('MSISDN', $msisdn)->where('state', 1)->update([
                    'QID' => $nextMsg->Id
                ]);
                $nextQuestion = $nextMsg->Question;
                PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "QUESTION: ".$nextQuestion."; Reply with A or B only");
            }
            else if(in_array($mess1, ['STOP','STOPTWP']) || in_array($mess2, ['STOP','STOPTWP'])){
                //USER PART OF PROMO AND SENDS STOP TO SHORTCODE
                DB::connection("vf_connection")->table('PromoSubs')->where('MSISDN', $msisdn)->where('state', 1)->update([
                    'state' => 0,
                    'TotalPoints' => 0,
                    'playCNT' => 0,
                    'Weekplay' => 0,
                    'Totalplay' => 0
                ]);
                PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "You have unsubscribed from TEXT TO WIN promo. You'll lose your chances  of winning amazing prizes. Text WIn to 4063 to join again");
            }
            else if(in_array($mess1, ['POINT', 'POINTS']) || in_array($mess2, ['POINT', 'POINTS'])){
                //USER PART OF PROMO BUT SENDS HELP TO SHORTCODE
                if(in_array($mess2, ['DAY', 'TODAY'])) {
                    $points = $customer->Points;
                    $pointMessage = "Wow! You  have accummulated ".$points." points today in the TEXT TO WIN Promo. Text MORE to win BIG";
                    PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, $pointMessage);
                }
                else if(in_array($mess2, ['WEEK'])){
                    $wkPoints = $customer->WeekPoints;
                    $pointMessage = "Wow! You  have accummulated ".$wkPoints." points for the week in the TEXT TO WIN Promo. Text MORE to win BIG";
                    PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, $pointMessage);
                }
                else {
                    $points = $customer->Points;
                    $wkPoints = $customer->WeekPoints;
                    $totalPoints = $customer->TotalPoints;
                    $pointMessage = "Wow! You  have accummulated ".$points." points today,  ".$wkPoints." points For the week and ".$totalPoints." Total Points in the TEXT TO WIN Promo. Text MORE to win BIG";
                    PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, $pointMessage); 
                }
               
            }
            else {
                

                 if($customer->QuestCNT > 50000000000){
                    //CUSTOMER HAS REACHED PARTICIPATING LIMIT FOR DAY
                    $totalPoints = $customer->TotalPoints;
                    PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "You have ".$totalPoints." You have reached your participation limit for the TEXT TO WIN PROMO. To continue text START to 4063");
                 }
                 
                 else if(in_array($mess1, ['A','B']) || in_array($mess2, ['A','B'])){
                    $msg = DB::connection("vf_connection")->table('PromoMsg')->where('Id', $customer->QID)->first();
                    if($msg){
                        $nextQuestion = '';
                        $awdPoint = 0;
                        $respAnswer = '';

                        $dt = Carbon::now();
                        if(in_array($dt->dayOfWeek, [0,6])){  // 0-SUNDAY OR 6-SATURDAY
                            $currTime = explode(" ", $dt->toDateTimeString())[1];
                            if($currTime < '12:00:00'){  
                                if(strtoupper(trim($msg->Answers)) == strtoupper(trim($mess1))){
                                    $awdPoint = 100;
                                    $respAnswer = 'Great! U got 100pts for that.';
                                }else{
                                    $awdPoint = 50;
                                    $respAnswer = 'Wrong! U got 50pts for that.';
                                }
                            }
                            else if(($currTime > '12:00:00' && $currTime < '13:59:59') || ($currTime > '18:00:00' && $currTime < '19:59:59')){
                                //HAPPY HOUR TIME FOR WEEKENDS
                                if((strtoupper(trim($msg->Answers)) == strtoupper(trim($mess1))) || strtoupper(trim($msg->Answers)) == strtoupper(trim($mess2))){
                                    $awdPoint = 100;
                                    $respAnswer = 'Great! U got 100pts for that.';
                                }else{
                                    $awdPoint = 50;
                                    $respAnswer = 'Wrong! U got 50pts for that.';
                                }
                            }else{
                                if((strtoupper(trim($msg->Answers)) == strtoupper(trim($mess1))) || strtoupper(trim($msg->Answers)) == strtoupper(trim($mess2))){
                                    $awdPoint = 100;
                                    $respAnswer = 'Great! U got 100pts for that.';
                                }else{
                                    $awdPoint = 50;
                                    $respAnswer = 'Wrong! U got 50pts for that.';
                                }
                            }
                        }else{  //ITS NOT SATURDAY OR SUNDAY
                            $currTime = explode(" ", $dt->toDateTimeString())[1];
                            if($currTime < '12:00:00'){  
                                if((strtoupper(trim($msg->Answers)) == strtoupper(trim($mess1))) || strtoupper(trim($msg->Answers)) == strtoupper(trim($mess2))){
                                    $awdPoint = 100;
                                    $respAnswer = 'Great! U got 100pts for that.';
                                }else{
                                    $awdPoint = 50;
                                    $respAnswer = 'Wrong! U got 50pts for that.';
                                }
                            }
                            else if(($currTime > '12:00:00' && $currTime < '13:59:59') || ($currTime > '18:00:00' && $currTime < '19:59:59')){
                                //HAPPY HOUR TIME FOR WEEKDAYS
                                if((strtoupper(trim($msg->Answers)) == strtoupper(trim($mess1))) || strtoupper(trim($msg->Answers)) == strtoupper(trim($mess2))){
                                    $awdPoint = 100;
                                    $respAnswer = 'Great! U got 100pts for that.';
                                }else{
                                    $awdPoint = 50;
                                    $respAnswer = 'Wrong! U got 50pts for that.';
                                }
                            }else{
                                if((strtoupper(trim($msg->Answers)) == strtoupper(trim($mess1))) || strtoupper(trim($msg->Answers)) == strtoupper(trim($mess2))){
                                    $awdPoint = 100;
                                    $respAnswer = 'Great! U got 100pts for that.';
                                }else{
                                    $awdPoint = 50;
                                    $respAnswer = 'Wrong! U got 50pts for that.';
                                }
                            }
                        }

                        $nextMsg = DB::connection("vf_connection")->table('PromoMsg')->where('Id','>', $customer->QID)->orderBy('Id', 'ASC')->first();
                        if(!$nextMsg){
                            $nextMsg = DB::connection("vf_connection")->table('PromoMsg')->orderBy('Id', 'ASC')->first();
                        }
                        $nextQuestion = $nextMsg->Question;

                        DB::connection("vf_connection")->table('PromoSubs')
                            ->where('MSISDN', $msisdn)
                            ->increment('Points', $awdPoint);
                        DB::connection("vf_connection")->table('PromoSubs')
                            ->where('MSISDN', $msisdn)
                            ->increment('WeekPoints', $awdPoint);
                        DB::connection("vf_connection")->table('PromoSubs')
                            ->where('MSISDN', $msisdn)
                            ->increment('TotalPoints', $awdPoint);
                        DB::connection("vf_connection")->table('PromoSubs')
                            ->where('MSISDN', $msisdn)
                            ->increment('playCNT', 1);
                        DB::connection("vf_connection")->table('PromoSubs')
                            ->where('MSISDN', $msisdn)->update([
                                'QID' => $nextMsg->Id,
                                'LastRcvd' => date('Y-m-d H:i:s')
                            ]);
                            

                        try{
                        $points = $customer->Points;
                        $wkPoints = $customer->WeekPoints;
                        $totalPoints = $customer->TotalPoints;
                        $pointMessage = $respAnswer.`\n\n`."POINTS: You  have accummulated ".$points." points today,  ".$wkPoints." points For the week and ".$totalPoints." Total Points in the TEXT TO WIN Promo. Text MORE to win BIG";
                        PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, $pointMessage);
                        PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "QUESTION: ".$nextQuestion."; Reply with A or B only");
                        }catch(Exception $e){
                            Log::error("=====PROMO MECHANICS========");
                            Log::error($e);
                        }
                    }else{
                        $nextMsg = DB::connection("vf_connection")->table('PromoMsg')->where('Id','>', $customer->QID)->orderBy('Id', 'ASC')->first();
                        if(!$nextMsg){
                            $nextMsg = DB::connection("vf_connection")->table('PromoMsg')->orderBy('Id', 'ASC')->first();
                        }
                        $respAnswer = "Great! Your next question";
                        $nextQuestion = $nextMsg->Question;
                        DB::connection("vf_connection")->table('PromoSubs')->where('MSISDN', $msisdn)->where('state', 1)->update([
                            'QID' => $nextMsg->Id
                        ]);
                        PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, $respAnswer."; QUESTION: ".$nextQuestion."; Reply with A or B only");
                    }
                 }
                 else{
                    PromoMechanics::sendMessage($msisdn, $shortcode, $messageId, "Wrong keyword. Text Points to check your points or Use A or B to answer Question to win big! Only 20Gp/sms.");
                 }

                
            }
        }
    }


    public static function sendMessage($msisdn, $shortcode, $messageid,$messageToSend){
        try{
            $smssending = SMSSending::create([
                'address' => $msisdn,
                'senderAddress' => $shortcode,
                'outboundSMSTextMessage' => $messageToSend,
                'clientCorrelator' => $messageid,
                'notifyURL' => 'https://vftelenity.elcutoconsult.com/api/sms-callback',
                'senderName' => 'ELCUTO',
                'callbackData' => $messageid
            ])->refresh();
            $sendsms = new SMSMessaging();
    
            $result = $sendsms->sendContentToCustomer($smssending, '1252');
            $deliveryInfo = $result['data'];

            if ($result['status'] == 'OK') {
                // $deliveryInfo = $result['data']['deliveryInfoList']->deliveryInfo[0];
                $deliveryInfo = $result['data'];
                // Log::info('========SMS TEXT ERROR==================');
                // Log::info($deliveryInfo);
                // Log::info('========SMS TEXT ERROR==================');
                if(array_key_exists('outboundSMSMessageRequest',$deliveryInfo)){
                    $deliveryInfo = $deliveryInfo['outboundSMSMessageRequest']['deliveryInfoList']['deliveryInfo'][0];
                    SMSSent::create([
                        "address" => $smssending->address,
                        "senderAddress" => $smssending->senderAddress,
                        "outboundSMSTextMessage" => $smssending->outboundSMSTextMessage,
                        "clientCorrelator" => $messageid,
                        "notifyURL" => $smssending->notifyURL,
                        "senderName" => 'ELCUTO',
                        "callbackData" => $messageid,
                        "deliveryStatus" => array_key_exists("deliveryStatus",$deliveryInfo)?$deliveryInfo['deliveryStatus']:null,
                        "requestId" => array_key_exists("requestId",$deliveryInfo)?$deliveryInfo['requestId']:null,
                        "errorCode" => array_key_exists("errorCode",$deliveryInfo)?$deliveryInfo['errorCode']:null,
                        "errorDescription" => array_key_exists("errorDescription",$deliveryInfo)?$deliveryInfo['errorDescription']:null,
                        "sent" => 1
                    ]);

                    $smssending->delete();

                }else{
                    SMSSent::create([
                        "address" => $smssending->address,
                        "senderAddress" => $smssending->senderAddress,
                        "outboundSMSTextMessage" => $smssending->outboundSMSTextMessage,
                        "clientCorrelator" => $messageid,
                        "notifyURL" => $smssending->notifyURL,
                        "senderName" => 'ELCUTO',
                        "callbackData" => $messageid,
                        "deliveryStatus" => array_key_exists("deliveryStatus",$deliveryInfo)?$deliveryInfo['deliveryStatus']:null,
                        "requestId" => array_key_exists("requestId",$deliveryInfo)?$deliveryInfo['requestId']:null,
                        "errorCode" => array_key_exists("code",$deliveryInfo)?$deliveryInfo['code']:null,
                        "errorDescription" => array_key_exists("message",$deliveryInfo)?$deliveryInfo['message']:null,
                        "sent" => 1
                    ]);

                    $smssending->delete();
                    
                    Log::info('========PROMO SEND MESSAGE ERROR==================');
                    Log::info($result);
                    Log::info('========PROMO SEND MESSAGE ERROR==================');
                }

            } else {
                SMSSent::create([
                    "address" => $smssending->address,
                    "senderAddress" => $smssending->senderAddress,
                    "outboundSMSTextMessage" => $smssending->outboundSMSTextMessage,
                    "clientCorrelator" => $messageid,
                    "notifyURL" => $smssending->notifyURL,
                    "senderName" => 'ELCUTO',
                    "callbackData" => $messageid,
                    "deliveryStatus" => array_key_exists("deliveryStatus",$deliveryInfo)?$deliveryInfo['deliveryStatus']:null,
                    "requestId" => array_key_exists("requestId",$deliveryInfo)?$deliveryInfo['requestId']:null,
                    "errorCode" => array_key_exists("code",$deliveryInfo)?$deliveryInfo['code']:null,
                    "errorDescription" => array_key_exists("message",$deliveryInfo)?$deliveryInfo['message']:null,
                    "sent" => 1
                ]);
                $smssending->delete();
                Log::info(',,,,,,,,,,,==================');
                Log::info($result);
                Log::info(',,,,,,,,,,,==================');
            }
            // return $result;
        }catch(Exception $e){
            Log::error($e);
        }
    }
}