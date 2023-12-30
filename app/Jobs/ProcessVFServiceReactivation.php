<?php

namespace App\Jobs;

use App\Helpers\SMSMessage;
use App\Models\Revenue;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessVFServiceReactivation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public  $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //==========UPDATE REVENUE TABLE WITH NEW RENEWALS
        // Log::info($this->request);
        $date = Carbon::parse($this->request['lastRenewalDate'])->format('Y-m-d');
        $revenue = Revenue::where('date', $date)->first();
        if($revenue){
            $revenue->update([
                'amount' => (float)$revenue->amount + (float)$this->request['chargedAmount'],
                'count' => (int)$revenue->count + 1,
            ]);
        }else{
            Revenue::create([
                'date' =>  $date,
                'amount' =>  $this->request['chargedAmount'],
                'count' => 1
            ]);
        }
        //==========UPDATE REVENUE TABLE WITH NEW RENEWALS


        $sub = Subscription::lockForUpdate()->where('msisdn', $this->request['msisdn'])
            // ->where('serviceId', $this->request['serviceId'])
            ->where('offerId', $this->request['offerId'])
            ->first();
        if ($sub) {
            $sub->lastRenewalDate = $this->request['lastRenewalDate'];
            $sub->state = $this->request['state'];
            $sub->save();

            if(in_array($this->request['serviceId'], ['772', '787']))// Place holder for Carbun8 Service  /renewal/delete 
            {
                    // $service = AllService::where('offerId', $this->request['offerId'])->first();

                    // $notif = new ThirdPartyNotification($service, $this->request['msisdn'], 'renewal', $this->request['transactionId'], $this->request['subscriptionId'], $this->request['offerId']);

                    // $notif->sendJSON3PPCallbackNotification();

                    // if($this->request['serviceId'] == '787'){ //wiflix
                    //     ThirdPartyNotification::sendWifixNotification($this->request['transactionId'],$this->request['msisdn'], $this->request['serviceId'], $this->request['offerName'],'ADD', '4063');
                    // }

            }else{

                $message = SMSMessage::getDailyMessage($this->request['serviceId']);
                ProcessVFSMSMessaging::dispatch($this->request['msisdn'], '4060', $message, $this->request['serviceId'])->onQueue('smssending');
            }
                        
        } else {
            Subscription::createSubscription($this->request);                        
        }
    }
}
