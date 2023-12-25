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
        Schema::create('MONotifications', function (Blueprint $table) {
            $table->id();
            $table->datetime('requestDate')->nullable();
            $table->string('destinationAddress')->nullable();
            $table->string('shortCode')->nullable();
            $table->string('messageId')->nullable();
            $table->string('message')->nullable();
            $table->string('senderAddress')->nullable();
            $table->string('dcs')->nullable();
            $table->text('callbackData')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MONotifications');
    }
};
