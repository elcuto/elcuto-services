<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected $connection = "vf_connection";
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('DCB_MONotifications', function (Blueprint $table) {
            $table->id("Id");
            $table->string('merchantId')->nullable();
            $table->string('carrierId')->nullable();
            $table->string('shortCode')->nullable();
            $table->string('accountIdType')->nullable();
            $table->string('accountId')->nullable();
            $table->string('msisdn')->nullable();
            $table->string('smsText')->nullable();
            $table->string('carrierTransactionId')->nullable();
            $table->string('actionType')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DCB_MONotifications');
    }
};
