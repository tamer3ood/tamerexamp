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
        Schema::create('tamer_category_elements', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('tamer_id');
            $table->unsignedBigInteger('category_element_id');
            $table->tinyInteger('basic_pk_element_value');
            $table->tinyInteger('standard_pk_element_value')->nullable();
            $table->tinyInteger('advanced_pk_element_value')->nullable();
            $table->foreignId('tamer_id')
            ->constrained()
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamer_category_elements');
    }
};
