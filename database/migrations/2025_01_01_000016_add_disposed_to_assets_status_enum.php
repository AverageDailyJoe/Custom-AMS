<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Drop existing PostgreSQL check constraint on assets status column if present
            DB::statement("ALTER TABLE assets DROP CONSTRAINT IF EXISTS assets_status_check;");
            
            // Add updated check constraint including 'disposed'
            DB::statement("ALTER TABLE assets ADD CONSTRAINT assets_status_check CHECK (status::text IN ('in_stock', 'checked_out', 'in_repair', 'archived', 'disposed'));");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE assets DROP CONSTRAINT IF EXISTS assets_status_check;");
            DB::statement("ALTER TABLE assets ADD CONSTRAINT assets_status_check CHECK (status::text IN ('in_stock', 'checked_out', 'in_repair', 'archived'));");
        }
    }
};
