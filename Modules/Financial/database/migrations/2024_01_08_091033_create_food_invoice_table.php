<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Financial\app\Models\Invoice;
use Modules\Shop\app\Models\Food;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_invoice', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('food_id');
            $table->foreign('food_id')->references('id')->on('foods');
            $table->uuid('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_food');
    }
};
