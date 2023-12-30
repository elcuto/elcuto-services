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
        Schema::create('third_parties', function (Blueprint $table) {
            $table->id();
            $table->string("logo")->nullable();
            $table->string("name")->nullable();
            $table->string("network")->nullable();
            $table->string("email")->nullable();
            $table->longText("description")->nullable();
            $table->string("client_id")->nullable();
            $table->string("secret_key")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('third_parties');
    }
};
