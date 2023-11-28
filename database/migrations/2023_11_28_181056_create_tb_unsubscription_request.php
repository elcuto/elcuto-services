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
        Schema::create('tb_unsubscription_request', function (Blueprint $table) {
            $table->id();
            $table->string("user_identifier", 50)->nullable();
            $table->string("user_identifier_type", 50)->nullable();
            $table->string("product_id", 50)->nullable();
            $table->string("mnc", 50)->nullable();
            $table->string("mcc", 50)->nullable();
            $table->string("entry_channel", 50)->nullable();
            $table->string("large_account", 50)->nullable();
            $table->string("sub_keyword", 50)->nullable();
            $table->string("tracking_id", 50)->nullable();
            $table->string("client_ip", 50)->nullable();
            $table->string("control_keyword", 50)->nullable();
            $table->string("control_service_keyword", 50)->nullable();
            $table->string("sub_id", 50)->nullable();
            $table->string("cancel_reason", 50)->nullable();
            $table->string("cancel_source", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_unsubscription_request');
    }
};
