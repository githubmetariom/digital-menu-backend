<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Financial\app\Models\Order;
use Modules\Shop\app\Models\Store;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_store', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();

            $table->uuid('store_id');
            $table->foreign('store_id')->references('id')->on('stores');

            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_store');
    }
};
