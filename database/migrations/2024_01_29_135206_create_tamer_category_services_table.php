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
        Schema::create('tamer_category_services', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('tamer_id');
            $table->unsignedBigInteger('category_service_id');
            $table->boolean('is_basic_pk');
            $table->boolean('is_standard_pk')->nullable();
            $table->boolean('is_advanced_pk')->nullable();
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
        Schema::dropIfExists('tamer_category_services');
    }
};
