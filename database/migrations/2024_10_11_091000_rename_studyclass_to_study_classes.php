<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // If the oddly named table exists, rename it to canonical 'study_classes'
        if (Schema::hasTable('Studyclass') && !Schema::hasTable('study_classes')) {
            Schema::rename('Studyclass', 'study_classes');
        }

        // If there is a table named 'studyclass' (lowercase) also rename
        if (Schema::hasTable('studyclass') && !Schema::hasTable('study_classes')) {
            Schema::rename('studyclass', 'study_classes');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('study_classes') && !Schema::hasTable('Studyclass')) {
            Schema::rename('study_classes', 'Studyclass');
        }
    }
};
