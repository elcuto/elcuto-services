<?php

namespace Database\Seeders;

use App\Models\AllService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AllServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        AllService::create([
            "service_name" => "Catholic Teachings Daily",
            "service_description" => "Catholic Teachings Daily",
            "msg_table_name" => "tb_catholic_msg",
            "at_sub_table_name" => "tb_catholic_sub",
            "vf_sub_table_name" => "tb_catholic_sub",
            "at_service_id" => "1142",
            "vf_service_id" => "763",
            "vf_offer_id" => "497",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);
        AllService::create([
            "service_name" => "Dei Tumi Daily",
            "service_description" => "Dei Tumi Daily",
            "msg_table_name" => "tb_deitumi_msg",
            "at_sub_table_name" => "tb_deitumi_sub",
            "vf_sub_table_name" => "tb_deitumi_sub",
            "at_service_id" => "1143",
            "vf_service_id" => "768",
            "vf_offer_id" => "498",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);
        AllService::create([
            "service_name" => "Eastwood Daily",
            "service_description" => "Eastwood Daily",
            "msg_table_name" => "tb_eastwood_msg",
            "at_sub_table_name" => "tb_eastwood_sub",
            "vf_sub_table_name" => "tb_eastwood_sub",
            "at_service_id" => "1144",
            "vf_service_id" => "785",
            "vf_offer_id" => "499",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);
        AllService::create([
            "service_name" => "Fact Today In History",
            "service_description" => "Fact Today In History",
            "msg_table_name" => "tb_fact_today_in_history_msg",
            "at_sub_table_name" => "tb_fact_today_in_history_sub",
            "vf_sub_table_name" => "tb_fact_today_in_history_sub",
            "at_service_id" => "",
            "vf_service_id" => "",
            "vf_offer_id" => "499",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);
        AllService::create([
            "service_name" => "Facts Africa Daily",
            "service_description" => "Facts Africa Daily",
            "msg_table_name" => "tb_fact_africa_msg",
            "at_sub_table_name" => "tb_fact_africa_sub",
            "vf_sub_table_name" => "tb_fact_africa_sub",
            "at_service_id" => "1146",
            "vf_service_id" => "773",
            "vf_offer_id" => "501",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);
        AllService::create([
            "service_name" => "Facts General Daily",
            "service_description" => "Facts General Daily",
            "msg_table_name" => "tb_fact_general_msg",
            "at_sub_table_name" => "tb_fact_general_sub",
            "vf_sub_table_name" => "tb_fact_general_sub",
            "at_service_id" => "1147",
            "vf_service_id" => "782",
            "vf_offer_id" => "502",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);
        AllService::create([
            "service_name" => "Facts Ghana Daily",
            "service_description" => "Facts Ghana Daily",
            "msg_table_name" => "tb_fact_ghana_msg",
            "at_sub_table_name" => "tb_fact_ghana_sub",
            "vf_sub_table_name" => "tb_fact_ghana_sub",
            "at_service_id" => "1148",
            "vf_service_id" => "789",
            "vf_offer_id" => "503",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);
        AllService::create([
            "service_name" => "Facts World Daily",
            "service_description" => "Facts World Daily",
            "msg_table_name" => "tb_fact_world_msg",
            "at_sub_table_name" => "tb_fact_world_sub",
            "vf_sub_table_name" => "tb_fact_world_sub",
            "at_service_id" => "1149",
            "vf_service_id" => "770",
            "vf_offer_id" => "504",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);
        AllService::create([
            "service_name" => "Fashion And Beauty Tips Daily",
            "service_description" => "Fashion And Beauty Tips Daily",
            "msg_table_name" => "tb_fashion_beauty_msg",
            "at_sub_table_name" => "tb_fashion_beauty_sub",
            "vf_sub_table_name" => "tb_fashion_beauty_sub",
            "at_service_id" => "1150",
            "vf_service_id" => "774",
            "vf_offer_id" => "505",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);
        AllService::create([
            "service_name" => "Investment Tips Daily",
            "service_description" => "Investment Tips Daily",
            "msg_table_name" => "tb_fni_msg",
            "at_sub_table_name" => "tb_fni_sub",
            "vf_sub_table_name" => "tb_fni_sub",
            "at_service_id" => "",
            "vf_service_id" => "771",
            "vf_offer_id" => "507",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "Health ",
            "service_description" => "Health",
            "msg_table_name" => "tb_health_msg",
            "at_sub_table_name" => "tb_health_sub",
            "vf_sub_table_name" => "tb_health_sub",
            "at_service_id" => "1151",
            "vf_service_id" => "",
            "vf_offer_id" => "497",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "Hygiene Tips Daily",
            "service_description" => "Hygiene Tips Daily",
            "msg_table_name" => "tb_hygiene_msg",
            "at_sub_table_name" => "tb_hygiene_sub",
            "vf_sub_table_name" => "tb_hygiene_sub",
            "at_service_id" => "",
            "vf_service_id" => "775",
            "vf_offer_id" => "506",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "ISLAM INFO Daily",
            "service_description" => "ISLAM INFO Daily",
            "msg_table_name" => "tb_islam_msg",
            "at_sub_table_name" => "tb_islam_sub",
            "vf_sub_table_name" => "tb_islam_sub",
            "at_service_id" => "",
            "vf_service_id" => "786",
            "vf_offer_id" => "508",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "IT INFO Daily",
            "service_description" => "IT INFO Daily",
            "msg_table_name" => "tb_it_tips_msg",
            "at_sub_table_name" => "tb_it_tips_sub",
            "vf_sub_table_name" => "tb_it_tips_sub",
            "at_service_id" => "",
            "vf_service_id" => "778",
            "vf_offer_id" => "509",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "Jerry Daily",
            "service_description" => "Jerry Daily",
            "msg_table_name" => "tb_jerry_msg",
            "at_sub_table_name" => "tb_jerry_sub",
            "vf_sub_table_name" => "tb_jerry_sub",
            "at_service_id" => "",
            "vf_service_id" => "767",
            "vf_offer_id" => "510",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "Joe Daily",
            "service_description" => "Joe Daily",
            "msg_table_name" => "tb_joe_msg",
            "at_sub_table_name" => "tb_joe_sub",
            "vf_sub_table_name" => "tb_joe_sub",
            "at_service_id" => "",
            "vf_service_id" => "779",
            "vf_offer_id" => "511",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "Jokez Daily",
            "service_description" => "Jokez Daily",
            "msg_table_name" => "tb_jokes_msg",
            "at_sub_table_name" => "tb_jokes_sub",
            "vf_sub_table_name" => "tb_jokes_sub",
            "at_service_id" => "",
            "vf_service_id" => "781",
            "vf_offer_id" => "512",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "LEADERSHIP INFO Daily",
            "service_description" => "LEADERSHIP INFO Daily",
            "msg_table_name" => "tb_leader_msg",
            "at_sub_table_name" => "tb_leader_sub",
            "vf_sub_table_name" => "tb_leader_sub",
            "at_service_id" => "",
            "vf_service_id" => "784",
            "vf_offer_id" => "513",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "LOVE INFO Daily",
            "service_description" => "LOVE INFO Daily",
            "msg_table_name" => "tb_love_msg",
            "at_sub_table_name" => "tb_love_sub",
            "vf_sub_table_name" => "tb_love_sub",
            "at_service_id" => "",
            "vf_service_id" => "776",
            "vf_offer_id" => "514",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "Marriage Counselling Tips Daily",
            "service_description" => "Marriage Counselling Tips Daily",
            "msg_table_name" => "tb_marriage_msg",
            "at_sub_table_name" => "tb_marriage_sub",
            "vf_sub_table_name" => "tb_marriage_sub",
            "at_service_id" => "",
            "vf_service_id" => "780",
            "vf_offer_id" => "515",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "Pentecost Daily",
            "service_description" => "Pentecost Daily",
            "msg_table_name" => "tb_pentecost_msg",
            "at_sub_table_name" => "tb_pentecost_sub",
            "vf_sub_table_name" => "tb_pentecost_sub",
            "at_service_id" => "",
            "vf_service_id" => "766",
            "vf_offer_id" => "516",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "Economics Tips Daily",
            "service_description" => "Economics Tips Daily",
            "msg_table_name" => "tb_econs_msg",
            "at_sub_table_name" => "tb_econs_sub",
            "vf_sub_table_name" => "tb_econs_sub",
            "at_service_id" => "1145",
            "vf_service_id" => "769",
            "vf_offer_id" => "500",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "Whyte Daily",
            "service_description" => "Whyte Daily",
            "msg_table_name" => "tb_whyte_msg",
            "at_sub_table_name" => "tb_whyte_sub",
            "vf_sub_table_name" => "tb_whyte_sub",
            "at_service_id" => "",
            "vf_service_id" => "783",
            "vf_offer_id" => "N/A",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);


        AllService::create([
            "service_name" => "SAFETY Daily",
            "service_description" => "SAFETY Daily",
            "msg_table_name" => "tb_safety_msg",
            "at_sub_table_name" => "tb_safety_sub",
            "vf_sub_table_name" => "tb_safety_sub",
            "at_service_id" => "",
            "vf_service_id" => "764",
            "vf_offer_id" => "517",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);

        AllService::create([
            "service_name" => "SPORTS TITBITS Daily",
            "service_description" => "SPORTS TITBITS Daily",
            "msg_table_name" => "tb_sports_msg",
            "at_sub_table_name" => "tb_sports_sub",
            "vf_sub_table_name" => "tb_sports_sub",
            "at_service_id" => "",
            "vf_service_id" => "777",
            "vf_offer_id" => "518",
            "vf_charge_amount" => "0.19",
            "at_charge_amount" => "",
            "vf_client_id" => "",
            "vf_api_secret" => "",
            "service_type" => "",
            "service_network" => "",
            "call_back_url" => "",
            "shortcode" => "4060"
        ]);


    }
}
