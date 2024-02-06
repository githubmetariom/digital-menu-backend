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
        Schema::create('foods', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('slug');
            $table->uuid('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedBigInteger('price');
            $table->integer('depot');
            $table->string('thumbnail')->nullable();
            $table->integer('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
