<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Skip this migration for SQL Server - json_unquote is MySQL-specific
        // SQL Server uses JSON_VALUE() function natively for JSON extraction
        $connection = config('database.default');
        
        if ($connection === 'sqlsrv') {
            // SQL Server doesn't need this function - use JSON_VALUE() instead
            return;
        }
        
        // For MySQL, create the json_unquote shim
        DB::unprepared('DROP FUNCTION IF EXISTS json_unquote');
        DB::unprepared("CREATE FUNCTION json_unquote(input TEXT) RETURNS TEXT DETERMINISTIC RETURN TRIM(BOTH '\"' FROM input);");
    }

    public function down(): void
    {
        $connection = config('database.default');
        
        if ($connection === 'sqlsrv') {
            return;
        }
        
        DB::unprepared('DROP FUNCTION IF EXISTS json_unquote;');
    }
};
