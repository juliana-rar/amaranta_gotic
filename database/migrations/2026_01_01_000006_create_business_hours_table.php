<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();
            $table->integer('day_order')->unique();
            $table->boolean('is_open')->default(true);
            $table->string('shift1_start', 5)->nullable();
            $table->string('shift1_end', 5)->nullable();
            $table->string('shift2_start', 5)->nullable();
            $table->string('shift2_end', 5)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_hours');
    }
};
