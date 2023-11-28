<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = "at_pgsql";
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_at_service_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string("user_identifier", 20)->nullable();
            $table->string("user_identifier_type", 50)->nullable();
            $table->string("large_account", 10)->nullable();
            $table->string("product_id", 50)->nullable();
            $table->string("external_trxn_id", 100)->nullable();
            $table->string("mcc", 5)->nullable();
            $table->string("mnc", 5)->nullable();
            $table->string("sub_keyword")->nullable();
            $table->string("tracking_id", 100)->nullable();
            $table->string("client_ip", 50)->nullable();
            $table->string("campaign_url")->nullable();
            $table->string("entry_channel")->nullable();
            $table->dateTime("insert_date")->nullable();
            $table->string("no_retries")->nullable();
            $table->string("priority")->nullable();
            $table->dateTime("send_date")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_at_service_subscriptions');
    }
};
