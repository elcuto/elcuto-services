<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ATDailyPromoPointsBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:a-t-daily-promo-points-back-up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            $records = DB::connection("at_mega_promo")->table('at_promo_subs')
                ->select(
                "msisdn",
                "reg_date",
                "name",
                "points",
                "week_points",
                "total_points",
                "play_count",
                "week_play",
                "total_play",
                "qid",
                "last_rcvd",
                "quest_count",
                "state",
                "sub_type",
                "played_limit",
                "special_word",
                'created_at',
                'updated_at')->get()->toArray();

            DB::connection("at_mega_promo")
                ->table('tb_at_promo_subs_back_up')
                ->insert(json_decode(json_encode($records), True));

            DB::connection("at_mega_promo")->table('at_promo_subs')->update([
                'points' => 0
            ]);

        }catch(\Exception $e){
            // Log::error($e);
        }
    }
}
