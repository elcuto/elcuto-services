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
        Schema::create('tb_partner_mt', function (Blueprint $table) {
            $table->id();
            $table->string("product_id", 50)->nullable();
            $table->string("price_point_id", 50)->nullable();
            $table->string("mnc", 5)->nullable();
            $table->string("mcc", 5)->nullable();
            $table->string("text", 200)->nullable();
            $table->string("msisdn", 50)->nullable();
            $table->string("large_account", 50)->nullable();
            $table->date("send_date", 50)->nullable();
            $table->string("priority", 50)->nullable();
            $table->string("timezone", 50)->nullable();
            $table->string("context", 50)->nullable();
            $table->string("mo_transaction_uuid", 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_partner_mt');
    }
};
