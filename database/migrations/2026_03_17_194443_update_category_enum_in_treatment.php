<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE treatment MODIFY COLUMN category ENUM(
            'Orthodontics','Endodontics','Aesthetics','Surgery',
            'Periodontics','Oral Surgery','Prosthodontics',
            'Implants','Whitening','Cleaning','Other'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE treatment MODIFY COLUMN category ENUM(
            'Orthodontics','Endodontics','Aesthetics','Surgery'
        ) NOT NULL");
    }
};