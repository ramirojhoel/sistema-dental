<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE medical_appointments MODIFY COLUMN state ENUM('Pending','Completed','Cancelled') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE medical_appointments MODIFY COLUMN state ENUM('Pending','Completed','Canceled') NOT NULL");
    }
};