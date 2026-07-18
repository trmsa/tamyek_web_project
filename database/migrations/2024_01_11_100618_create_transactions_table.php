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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('transaction_id');
            $table->text('token')->nullable();
            $table->string('rrn')->nullable();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('final_price_products');
            $table->unsignedBigInteger('postal_price');
            $table->string('gateway');
            $table->string('ip');
            $table->text('products')->nullable();
            $table->boolean('status')->default('0');
            $table->dateTime('date_payment')->nullable();
            $table->dateTime('date_send')->nullable();
            $table->string('shipment_code')->nullable();
            $table->string('type')->nullable();
            $table->string('origin')->nullable();
            $table->string('send_way')->nullable();
            $table->string('send_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
