<?php

namespace App\Console\Commands;


use App\Jobs\ProcessVFSendPromoRenewal;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VFDailyPromoRenewal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to renew promo subscription';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        try{
            $records = DB::connection("vf_connection")->table('PromoSubs')->where('State', 1)
                        ->where('lastRenewed', '<', date('Y-m-d'))
                        ->orWhere('lastRenewed', null)->get();
            // Log::error($records);
            if(count($records) > 0){
                $daycode = $this->generateRandomString();
                foreach($records as $r){
                    $tempClientTranId = $r->MSISDN.'CODE'.$daycode;
                    $tempRequestTranId = $r->MSISDN.'CODE'.$daycode;

                    //delete if there newewal from previous day
                    DB::connection("vf_connection")->table('MTBillingSendingTable')->where('msisdn', $r->MSISDN)->delete();

                    $id = DB::connection("vf_connection")->table('MTBillingSendingTable')->insertGetId([
                        'amount' => config('promo.MT_RENEW_CHARGE'),
                        'message' => '',
                        'clientChargeTransactionId' => $tempClientTranId,
                        'clientRequestId' => $tempRequestTranId,
                        'channel' => 'SMS',
                        'msisdn' => $r->MSISDN,
                        'offer' => config('promo.OFFER_NAME'),
                        'description' => '',
                        'unit' => 1, //1=>Money, 2=>Piece, 3=>Byte, 4=>Second
                        'parameters' => json_encode([]),
                        'name' => '',
                        'value' => '',
                        'notries'=> 1,
                        'priority' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    ProcessVFSendPromoRenewal::dispatch($id)->onConnection('redis');
                }
            }
        }catch(Exception $e){
            
        }
        return 0;
    }



    public function generateRandomString($length = 12)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }
}
