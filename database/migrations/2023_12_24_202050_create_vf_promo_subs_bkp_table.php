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
        Schema::create('PromoSubsBkp', function (Blueprint $table) {
            $table->id("Id");
            $table->string('MSISDN')->nullable();
            $table->datetime('RegDate')->nullable();
            $table->string('Name')->nullable();
            $table->bigInteger('Points')->nullable();
            $table->bigInteger('WeekPoints')->nullable();
            $table->bigInteger('TotalPoints')->nullable();
            $table->bigInteger('playCNT')->nullable();
            $table->bigInteger('Weekplay')->nullable();
            $table->bigInteger('Totalplay')->nullable();
            $table->integer('QID')->nullable();
            $table->datetime('LastRcvd')->nullable();
            $table->bigInteger('QuestCNT')->nullable();
            $table->string('State')->nullable();
            $table->string('SubType')->nullable();
            $table->bigInteger('PlayedLimit')->nullable();
            $table->string('Specialword')->nullable();
            $table->date('event_insert_date')->nullable()->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PromoSubsBkp');
    }
};
