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
        Schema::create('tb_aa_partner_notif_renew_msisdn', function (Blueprint $table) {
            $table->id();
            $table->string("msisdn")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_aa_partner_notif_renew_msisdn');
    }
};
