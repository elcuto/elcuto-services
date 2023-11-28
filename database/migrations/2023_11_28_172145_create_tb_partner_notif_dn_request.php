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
        Schema::create('tb_partner_notif_dn_request', function (Blueprint $table) {
            $table->id();
            $table->string("partner_role", 50)->nullable();
            $table->string("external_txid", 50)->nullable();
            $table->dateTime("event_insert_date")->nullable();
            $table->string("product_id", 50)->nullable();
            $table->string("price_point_id", 50)->nullable();
            $table->string("mcc", 5)->nullable();
            $table->string("mnc", 5)->nullable();
            $table->string("transaction_uuid", 50)->nullable();
            $table->string("user_identifier", 50)->nullable();
            $table->string("large_account", 50)->nullable();
            $table->string("mno_delivery_code", 50)->nullable();
            $table->string("tags", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_partner_notif_dn_request');
    }
};
