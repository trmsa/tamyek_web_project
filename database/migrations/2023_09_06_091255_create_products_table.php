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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('images')->nullable();
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discounted_price')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->double('inventory')->default(0);
            $table->string('unit')->nullable();
            $table->double('min_order')->default(0);
            $table->string('type')->default('khorde');
            $table->bigInteger('total_price_sales')->default(0);
            $table->integer('sales_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->double('likes')->default(0);
            $table->bigInteger('view_count')->default(0);
            $table->enum('discount_type', ['public_percent', 'public_constant', 'code_percent', 'code_constant'])->nullable();
            $table->string('discount_code')->nullable();
            $table->dateTime('discount_begin')->nullable();
            $table->dateTime('discount_expire')->nullable();
            $table->unsignedBigInteger('discount_amount')->nullable();
            $table->text('links')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('other')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
