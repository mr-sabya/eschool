<?php

use App\Models\ClassSection;
use App\Models\SchoolClass;
use App\Models\Shift;
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
        Schema::create('subject_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SchoolClass::class)
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(ClassSection::class)
                ->constrained()
                ->onDelete('cascade');

            // shift_id is optional, can be null if not applicable
            $table->foreignIdFor(Shift::class)
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            
            $table->integer('status')->default(1); // 1 for active, 0 for inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_assigns');
    }
};
