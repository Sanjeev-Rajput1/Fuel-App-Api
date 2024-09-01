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
        Schema::create('driver', function (Blueprint $table) {
            $table->id();
            $table->string('driver_category');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('expiration_date');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->text('address');
            $table->string('password');
            $table->string('upload_valid_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver');
    }
};
