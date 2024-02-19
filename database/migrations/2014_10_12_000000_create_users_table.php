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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_social_photo')->default(0);
            $table->text('bio')->nullable();
            $table->float('rating')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->boolean('is_email_verified')->default(0);
            $table->string('account_verification_code')->nullable();
            $table->string('reset_password_code')->nullable();
            $table->string('password');
            $table->enum('gender',['male','female'])->nullable();
            $table->enum('account_type',['client','talent','clientTalent','admin'])->nullable();
            $table->boolean('is_online')->default(0);
            $table->enum('status',['active','inActive'])->default('active');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('registered_ip')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->decimal('balance',5,2)->default(0);
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->unsignedBigInteger('security_question_id')->nullable();
            $table->string('security_question')->nullable();
            $table->string('security_question_answer')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
