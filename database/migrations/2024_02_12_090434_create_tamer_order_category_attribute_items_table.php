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
        Schema::create('tamer_order_category_attribute_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tamer_id');
            $table->unsignedBigInteger('tamer_order_id');
            $table->unsignedBigInteger('category_attribute_item_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamer_order_category_attribute_items');
    }
};
