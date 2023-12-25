<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SMSMessage {


    public static function getDailyMessage($serviceid){
        switch($serviceid){
                case '763': //Catholic
                    $message = DB::connection("test_db_pgsql")->table('tb_catholic_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_catholic_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '768':  // DeiTumi
                    $message = DB::connection("test_db_pgsql")->table('tb_deitumi_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_deitumi_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '785':  //Eastwood
                    $message = DB::connection("test_db_pgsql")->table('tb_eastwood_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_eastwood_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '769':  //Econs
                    $message = DB::connection("test_db_pgsql")->table('tb_econs_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_econs_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '773':  //Africa
                    $message = DB::connection("test_db_pgsql")->table('tb_fact_africa_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_fact_africa_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '782': //general
                    $message = DB::connection("test_db_pgsql")->table('tb_fact_general_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_fact_general_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '789': //Ghana
                    $message = DB::connection("test_db_pgsql")->table('tb_fact_ghana_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_fact_ghana_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '770': //World
                    $message = DB::table('tb_fact_world_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_fact_world_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '774': // Fashion and Beauty
                    $message = DB::connection("test_db_pgsql")->table('tb_fashion_beauty_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
    
                        $message = DB::connection("test_db_pgsql")->table('tb_fashion_beauty_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '775': //Hygiene
                    $message = DB::connection("test_db_pgsql")->table('tb_hygiene_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_hygiene_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '771':  //Invesment
                    $message = DB::connection("test_db_pgsql")->table('tb_fni_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_fni_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '786': //islam
                    $message = DB::connection("test_db_pgsql")->table('tb_islam_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_islam_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '778': //IT Info
                    $message = DB::connection("test_db_pgsql")->table('tb_it_tips_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_it_tips_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '767': //Jeery
                    $message = DB::connection("test_db_pgsql")->table('tb_jerry_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_jerry_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '779': //Joe
                    $message = DB::connection("test_db_pgsql")->table('tb_joe_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_joe_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '781': //Jokes
                    $message = DB::connection("test_db_pgsql")->table('tb_jokes_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_jokes_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '784': //leadership
                    $message = DB::connection("test_db_pgsql")->table('tb_leader_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_leader_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '776': //Love
                    $message = DB::connection("test_db_pgsql")->table('tb_love_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_love_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '780': //Marriage
                    $message = DB::connection("test_db_pgsql")->table('tb_marriage_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_marriage_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '766': //Pent
                    $message = DB::connection("test_db_pgsql")->table('tb_pentecost_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_pentecost_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                    break;
                case '764': //Safety
                    $message = DB::connection("test_db_pgsql")->table('tb_safety_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_safety_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                case '777': //Sports
                    $message = DB::connection("test_db_pgsql")->table('tb_sports_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_sports_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                case '765': //Today
                    $message = DB::connection("test_db_pgsql")->table('tb_fact_today_in_history_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_fact_today_in_history_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
                case '783': //whyte
                    $message = DB::connection("test_db_pgsql")->table('tb_whyte_msg')->whereDate('date', date('Y-m-d'))->where('status', 'Used')->orderBy('id', 'ASC')->first();
                    if($message){
                        return $message->Message;
                    }else{
                        $message = DB::connection("test_db_pgsql")->table('tb_whyte_msg')->where('status', 'Used')->orderBy('id', 'ASC')->first();
                        return $message->Message;
                    }
    
                default:
                    return "";
        }
    
    }
}