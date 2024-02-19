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
        Schema::create('tamer_order_add_ons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tamer_order_id');
            $table->unsignedBigInteger('tamer_id');
            $table->unsignedBigInteger('tamer_add_on_id');
            $table->enum('add_on_type', ['categoryElement', 'categoryService', 'customAddOn']);
            $table->unsignedBigInteger('category_eleserv_id')->nullable(); //category element or category service ID :)
            $table->tinyInteger('additional_days')->nullable();
            $table->decimal('unit_extra_price');
            $table->tinyInteger('quantity')->default(1);
            $table->decimal('total_extra_price');
            $table->string('tamer_title_for_custom_add_on')->nullable();
            $table->string('tamer_description_for_custom_add_on')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamer_order_add_ons');
    }
};
