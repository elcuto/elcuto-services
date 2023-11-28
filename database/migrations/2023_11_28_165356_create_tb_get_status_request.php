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
        Schema::create('tb_get_status_request', function (Blueprint $table) {
            $table->id();
            $table->string("user_identifier", 50)->nullable();
            $table->string("user_identifier_type", 50)->nullable();
            $table->string("product_id", 50)->nullable();
            $table->string("mnc", 5)->nullable();
            $table->string("mcc", 5)->nullable();
            $table->string("entry_channel", 50)->nullable();
            $table->string("client_ip", 50)->nullable();
            $table->string("control_keyword", 50)->nullable();
            $table->string("control_service_keyword", 50)->nullable();
            $table->integer("sub_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_get_status_request');
    }
};
