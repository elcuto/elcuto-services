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
        Schema::create('tb_subscription_request_resp', function (Blueprint $table) {
            $table->id();
            $table->string("message", 50)->nullable();
            $table->integer("in_error")->nullable();
            $table->string("request_id", 50)->nullable();
            $table->string("code", 50)->nullable();
            $table->string("client_ip", 50)->nullable();
            $table->string("resp_transaction_id", 50)->nullable();
            $table->string("resp_subscription_result", 50)->nullable();
            $table->string("resp_subscription_error", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_subscription_request_resp');
    }
};
