<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Financial\app\Models\Order;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedBigInteger('amount');
            $table->integer('discount')->nullable();
            $table->unsignedBigInteger('total');
            $table->unsignedBigInteger('amount_total');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
