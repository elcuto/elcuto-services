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
        Schema::create('MTBillingSentTable', function (Blueprint $table) {
            $table->id("Id");
            $table->string('amount')->nullable();
            $table->string('message')->nullable();
            $table->string('clientChargeTransactionId')->nullable();
            $table->string('clientRequestId')->nullable();
            $table->string('channel')->nullable();
            $table->string('msisdn')->nullable();
            $table->string('offer')->nullable();
            $table->string('description')->nullable();
            $table->string('unit')->nullable();
            $table->string('parameters')->nullable();
            $table->string('name')->nullable();
            $table->string('value')->nullable();
            $table->string('transactionId')->nullable();
            $table->string('errorCode')->nullable();
            $table->string('errorMsg')->nullable();
            $table->string('rootErrorCode')->nullable();
            $table->integer('notries')->nullable();
            $table->integer('priority')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MTBillingSentTable');
    }
};
