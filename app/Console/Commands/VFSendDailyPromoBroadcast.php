<?php

namespace App\Console\Commands;

use App\Models\SMSSending;
use App\Vodafone\PromoMechanics;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VFSendDailyPromoBroadcast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:broadcast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This runs every morning to send broacast to the players with their last question';

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
        $records = DB::connection("vf_connection")->table('PromoSubs')->where('State', 1)->whereDate('LastRcvd','<', date('Y-m-d') )->take(20)->get();
        foreach($records as $r){
            $messageid = Str::orderedUuid();
            $nextMsg = DB::connection("vf_connection")->table('PromoMsg')->where('Id', $r->QID)->orderBy('Id', 'ASC')->first();
            $message = "QUESTION: ".$nextMsg->Question."; Reply with A or B only";
            PromoMechanics::sendMessage($r->MSISDN, '4063', $messageid, $message);
            DB::connection("vf_connection")->table('PromoSubs')->where('MSISDN', $r->MSISDN)->update([
                'LastRcvd' => date('Y-m-d H:i:s')
            ]);
        }
        return 0;
    }
}
