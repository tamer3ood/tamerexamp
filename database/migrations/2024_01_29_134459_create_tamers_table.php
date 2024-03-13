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
        Schema::create('tamers', function (Blueprint $table) {
            $table->id();
            // $table->string('code')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('talent_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedBigInteger('category_type_id')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'draft', 'underReview', 'inActive'])->default('draft');
            $table->tinyInteger('max_no_of_simultaneous_tamers')->default(3);
            $table->boolean('privacy_notice_agreement')->default(false);
            $table->boolean('term_of_service_agreement')->default(false);

            $table->boolean('has_basic_package')->default(false);
            $table->boolean('has_standard_package')->default(false);
            $table->boolean('has_advanced_package')->default(false);

            $table->string('basic_package_title')->nullable();
            $table->string('standard_package_title')->nullable();
            $table->string('advanced_package_title')->nullable();

            $table->string('basic_package_description')->nullable();
            $table->string('standard_package_description')->nullable();
            $table->string('advanced_package_description')->nullable();

            $table->decimal('basic_package_price')->nullable();
            $table->decimal('standard_package_price')->nullable();
            $table->decimal('advanced_package_price')->nullable();
            $table->unsignedBigInteger('likes')->nullable();
            $table->double('stars')->nullable();
            $table->unsignedInteger('reviews')->nullable();//number of client's reviews
            $table->unsignedTinyInteger('orders_in_queue')->nullable();
            $table->unsignedBigInteger('completed_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamers');
    }
};
