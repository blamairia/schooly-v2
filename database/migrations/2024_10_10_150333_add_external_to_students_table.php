<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('students', function (Blueprint $table) {
        $table->boolean('external')->default(false); // Adds a new boolean field with a default value of false
    });
}

public function down()
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn('external'); // Drops the external column if the migration is rolled back
    });
}

};
