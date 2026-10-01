<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roomMappings = require __DIR__ . '/../../excel_room_mapping.php';

        foreach ($roomMappings as $tag => $room) {
            DB::table('assets')
                ->where('asset_tag', $tag)
                ->update(['room' => $room]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
