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
        Schema::create('SMSSendingTable', function (Blueprint $table) {
            $table->id();
            $table->longText("address");
            $table->string('senderAddress');
            $table->string('outboundSMSTextMessage');
            $table->string('clientCorrelator');
            $table->string('notifyURL');
            $table->string('callbackData');
            $table->string('senderName');
            $table->string('deliveryStatus')->nullable();
            $table->string('requestId')->nullable();
            $table->string('errorCode')->nullable();
            $table->string('errorDescription')->nullable();
            $table->boolean('sent')->default(false);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SMSSendingTable');
    }
};
