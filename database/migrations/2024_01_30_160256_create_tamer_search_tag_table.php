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
        Schema::create('tamer_search_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('search_tag_id');
            // $table->unsignedBigInteger('tamer_id');
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
        Schema::dropIfExists('tamer_search_tag');
    }
};
