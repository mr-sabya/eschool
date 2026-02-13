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
        Schema::create('admission_infos', function (Blueprint $table) {
            $table->id();
            $table->longText('content'); // Stores the HTML/Text details
            $table->string('form_path')->nullable(); // Stores the PDF file path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_infos');
    }
};
