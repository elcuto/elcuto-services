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
        Schema::create('tb_mt_sms_sent', function (Blueprint $table) {
            $table->id("message_id");
            $table->string("msisdn", 20)->nullable();
            $table->string("message", 200)->nullable();
            $table->tinyInteger("is_sent")->nullable();
            $table->dateTime("requested")->nullable();
            $table->dateTime("date_to_send")->nullable();
            $table->string("shortcode", 12)->nullable();
            $table->integer("priority")->nullable();
            $table->integer("no_of_attempts")->nullable();
            $table->string("product_id", 50)->nullable();
            $table->string("price_point_id", 50)->nullable();
            $table->string("mcc", 5)->nullable();
            $table->string("mnc", 5)->nullable();
            $table->date("send_date", 50)->nullable();
            $table->string("timezone", 100)->nullable();
            $table->string("mt_priority", 50)->nullable();
            $table->string("mt_context", 50)->nullable();
            $table->string("mo_transaction_uuid", 50)->nullable();
            $table->string("entry_channel", 50)->nullable();
            $table->string("mt_response_data", 200)->nullable();
            $table->string("resp_message", 50)->nullable();
            $table->string("in_error", 50)->nullable();
            $table->string("resp_request_id", 50)->nullable();
            $table->string("resp_code", 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_mt_sms_sent');
    }
};
