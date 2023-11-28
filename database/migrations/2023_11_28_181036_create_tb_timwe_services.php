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
        Schema::create('tb_timwe_services', function (Blueprint $table) {
            $table->id("record_id");
            $table->string("service_name", 100)->nullable();
            $table->string("sdescription", 50)->nullable();
            $table->string("partner_id", 50)->nullable();
            $table->string("partner_service_id", 50)->nullable();
            $table->string("partner_role", 50)->nullable();
            $table->string("product_name", 50)->nullable();
            $table->string("product_id", 50)->nullable();
            $table->string("price_point_id_dob", 50)->nullable();
            $table->string("price_point_mt_free", 50)->nullable();
            $table->string("price_point_mo_free", 50)->nullable();
            $table->string("mcc", 5)->nullable();
            $table->string("mnc", 5)->nullable();
            $table->string("entry_channel", 50)->nullable();
            $table->string("tb_name", 200)->nullable();
            $table->dateTime("launch_date")->nullable();
            $table->string("shortcode", 50)->nullable();
            $table->string("keywords", 50)->nullable();
            $table->string("msg_table_name", 50)->nullable();
            $table->string("sub_table_name", 50)->nullable();
            $table->string("help_msg", 50)->nullable();
            $table->string("prefix", 50)->nullable();
            $table->integer("can_subscribe")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_timwe_services');
    }
};
