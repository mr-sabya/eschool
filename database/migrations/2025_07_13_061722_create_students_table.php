<?php

use App\Models\Blood;
use App\Models\ClassSection;
use App\Models\Gender;
use App\Models\Religion;
use App\Models\SchoolClass;
use App\Models\Shift;
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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // user_id
            $table->foreignIdFor(User::class)
                ->constrained()
                ->onDelete('cascade');

            // Additional fields for student details
            $table->string('first_name');
            $table->string('last_name');
            
            // roll number
            $table->string('roll_number')->unique()->nullable(); // Unique roll number, can
            // class_id
            $table->foreignIdFor(SchoolClass::class)
                ->constrained()
                ->onDelete('cascade'); // Foreign key to SchoolClass, on delete cascade
            
            // section
            $table->foreignIdFor(ClassSection::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null'); // Foreign key to ClassSection, can be null if not applicable
            
            // shift_id
            $table->foreignIdFor(Shift::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null'); // Foreign key to Shift, can be null if not

            // guardian id
            $table->foreignIdFor(User::class, 'guardian_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null'); // Foreign key to User for parent, can be null if not applicable

            // Optional fields
            $table->string('email')->unique()->nullable(); // Optional email
            $table->string('phone')->nullable(); // Optional phone number
            $table->string('address')->nullable(); // Optional address
            $table->date('date_of_birth')->nullable(); // Optional date of birth
            // admission date
            $table->date('admission_date')->nullable(); // Optional admission date
            // admission number
            $table->string('admission_number')->unique()->nullable(); // Unique admission number,

            // student category
            $table->string('category')->nullable(); // Optional category, e.g., regular, scholarship, etc.

            // Foreign keys for additional details
            $table->foreignIdFor(Gender::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null'); // Foreign key to

            $table->foreignIdFor(Blood::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null'); // Foreign key to Blood, can be null if not

            $table->foreignIdFor(Religion::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null'); // Foreign key to Religion, can be null if not applicable

            $table->string('national_id')->unique()->nullable(); // Optional national ID
            // place of birth
            $table->string('place_of_birth')->nullable(); // Optional place of birth
            $table->string('nationality')->nullable(); // Optional
            $table->string('language')->nullable(); // Optional primary language spoken
            // heaslth status
            $table->text('health_status')->nullable(); // Optional health status description

            // rank in family
            $table->integer('rank_in_family')->nullable(); // Optional rank in family, e.g., first child, second child, etc.

            // number of siblings
            $table->integer('number_of_siblings')->nullable(); // Optional number of siblings

            // profile picture
            $table->string('profile_picture')->nullable(); // Optional profile picture

            // emergency contact
            $table->string('emergency_contact_name')->nullable(); // Optional emergency contact name
            $table->string('emergency_contact_phone')->nullable(); // Optional emergency contact phone number

            // is previous school attended
            $table->boolean('previous_school_attended')->default(false); // Indicates if the student
            // previous school
            $table->string('previous_school')->nullable(); // Optional previous school attended
            // previous school document
            $table->string('previous_school_document')->nullable(); // Optional document from previous school

            // Additional fields for student management
            $table->boolean('is_active')->default(true); // Active status of the student
            $table->text('notes')->nullable(); // Optional notes for the student
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
