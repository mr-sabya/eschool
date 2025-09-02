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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Notice title
            $table->text('description')->nullable(); // Notice details
            $table->string('attachment')->nullable(); // Optional file attachment (PDF, image)

            $table->enum('notice_type', ['general', 'exam', 'holiday', 'event'])->default('general'); // Type of notice
            $table->date('start_date')->nullable(); // Start date
            $table->date('end_date')->nullable(); // End date (if applicable)

            $table->boolean('is_active')->default(true); // Active or inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
