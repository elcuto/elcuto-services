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
        Schema::create('tb_response_data', function (Blueprint $table) {
            $table->id();
            $table->string("transaction_id", 50)->nullable();
            $table->string("subscription_result", 50)->nullable();
            $table->string("subscription_error", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_response_data');
    }
};
