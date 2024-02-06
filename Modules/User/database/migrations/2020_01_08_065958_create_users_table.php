<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('name');
            $table->string('family');
            $table->string('mobile');
            $table->string('email');
            $table->string('national_code');
            $table->string('referral_code')->unique();
            $table->date('date_of_birth')->nullable();
            $table->string('thumbnail')->nullable();
            $table->uuid('referral_id')->nullable();
            $table->foreign('referral_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
