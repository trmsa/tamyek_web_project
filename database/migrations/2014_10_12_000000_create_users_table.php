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
            $table->string('name')->nullable();
            $table->string('mobile', 11)->unique();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('national_code')->nullable();
            $table->string('birth_date')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('plaque')->nullable();
            $table->string('verify_sms')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('auth_api_pay')->nullable();
            $table->string('avatar')->nullable();
            $table->text('favorits')->nullable();
            $table->text('shoping_cart')->nullable();
            $table->string('type')->unique()->nullable();
            $table->rememberToken();
            $table->softDeletes();
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
