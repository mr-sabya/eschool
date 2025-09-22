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
        Schema::create('exam_routines', function (Blueprint $table) {
            $table->id();
            // The parent exam this routine belongs to.
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');

            // The class and section this exam is for.
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');

            $table->foreignId('class_section_id')->constrained('class_sections')->onDelete('cascade');

            // Optional department for subject (e.g., Science, Arts).
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null'); // If department is deleted, keep the routine entry.

            // The specific subject for this exam.
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');

            // The predefined time slot for this exam.
            $table->foreignId('time_slot_id')->constrained('time_slots')->onDelete('cascade');

            // The exact date of this exam.
            $table->date('exam_date');

            // ClassRoom_id
            $table->foreignId('class_room_id')->constrained('class_rooms')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_routines');
    }
};
