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
        Schema::create('library_members', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->unique(); // Library Card ID
            $table->enum('user_type', ['student', 'teacher']);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK to users table
            $table->date('join_date')->nullable();
            $table->date('expire_date')->nullable(); // NEW: Membership expiry date
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_members');
    }
};
