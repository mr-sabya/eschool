<?php

use App\Models\Designation;
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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            // user_id nullable for staff who are not users
            $table->foreignIdFor(User::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->string('first_name'); // Staff name
            $table->string('last_name'); // Staff name
            $table->string('email')->unique()->nullable(); // Optional email
            $table->string('phone')->nullable(); // Optional phone number
            $table->string('address')->nullable(); // Optional address
            $table->foreignIdFor(Designation::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null'); // Optional designation, can be null if not applicable

            $table->date('date_of_joining')->nullable(); // Optional date of joining
            $table->date('date_of_birth')->nullable(); // Optional date of birth
            // nid is optional, can be null if not applicable
            $table->string('nid')->nullable()->unique();
            // Optional profile picture, can be null if not provided
            $table->string('profile_picture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
