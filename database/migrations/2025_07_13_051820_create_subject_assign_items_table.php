<?php

use App\Models\Subject;
use App\Models\SubjectAssign;
use App\Models\User;
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
        Schema::create('subject_assign_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubjectAssign::class)
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(Subject::class)
                ->constrained()
                ->onDelete('cascade');

            // teacher_id
            $table->foreignIdFor(User::class, 'teacher_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_assign_items');
    }
};
