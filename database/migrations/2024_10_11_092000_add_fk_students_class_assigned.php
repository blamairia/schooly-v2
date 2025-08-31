<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // only add foreign key if column exists and fk not already present
            if (Schema::hasColumn('students', 'class_assigned_id')) {
                // ensure column is unsigned big integer and nullable to match referenced primary key
                $table->unsignedBigInteger('class_assigned_id')->nullable()->change();

                // try to add FK if table exists
                if (Schema::hasTable('study_classes')) {
                    // adding constraint safely using blueprint
                    $table->foreign('class_assigned_id')->references('id')->on('study_classes')->onDelete('set null');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // drop foreign key if exists
            $table->dropForeign(['class_assigned_id']);
        });
    }
};
