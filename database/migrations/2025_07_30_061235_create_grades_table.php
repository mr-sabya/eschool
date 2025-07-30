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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('grade_name'); // A+, A, B, etc.
            $table->decimal('grade_point', 4, 2); // 5.00, 4.00, etc.
            $table->integer('start_marks'); // e.g. 80
            $table->integer('end_marks');   // e.g. 100
            $table->string('remarks')->nullable(); // e.g. "Excellent"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
