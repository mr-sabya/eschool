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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('school_address')->nullable();
            $table->string('school_email')->nullable();
            $table->string('school_phone')->nullable();

            // school history
            $table->text('school_history')->nullable();

            // eiin no
            $table->string('eiin_no')->nullable();

            // school code
            $table->string('school_code')->nullable();

            // reg. no
            $table->string('registration_no')->nullable();

            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            // copy right
            $table->string('copyright')->nullable();
            

            $table->string('timezone')->default('UTC');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
