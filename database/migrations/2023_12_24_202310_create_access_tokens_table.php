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
        Schema::create('AccessToken', function (Blueprint $table) {
            $table->id();
            $table->string('access_token')->nullable();
            $table->string('token_type')->nullable();
            $table->datetime('issued_at')->nullable();
            $table->string('expires_in')->nullable();
            $table->string('expiry_date')->nullable();
            $table->boolean('is_error')->default(false);
            $table->string('error_description')->nullable();
            $table->string('error')->nullable();
            $table->string('serviceid')->nullable();
            $table->string('offerid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AccessToken');
    }
};
