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
        Schema::create('tamer_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('tamer_talent_id');
            $table->unsignedBigInteger('tamer_category_id');
            $table->unsignedBigInteger('tamer_sub_category_id');
            $table->unsignedBigInteger('tamer_category_type_id')->nullable();
            $table->unsignedBigInteger('tamer_id');
            $table->string('tamer_title');
            $table->text('tamer_description');
            $table->enum('tamer_order_package_type', ['basic', 'standard', 'advanced']);
            $table->string('tamer_package_title')->nullable();
            $table->string('tamer_package_description')->nullable();
            $table->decimal('tamer_package_price');
            $table->decimal('total_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamer_orders');
    }
};
