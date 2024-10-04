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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('part_number');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_due', 10, 2);
            $table->decimal('amount_paid', 10, 2);
            $table->date('due_date')->nullable();
            $table->string('status');
            $table->foreignId('division_plan_id')->constrained();
            $table->foreignId('payment_type_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->string('payment_method');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
