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
        Schema::create('direct_charges', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount')->nullable();
            $table->string('clientChargeTransactionId')->nullable();
            $table->string('clientRequestId')->nullable();
            $table->string('channel')->nullable();
            $table->string('msisdn')->nullable();
            $table->string('offer')->nullable();
            $table->string('description')->nullable();
            $table->string('unit')->nullable();
            $table->longText('parameters')->nullable();
            $table->string('transactionId')->nullable();
            $table->longText('apireponse')->nullable();
            $table->string('status')->nullable();
            $table->string('errorCode')->nullable();
            $table->string('errorMsg')->nullable();
            $table->string('rootErrorCode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direct_charges');
    }
};
