<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->default('');
            $table->string('image_url')->default('');
            $table->string('category')->default('');
            $table->string('price_text')->default('');
            $table->boolean('show_on_home')->default(false);
            $table->integer('display_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
