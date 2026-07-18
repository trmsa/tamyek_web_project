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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('category');
            $table->string('type');
            $table->text('images');
            $table->string('meta_description');
            $table->text('keywords');
            $table->dateTime('published')->nullable();
            $table->string('auther');
            $table->integer('view_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->double('likes')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
