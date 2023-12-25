<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('all_services', function (Blueprint $table) {
            $table->id();
            $table->string("service_name")->nullable();
            $table->string("service_description")->nullable();
            $table->string("msg_table_name")->nullable();
            $table->string("at_sub_table_name")->nullable();
            $table->string("vf_sub_table_name")->nullable();
            $table->string("at_service_id")->nullable();
            $table->string("vf_service_id")->nullable();
            $table->string("vf_offer_id")->nullable();
            $table->string("vf_charge_amount")->nullable();
            $table->string("at_charge_amount")->nullable();
            $table->string("vf_client_id")->nullable();
            $table->string("vf_api_secret")->nullable();
            $table->string("service_type")->default("ELCUTO");
            $table->string("service_network")->nullable();
            $table->string("call_back_url")->nullable();
            $table->string("shortcode")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_services');
    }
};
