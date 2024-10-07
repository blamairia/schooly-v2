<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTotalsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_totals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_type_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2)->default(0); // Total amount paid
            $table->decimal('total_due', 10, 2)->default(0); // Total amount due
            $table->integer('parts_paid')->default(0); // Number of parts paid
            $table->timestamps(); // Created at and updated at
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_totals');
    }
}
