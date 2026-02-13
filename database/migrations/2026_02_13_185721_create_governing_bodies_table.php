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
        Schema::create('governing_bodies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('image')->nullable();
            $table->integer('rank')->default(0); // For sorting (e.g., 1 for Chairman, 2 for Secretary)
            $table->string('mobile')->nullable();
            $table->enum('type', ['current', 'former'])->default('current');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governing_bodies');
    }
};
