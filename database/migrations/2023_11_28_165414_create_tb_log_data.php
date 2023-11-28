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
        Schema::create('tb_log_data', function (Blueprint $table) {
            $table->id();
            $table->string("code", 50)->nullable();
            $table->string("message_id", 50)->nullable();
            $table->string("message", 200)->nullable();
            $table->string("request_id", 50)->nullable();
            $table->string("request_data", 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_log_data');
    }
};
