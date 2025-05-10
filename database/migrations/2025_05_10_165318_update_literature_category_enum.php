<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Modify the ENUM to include 'report'
        DB::statement("ALTER TABLE literatures MODIFY category ENUM('poem', 'research', 'book', 'article', 'report')");
    }

    public function down()
    {
        // Revert back to the original ENUM values
        DB::statement("ALTER TABLE literatures MODIFY category ENUM('poem', 'research', 'book', 'article')");
    }
};
