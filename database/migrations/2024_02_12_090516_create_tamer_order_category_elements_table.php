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
        Schema::create('tamer_order_category_elements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tamer_id');
            $table->unsignedBigInteger('tamer_order_id');
            $table->unsignedBigInteger('category_element_id');
            $table->unsignedBigInteger('category_element_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamer_order_category_elements');
    }
};
