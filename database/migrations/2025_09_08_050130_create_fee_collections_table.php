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
        Schema::create('fee_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_class_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('class_section_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('fine', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->date('payment_date');

            // Reference to payment_methods table
            $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();

            $table->string('transaction_no')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_collections');
    }
};
