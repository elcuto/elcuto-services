<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetContentForDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-content-for-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is run by cron daily at 12 am to set the days content before delivery start at 6 am';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = [
            'tb_catholic_msg',
            'tb_deitumi_msg',
            'tb_eastwood_msg',
            'tb_econs_msg',
            'tb_fact_africa_msg',
            'tb_fact_general_msg',
            'tb_fact_ghana_msg',
            'tb_fact_world_msg',
            'tb_fashion_beauty_msg',
            'tb_hygiene_msg',
            'tb_fni_msg',
            'tb_islam_msg',
            'tb_it_tips_msg',
            'tb_jerry_msg',
            'tb_joe_msg',
            'tb_jokes_msg',
            'tb_leader_msg',
            'tb_sports_msg',
            'tb_love_msg',
            'tb_pentecost_msg',
            'tb_safety_msg',
            'tb_fact_today_in_history_msg',
            'tb_whyte_msg',
            'tb_marriage_msg',
            'tb_health_msg'
        ];

        foreach($tables as $table){
            try{
                $data = DB::table($table)->where('date', null)->where('status', null)->orderBy('id', 'ASC')->first();
                // Log::info($data);
                if($data == null){
                    DB::table($table)->where('status','Used')->update([
                        'date' => null,
                        'status' => null
                    ]);
                    $data = DB::table($table)->where('date', null)->where('status', null)->orderBy('id', 'ASC')->first();
                    DB::table($table)->where('id', $data->id)->update([
                        'date' =>date('Y-m-d H:i:s'),
                        'status' => 'Used',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }else{

                    DB::table($table)->where('id', $data->id)->update([
                        'date' =>date('Y-m-d H:i:s'),
                        'status' => 'Used',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

            } catch(\Exception $e){

            }
        }

        return 0;
    }
}
