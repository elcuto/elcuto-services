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
        Schema::create('tb_product_idnpricepoints_raw', function (Blueprint $table) {
            $table->id();
            $table->string("product_id", 50)->nullable();
            $table->string("text", 150)->nullable();
            $table->string("price_point_id", 50)->nullable();
            $table->integer("cntt")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_product_idnpricepoints_raw');
    }
};
