<?php

namespace App\Jobs;

use App\APIServices\Vodafone\DirectDebitCharging;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessVFSendPromoRenewal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;  //BillingSenditable record id
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $record = DB::connection("vf_connection")->table('MTBillingSendingTable')->where('Id', $this->id)->first();
            // $record = collect(DB::select("SELECT * FROM MTBillingSendingTable WHERE Id=$this->id"))->first();
            $response = DirectDebitCharging::chargeCustomer($record, config('vodafone.SERVICE_ID'));
            if($response['status'] == 'OK'){
                if(array_key_exists('transactionId', $response['data']) && $response['data']['transactionId'] !=null){
                    //update user to prevent multiple remewal
                    DB::connection("vf_connection")->table('PromoSubs')->where('MSISDN', $record->msisdn)->update([
                        'lastRenewed' => date('Y-m-d H:i:s')
                    ]);
                    //move to bill sent table
                    DB::connection("vf_connection")->table('MTBillingSentTable')->insert([
                        'amount' => $record->amount,
                        'message' => $record->message,
                        'clientChargeTransactionId' => $record->clientChargeTransactionId,
                        'clientRequestId' => $record->clientRequestId,
                        'channel' => $record->channel,
                        'msisdn' => $record->msisdn,
                        'offer' => $record->msisdn,
                        'description' => $record->description,
                        'unit' => $record->unit,
                        'parameters' => $record->parameters,
                        'name' => $record->name,
                        'value' => $record->value,
                        'transactionId' => $record->transactionId,
                        'errorCode' => $record->errorCode,
                        'errorMsg' => $record->errorMsg,
                        'rootErrorCode' => $record->rootErrorCode,
                        'notries' => $record->notries,
                        'priority' => $record->priority,
                        'status' => 'SUCCESS',
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at,
                    ]);
                    //remove from Sending table
                    DB::connection("vf_connection")->table('MTBillingSendingTable')->delete($record->Id);
                }else{
                    if($record->notries >=5){
                        //renewal failed after five reries to move to sent table
                        $data = $response['data'];
                        DB::connection("vf_connection")->table('MTBillingSentTable')->insert([
                            'amount' => $record->amount,
                            'message' => $record->message,
                            'clientChargeTransactionId' => $record->clientChargeTransactionId,
                            'clientRequestId' => $record->clientRequestId,
                            'channel' => $record->channel,
                            'msisdn' => $record->msisdn,
                            'offer' => $record->offer,
                            'description' => $record->description,
                            'unit' => $record->unit,
                            'parameters' => $record->parameters,
                            'name' => $record->name,
                            'value' => $record->value,
                            'transactionId' => $record->transactionId,
                            'errorCode' => $record->errorCode,
                            'errorMsg' => $record->errorMsg,
                            'rootErrorCode' => $record->rootErrorCode,
                            'notries' => $record->notries,
                            'priority' => $record->priority,
                            'status' => 'ERROR',
                            'created_at' => $record->created_at,
                            'updated_at' => $record->updated_at,
                        ]); 
                        DB::connection("vf_connection")->table('MTBillingSendingTable')->delete($record->Id);
                    }else{
                        DB::connection("vf_connection")->table('MTBillingSendingTable')->where('Id', $this->id)->update([
                            'amount' => config('vodafone.MT_STEP_CHARGE'),
                            'notries' => (int)$record->notries +1
                        ]);
                        self::dispatch($this->id);
                    }
                }
            }else{  //error occured
                $data = $response['data'];
                switch($data['rootErrorCode']){
                    case 4:
                        DB::connection("vf_connection")->table('MTBillingSentTable')->insert([
                            'amount' => $record->amount,
                            'message' => $record->message,
                            'clientChargeTransactionId' => $record->clientChargeTransactionId,
                            'clientRequestId' => $record->clientRequestId,
                            'channel' => $record->channel,
                            'msisdn' => $record->msisdn,
                            'offer' => $record->offer,
                            'description' => $record->description,
                            'unit' => $record->unit,
                            'parameters' => $record->parameters,
                            'name' => $record->name,
                            'value' => $record->value,
                            'transactionId' => $record->transactionId,
                            'errorCode' => $data['errorCode'],
                            'errorMsg' => $data['errorMsg'],
                            'rootErrorCode' => $data['rootErrorCode'],
                            'notries' => $record->notries,
                            'priority' => $record->priority,
                            'status' => 'ERROR',
                            'created_at' => $record->created_at,
                            'updated_at' => $record->updated_at,
                        ]); 
                        break;
                    default:
                        DB::connection("vf_connection")->table('MTBillingSentTable')->insert([
                            'amount' => $record->amount,
                            'message' => $record->message,
                            'clientChargeTransactionId' => $record->clientChargeTransactionId,
                            'clientRequestId' => $record->clientRequestId,
                            'channel' => $record->channel,
                            'msisdn' => $record->msisdn,
                            'offer' => $record->offer,
                            'description' => $record->description,
                            'unit' => $record->unit,
                            'parameters' => $record->parameters,
                            'name' => $record->name,
                            'value' => $record->value,
                            'transactionId' => $record->transactionId,
                            'errorCode' => $data['errorCode'],
                            'errorMsg' => $data['errorMsg'],
                            'rootErrorCode' => $data['rootErrorCode'],
                            'notries' => $record->notries,
                            'priority' => $record->priority,
                            'status' => 'ERROR',
                            'created_at' => $record->created_at,
                            'updated_at' => $record->updated_at,
                        ]); 
                }
            }
        }catch(Exception $e){
            Log::error($e);
        }
    }
}
