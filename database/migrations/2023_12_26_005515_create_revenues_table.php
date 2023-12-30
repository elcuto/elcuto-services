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
        Schema::create('Revenues', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('amount')->default(0.00);
            $table->integer('count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Revenues');
    }
};
