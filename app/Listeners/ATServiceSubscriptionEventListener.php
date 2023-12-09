<?php

namespace App\Listeners;

use App\Events\ATServiceSubscriptionEvent;
use App\TigoGhTimwe\ATSubscriptionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class ATServiceSubscriptionEventListener
{
    /**
     *
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ATServiceSubscriptionEvent $event): void
    {
        $subscription = DB::connection("at_pgsql")->table("tb_at_service_subscriptions")
            ->where("id", $event->subscription_id)->first();
        if($subscription != null){
            $subscription_response = ATSubscriptionService::manualSubscribe(
                $subscription->user_identifier,
                $subscription->user_identifier_type,
                $subscription->product_id,
                $subscription->mcc,
                $subscription->mnc,
                $subscription->entry_channel,
                $subscription->large_account,
                $subscription->sub_keyword,
                $subscription->tracking_id,
                $subscription->client_ip,
                $subscription->campaign_url
            );
            if(is_array($subscription_response)){
                if($subscription_response['status'] == 'success'){
                    // move the subscription to done table
                    $the_response = $subscription_response["data"];
                    DB::connection("at_pgsql")->table("tb_at_service_subscriptions_done")->insert([
                        "user_identifier" => $subscription->user_identifier,
                        "user_identifier_type" => $subscription->user_identifier_type,
                        "large_account" => $subscription->large_account,
                        "product_id" => $subscription->product_id,
                        "external_trxn_id" => "",
                        "mcc" => $subscription->mcc,
                        "mnc" => $subscription->mnc,
                        "sub_keyword" => $subscription->sub_keyword,
                        "tracking_id" => $subscription->tracking_id,
                        "client_ip" => $subscription->client_ip,
                        "message" => $the_response?->message,
                        "in_error" => $the_response?->inError,
                        "request_id" => $the_response?->requestId,
                        "code" => $the_response?->code,
                        "transaction_id" => $the_response?->transactionId,
                        "subscription_result" => $the_response?->subscriptionResult,
                        "subscription_error" => $the_response?->subscriptionError,
                        "campaign_url" => $subscription->campaign_url,
                        "entry_channel" => $subscription->entry_channel,
                        "insert_date" => date("Y-m-d H:i:s"),
                        "no_retries" => $subscription->no_retries,
                        "priority" => 2,
                        "send_date" => date("Y-m-d H:i:s"),
                    ]);
                    //remove the record from subscription table
                    DB::connection("at_pgsql")->table("tb_at_service_subscriptions")
                        ->where("id", $event->subscription_id)->delete();
                }else{
                    // increment no of tries
                    DB::connection("at_pgsql")->table("tb_at_service_subscriptions")
                        ->where("id", $event->subscription_id)
                        ->increment('no_retries', 1);

                    if(($subscription->no_retries+1) > 1){
                        $the_response = $subscription_response["data"];
                        // log the action into the done table with errors thrown
                        DB::connection("at_pgsql")->table("tb_at_service_subscriptions_done")->insert([
                            "user_identifier" => $subscription->user_identifier,
                            "user_identifier_type" => $subscription->user_identifier_type,
                            "large_account" => $subscription->large_account,
                            "product_id" => $subscription->product_id,
                            "external_trxn_id" => "",
                            "mcc" => $subscription->mcc,
                            "mnc" => $subscription->mnc,
                            "sub_keyword" => $subscription->sub_keyword,
                            "tracking_id" => $subscription->tracking_id,
                            "client_ip" => $subscription->client_ip,
                            "message" => $the_response?->message,
                            "in_error" => $the_response?->inError,
                            "request_id" => $the_response?->requestId,
                            "code" => $the_response?->code,
                            "transaction_id" => $the_response?->transactionId,
                            "subscription_result" => $the_response?->subscriptionResult,
                            "subscription_error" => $the_response?->subscriptionError,
                            "campaign_url" => $subscription->campaign_url,
                            "entry_channel" => $subscription->entry_channel,
                            "insert_date" => date("Y-m-d H:i:s"),
                            "no_retries" => $subscription->no_retries + 1,
                            "priority" => 2,
                            "send_date" => date("Y-m-d H:i:s"),
                        ]);
                    }
                }
            }
        }
    }
}
