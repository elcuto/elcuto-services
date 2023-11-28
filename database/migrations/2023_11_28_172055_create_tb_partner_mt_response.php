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
        Schema::create('tb_partner_mt_response', function (Blueprint $table) {
            $table->id();
            $table->string("response_data", 150)->nullable();
            $table->string("message", 160)->nullable();
            $table->string("in_error", 50)->nullable();
            $table->string("request_id", 50)->nullable();
            $table->string("code", 50)->nullable();
            $table->string("transaction_uuid", 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_partner_mt_response');
    }
};
