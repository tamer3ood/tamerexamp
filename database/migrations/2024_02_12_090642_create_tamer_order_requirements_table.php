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
        Schema::create('tamer_order_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tamer_id');
            $table->unsignedBigInteger('tamer_order_id');
            $table->unsignedBigInteger('tamer_requirement_id');

            $table->string('requirement_text');
            $table->string('answer_type');
            $table->string('answer_text');

            //for multiple choice
            $table->boolean('is_allow_more_than_one_answer_for_multi_choice')->default(false)->nullable();
            $table->string('firstـchoice_text')->nullable();
            $table->boolean('is_firstـchoice_text_selected')->nullable();
            $table->string('secondـchoice_text')->nullable();
            $table->string('is_secondـchoice_text_selected')->nullable();
            $table->string('thirdـchoice_text')->nullable();
            $table->string('is_thirdـchoice_text_selected')->nullable();
            $table->string('fourthـchoice_text')->nullable();
            $table->string('is_fourthـchoice_text_selected')->nullable();
            $table->string('fifthـchoice_text')->nullable();
            $table->string('is_fifthـchoice_text_selected')->nullable();
            $table->string('sixthـchoice_text')->nullable();
            $table->string('is_sixthـchoice_text_selected')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamer_order_requirements');
    }
};
