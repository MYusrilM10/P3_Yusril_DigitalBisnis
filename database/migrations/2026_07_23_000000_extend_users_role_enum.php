<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend role enum to include 'admin' so middleware IsAdmin works.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'superadmin', 'panitia') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'panitia', 'user') NOT NULL DEFAULT 'user'");
    }
};
