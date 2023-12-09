<?php

namespace App\Jobs;

use App\TigoGhTimwe\TigoGhTimweService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessATRenewalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public object $data;
    public string $actionType;
    public string $transactionUUID;
    /**
     * Create a new job instance.
     */
    public function __construct(object $data, string $actionType, string $transactionUUID)
    {
        $this->data = $data;
        $this->actionType = $actionType;
        $this->transactionUUID = $transactionUUID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TigoGhTimweService::processReceivedNotifications($this->data, $this->actionType, $this->transactionUUID);
    }
}
