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
        Schema::table('final_mark_configurations', function (Blueprint $table) {
            $table->integer('grading_scale')->default(100)->after('final_result_weight_percentage');
            $table->boolean('exclude_from_gpa')->default(false)->after('grading_scale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('final_mark_configurations', function (Blueprint $table) {
            $table->dropColumn('grading_scale');
            $table->dropColumn('exclude_from_gpa');
        });
    }
};
