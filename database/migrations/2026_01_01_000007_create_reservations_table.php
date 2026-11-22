<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->date('date');
            $table->string('time', 5);
            $table->integer('party_size');
            $table->integer('children_count')->default(0);
            $table->integer('high_chair_count')->default(0);
            $table->text('comments')->nullable();
            $table->string('status')->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
