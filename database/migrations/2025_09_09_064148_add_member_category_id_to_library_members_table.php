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
        Schema::table('library_members', function (Blueprint $table) {
            $table->unsignedBigInteger('member_category_id')->after('user_id');

            $table->foreign('member_category_id')
                ->references('id')
                ->on('member_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('library_members', function (Blueprint $table) {
            $table->dropForeign(['member_category_id']);
            $table->dropColumn('member_category_id');
        });
    }
};
