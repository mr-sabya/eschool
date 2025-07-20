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
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('room_number')->unique();
            $table->string('room_name')->nullable();
            // room_type can be selected from a predefined list
            // e.g., 'Lecture', 'Laboratory', 'Seminar'
            $table->enum('room_type', ['Lecture', 'Laboratory', 'Seminar', 'Conference'])->default('Lecture');
            
            $table->integer('capacity')->default(30); // Default capacity of the classroom
            $table->boolean('is_active')->default(true); // Indicates if the classroom is currently in use
            $table->string('location')->nullable(); // e.g., 'Building A, Floor 2'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
