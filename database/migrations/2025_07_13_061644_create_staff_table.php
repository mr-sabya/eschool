<?php

use App\Models\Department;
use App\Models\Designation;
use App\Models\Gender;
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

            // staff id
            $table->string('staff_id')->unique(); // Unique staff ID
            // department_id is optional, can be null if not applicable
            $table->foreignIdFor(Department::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->string('first_name'); // Staff name
            $table->string('last_name'); // Staff name

            // father name
            $table->string('father_name')->nullable(); // Optional father name
            // mother name
            $table->string('mother_name')->nullable(); // Optional mother name

            $table->string('email')->unique()->nullable(); // Optional email, can be null if not provided
            $table->string('phone')->nullable(); // Optional phone number
            // nid is optional, can be null if not applicable
            $table->string('nid')->nullable()->unique();
            $table->date('date_of_birth')->nullable(); // Optional date of birth
            $table->string('current_address')->nullable(); // Optional current address
            $table->string('permanent_address')->nullable(); // Optional permanent address

            $table->foreignIdFor(Designation::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null'); // Optional designation, can be null if not applicable

            // gender_id is optional, can be null if not applicable
            $table->foreignIdFor(Gender::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            // marital status, can be null if not applicable
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])
                ->nullable()
                ->default('single'); // Default to 'single' if not specified
            // basic salary, can be null if not applicable
            $table->decimal('basic_salary', 10, 2)->nullable(); // Optional
            
            $table->date('date_of_joining')->nullable(); // Optional date of joining
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
