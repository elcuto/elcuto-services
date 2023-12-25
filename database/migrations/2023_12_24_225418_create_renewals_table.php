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
        Schema::create('Renewals', function (Blueprint $table) {
            $table->id();
            $table->string("subscriptionId")->nullable();
            $table->string("msisdn")->nullable();
            $table->string("state")->nullable();
            $table->string("offerId")->nullable();
            $table->string("offerName")->nullable();
            $table->string("shortCode")->nullable();
            $table->string("transactionId")->nullable();
            $table->string("serviceNotificationType")->nullable();
            $table->string("serviceId")->nullable();
            $table->string("serviceName")->nullable();
            $table->string("isRenewal")->nullable();
            $table->string("failureReason")->nullable();
            $table->string("subscriptionStartDate")->nullable();
            $table->string("subscriptionEndDate")->nullable();
            $table->string("nextChargingDate")->nullable();
            $table->string("lastRenewalDate")->nullable();
            $table->string("channelType")->nullable();
            $table->string("trailToPaid")->nullable();
            $table->string("chargingPeriod")->nullable();
            $table->string("subscriptionCounter")->nullable();
            $table->string("requestDate")->nullable();
            $table->string("chargedAmount")->nullable();
            $table->string("inTry")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Renewals');
    }
};
