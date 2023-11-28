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
        Schema::create('at_promo_mt_content', function (Blueprint $table) {
            $table->id();
            $table->longText("mt")->nullable();
            $table->integer("length")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('at_promo_mt_content');
    }
};
