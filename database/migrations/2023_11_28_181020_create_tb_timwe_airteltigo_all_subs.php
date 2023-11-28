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
        Schema::create('tb_timwe_airteltigo_all_subs', function (Blueprint $table) {
            $table->id();
            $table->string("partner_role", 50)->nullable();
            $table->string("product_id", 50)->nullable();
            $table->string("product_name", 50)->nullable();
            $table->string("price_point_id", 50)->nullable();
            $table->string("mnc", 5)->nullable();
            $table->string("mcc", 5)->nullable();
            $table->string("msisdn", 50)->nullable();
            $table->dateTime("event_insert_date")->nullable();
            $table->string("large_account", 50)->nullable();
            $table->string("entry_channel", 50)->nullable();
            $table->string("keyword", 50)->nullable();
            $table->string("other_info", 50)->nullable();
            $table->dateTime("last_bill_date", 50)->nullable();
            $table->integer("is_active")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_timwe_airteltigo_all_subs');
    }
};
