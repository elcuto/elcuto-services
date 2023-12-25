<?php

namespace App\Jobs;

use App\Vodafone\PromoMechanics;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessVFPromoMORequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $msisdn;
    public $carrierTransactionId;
    public $shortCode;
    public $smsText;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($msisdn, $carrierTransactionId, $shortCode,  $smsText)
    {
        $this->msisdn = $msisdn;
        $this->carrierTransactionId = $carrierTransactionId;
        $this->shortCode = $shortCode;
        $this->smsText = $smsText;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            Log::info(' QUEUE PROCESSED');
            PromoMechanics::processRequest($this->msisdn, $this->carrierTransactionId, $this->shortCode, $this->smsText);
        }catch(Exception $e){
            // Log::error($e);
            Log::info('ERROR: PROCESSING QUEUE ');
        }
    }
}
