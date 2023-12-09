<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = "at_mega_promo";
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_at_promo_subs_back_up', function (Blueprint $table) {
            $table->id();
            $table->string('msisdn')->nullable();
            $table->dateTime('reg_date')->nullable();
            $table->string('name')->nullable();
            $table->bigInteger('points')->nullable();
            $table->bigInteger('week_points')->nullable();
            $table->bigInteger('total_points')->nullable();
            $table->bigInteger('play_count')->default(0);
            $table->bigInteger('week_play')->default(0);
            $table->bigInteger('total_play')->default(0);
            $table->bigInteger('qid')->nullable();
            $table->dateTime('last_rcvd')->nullable();
            $table->bigInteger('quest_count')->nullable();
            $table->tinyInteger('state')->default(0);
            $table->string('sub_type')->nullable();
            $table->bigInteger('played_limit')->nullable();
            $table->string('special_word')->nullable();
            $table->dateTime('event_insert_date')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_at_promo_subs_back_up');
    }
};
