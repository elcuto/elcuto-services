<?php

namespace App\Jobs;

use App\TigoGhTimwe\TigoMegaPromoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcessATPromoMORequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Request $request;
    private string $actionType;
    /**
     * Create a new job instance.
     */
    public function __construct($request, $actionType)
    {
        $this->request = $request;
        $this->actionType = $actionType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            TigoMegaPromoService::processMsgRcvd($this->request, $this->actionType);
        }catch (\Exception $e){
            Log::error("============ UNABLE TO PROCESS AT PROMO MO ==================");
            Log::error($e);
            Log::error("============ END UNABLE TO PROCESS AT PROMO MO ==================");
        }
    }
}
