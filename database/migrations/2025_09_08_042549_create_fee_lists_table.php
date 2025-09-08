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
        Schema::create('fee_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_type_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->foreignId('school_class_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('class_section_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('due_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_lists');
    }
};
