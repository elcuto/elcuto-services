<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VFDailyPointsBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to backup promo points daily at 12am';

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
            $records = DB::connection("vf_connection")->table('PromoSubs')->select('MSISDN',
                        'RegDate',
                        'Name',
                        'Points',
                        'WeekPoints',
                        'TotalPoints',
                        'playCNT',
                        'Weekplay',
                        'Totalplay',
                        'QID',
                        'LastRcvd',
                        'QuestCNT',
                        'State',
                        'SubType',
                        'PlayedLimit',
                        'Specialword',
                        'created_at',
                        'updated_at')->get()->toArray();
            
            DB::connection("vf_connection")->table('PromoSubsBkp')->insert(json_decode(json_encode($records), True));

            DB::connection("vf_connection")->table('PromoSubs')->update([
                'Points' => 0
            ]);

        }catch(Exception $e){
            // Log::error($e);
        }
        // return 0;
    }
}
