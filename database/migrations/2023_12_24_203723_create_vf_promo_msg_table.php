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
        Schema::create('PromoMsg', function (Blueprint $table) {
            $table->id("Id");
            $table->text('Question')->nullable();
            $table->datetime('Date')->nullable();
            $table->string('PossibleAnswers')->nullable();
            $table->string('Answers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PromoMsg');
    }
};
