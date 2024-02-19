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
        Schema::create('tamer_requirements', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('tamer_id');
            $table->string('requirement_text');
            $table->enum('answer_type',['freeText','multipleChoice','fileAttachment']);
            $table->boolean('is_mandatory_requirement')->default(false);
            //for multiple choice
            $table->boolean('is_allow_more_than_one_answer_for_multi_choice')->default(false)->nullable();
            $table->string('firstـchoice_text')->nullable();
            $table->string('secondـchoice_text')->nullable();
            $table->string('thirdـchoice_text')->nullable();
            $table->string('fourthـchoice_text')->nullable();
            $table->string('fifthـchoice_text')->nullable();
            $table->string('sixthـchoice_text')->nullable();
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
        Schema::dropIfExists('tamer_requirements');
    }
};
