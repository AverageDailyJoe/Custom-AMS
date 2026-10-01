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
        $roomMappings = [
            'GTK-01-01-01' => 'OFFICE ATAS',
            'GTK-01-01-02' => 'RUANGAN FINANCE & ACCOUNTING',
            'GTK-01-01-03' => 'OFFICE ATAS',
            'GTK-01-01-04' => 'OFFICE ATAS',
            'GTK-01-01-05' => 'OFFICE ATAS',
            'GTK-01-01-06' => 'OFFICE ATAS',
            'GTK-01-01-07' => 'RUANGAN PRODUKSI',
            'GTK-01-01-08' => 'RUANGAN PRODUKSI',
            'GTK-01-01-09' => 'RUANGAN STICKER',
            'GTK-01-01-10' => 'GUDANG PM',
            'GTK-01-01-11' => 'GUDANG PM',
            'GTK-01-01-12' => 'RUANGAN FACTORY GOODS',
            'GTK-01-01-13' => 'RUANGAN STICKER',
            'GTK-01-01-14' => 'RUANGAN FACTORY GOODS',
            'GTK-01-01-15' => 'RUANGAN QUALITY CONTROL',
            'GTK-01-01-16' => 'RUANGAN IT',
            'GTK-01-01-17' => 'RUANGAN QUALITY CONTROL',
            'GTK-01-01-18' => 'RUANGAN QUALITY ASSURANCE',
            'GTK-01-01-19' => 'RUANGAN IT',
            'GTK-01-01-20' => 'RUANGAN IT',
            'GTK-01-01-21' => 'RUANGAN SERVER',
            'GTK-01-01-22' => 'RUANGAN SERVER',
            'GTK-01-03-01' => 'RUANGAN BUSINESS DEVELOPMENT',
            'GTK-01-03-02' => 'OFFICE ATAS',
        ];

        foreach ($roomMappings as $tag => $room) {
            DB::table('assets')
                ->where('asset_tag', $tag)
                ->where(function ($q) {
                    $q->whereNull('room')->orWhere('room', '');
                })
                ->update(['room' => $room]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for data injection
    }
};
