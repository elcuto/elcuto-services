<?php

namespace App\Http\Controllers\Vodafone;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessVFServiceDeactivation;
use App\Jobs\ProcessVFServiceReactivation;
use App\Jobs\ProcessVFServiceSubscription;
use App\Jobs\ProcessVFServiceSuspension;
use App\Models\Renewal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionNotificationController extends Controller
{
    //Route::any('/mo-notif', [SubscriptionNotificationController::class, 'handleMOCallBack'])->name('mo-callback');
    public function handleMOCallBack(Request $request){
       
        DB::table('MONotifications')->insert([
            'requestDate' => $request->inboundSMSMessageNotification['inboundSMSMessage']['dateTime'],
            'destinationAddress' => $request->inboundSMSMessageNotification['inboundSMSMessage']['destinationAddress'],
            'shortCode' => explode(":", $request->inboundSMSMessageNotification['inboundSMSMessage']['destinationAddress'])[1],
            'messageId' => $request->inboundSMSMessageNotification['inboundSMSMessage']['messageId'],
            'message' => $request->inboundSMSMessageNotification['inboundSMSMessage']['message'],
            'senderAddress' => $request->inboundSMSMessageNotification['inboundSMSMessage']['senderAddress'],
            'dcs' => $request->inboundSMSMessageNotification['inboundSMSMessage']['dcs'],
            'callbackData' => array_key_exists('callbackData',$request->inboundSMSMessageNotification) ? $request->inboundSMSMessageNotification['callbackData'] : '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $message = $request->inboundSMSMessageNotification['inboundSMSMessage']['message'];
        $msisdn = $request->inboundSMSMessageNotification['inboundSMSMessage']['senderAddress'];
        $shortcode = explode(":", $request->inboundSMSMessageNotification['inboundSMSMessage']['destinationAddress'])[1];
        $messageId = $request->inboundSMSMessageNotification['inboundSMSMessage']['messageId'];
        
        
        switch($shortcode){
            // case '4063': //allowed promo keywords
            //     PromoMechanics::processRequest($msisdn, $messageId, $shortcode, $message);
            //     break;

            case '4060':
                return;
            default:
                return;

        }

        // $keywords = ['MC', 'CATH', 'FNI', 'FNB'];

        // $msisdn = $request->inboundSMSMessageNotification['inboundSMSMessage']['senderAddress'];
        // $message = $request->inboundSMSMessageNotification['inboundSMSMessage']['message'];

        // if(strlen(trim($message))<=0){
        //     $defaultMessage = 'Hello Keyword NOT found,
        //     Send any of the following keyword to 4060 to subscribe to our amazing services.
        //     1. START MC 
        //     2. START FNI
        //     3. START DEI
        //     4. START WHYTE
        //     5. START SPORT
        //     6. START SAFETY
        //     7. START CATH
        //     8. START HYGIENE
        //     9. START JOE
        //     10. START FNB
        //     ';
        //     ProcessSMSMessaging::dispatch('233204052513', '4060', $defaultMessage)->onQueue('smssending');
        // }else{
            
        // }

        
        return response()->json('OK');
    }
    public function handleNotificationCallBack(Request $request)
    {
        // Log::info("============Telenity Callback Notification =============");
        // Log::info($request->getContent());
        // Log::info("============Telenity Callback Notification =============");
        if($request->input('state') == 'ACTIVE'){
            Renewal::createRenewal($request);
        }

        // return response()->json('OK-temp');

        switch ($request->input('serviceNotificationType')) {

            /**
             *    New subscription
             */
            case 'SUB':
                    // $this->createSubscription($request);
                    ProcessVFServiceSubscription::dispatch($request->all());
                    return  response()->json([
                        'status' => 'OK',
                        'message' => 'Subscription successfully created'
                    ]);
    
                break;


                /**
                 *    Deactivation 
                 */
            case 'UNSUB':
                    ProcessVFServiceDeactivation::dispatch($request->all());
                    return response()->json([
                        'message' => 'Notification received',
                        'status' => 'OK'
                    ]);
                    
                break;

                /**
                 *  Reactivation Or Renewal
                 */
            case 'REACTIVATE':
            case 'RENEWED':
                ProcessVFServiceReactivation::dispatch($request->all());
                return response()->json([
                            'status' => 'OK',
                            'message' => 'Renewal successfully created'
                        ]);
                break;

                /**
                 * 
                 */
            case 'SUSPENDED':  // Service Suspended for MSISDN
                ProcessVFServiceSuspension::dispatch($request->all());
                return  response()->json([
                    'status' => 'OK',
                    'message' => 'Subscription successfully suspended'
                ]);
                break;

                /**
                 * Unable to Renew
                 */
            case 'NOTRENEWED':  //
                return response()->json('OK');
                break;


            default:
                return response()->json('OK-default');
                break;
                //subscribe user to service
        }
    }

}
