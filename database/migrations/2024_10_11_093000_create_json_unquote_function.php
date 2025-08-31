<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
    // Create a simple json_unquote shim that strips surrounding double-quotes
    // Use a single-statement CREATE FUNCTION with RETURN (compatible with PDO)
    $sql = "DROP FUNCTION IF EXISTS json_unquote; CREATE FUNCTION json_unquote(input TEXT) RETURNS TEXT DETERMINISTIC RETURN TRIM(BOTH '\"' FROM input);";

    DB::unprepared($sql);
    }

    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS json_unquote;');
    }
};
