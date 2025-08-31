<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Preserve existing free-text payment_method by renaming it
            if (Schema::hasColumn('payments', 'payment_method')) {
                $table->renameColumn('payment_method', 'payment_method_text');
            }

            if (!Schema::hasColumn('payments', 'payment_method_id')) {
                $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            }

            if (!Schema::hasColumn('payments', 'study_year_id')) {
                $table->foreignId('study_year_id')->nullable()->constrained('study_years')->nullOnDelete();
            }

            if (!Schema::hasColumn('payments', 'year')) {
                $table->string('year')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payment_method_id')) {
                $table->dropForeign(['payment_method_id']);
                $table->dropColumn('payment_method_id');
            }

            if (Schema::hasColumn('payments', 'study_year_id')) {
                $table->dropForeign(['study_year_id']);
                $table->dropColumn('study_year_id');
            }

            if (Schema::hasColumn('payments', 'year')) {
                $table->dropColumn('year');
            }

            if (Schema::hasColumn('payments', 'payment_method_text')) {
                $table->renameColumn('payment_method_text', 'payment_method');
            }
        });
    }
};
