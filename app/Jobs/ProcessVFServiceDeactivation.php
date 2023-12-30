<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\UnSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessVFServiceDeactivation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request;
    /**
     * Create a new job instance.
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
        $this->processDeactivation();  
    }

    public function processDeactivation(){

        UnSubscription::createDeactivation($this->request);

        $sub = Subscription::where('msisdn', $this->request['msisdn'])
            // ->where('serviceId', $this->request['serviceId'])
            ->where('offerId', $this->request['offerId'])
            ->first();
        if ($sub) {
                $sub->delete();
        } else{
            Log::info("++++++++++++++++++++++++ SUB NOT FROUND FROM DEACTIVATE +++++++++++++++++++++++++");
            Log::info("++++++++++++++++++++++++ SUB NOT FROUND FROM DEACTIVATE +++++++++++++++++++++++++");
            Log::info("++++++++++++++++++++++++ SUB NOT FROUND FROM DEACTIVATE +++++++++++++++++++++++++");
            Log::info("++++++++++++++++++++++++ SUB NOT FROUND FROM DEACTIVATE +++++++++++++++++++++++++");
        }

        if(in_array($this->request['serviceId'], ['772', '787']) ) // Place holder for Carbun8 Service  /renewal/delete 
        {
                // $service = AllService::where('offerId', $this->request['offerId'])->first();

                // $notif = new ThirdPartyNotification($service, $this->request['msisdn'], 'delete', $this->request['transactionId'], $this->request['subscriptionId'], $this->request['offerId']);

                // $notif->sendJSON3PPCallbackNotification();

                // if($this->request['serviceId'] == '787'){ //wiflix
                //         ThirdPartyNotification::sendWifixNotification($this->request['transactionId'],$this->request['msisdn'], $this->request['serviceId'], $this->request['offerName'],'REMOVE', '4063');
                //  }
        }
        else if($this->request['offerId'] == '05') // Place holder for Wiflix Service
        {
            return;
        }else{
            return;
        }
    }
}
