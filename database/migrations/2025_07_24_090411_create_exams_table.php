<?php

use App\Models\AcademicSession;
use App\Models\Exam;
use App\Models\ExamCategory;
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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AcademicSession::class)
                ->constrained()
                ->cascadeOnDelete();
            
            $table->foreignIdFor(ExamCategory::class)
                ->constrained()
                ->cascadeOnDelete();

            // start end
            $table->date('start_at');
            $table->date('end_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
