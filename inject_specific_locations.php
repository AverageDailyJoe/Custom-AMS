<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== INJECTING LOKASI SPESIFIK (RUANGAN) TO ASSETS ===\n";

$roomMappings = [
    // Image 1: GTK-01-01 & GTK-01-03
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

    // Image 3: GTK-02-03, GTK-03-01 & GTK-03-03
    'GTK-02-03-60' => 'RUANGAN FINANCE & ACCOUNTING',
    'GTK-02-03-61' => 'LEMARI IT',
    'GTK-02-03-62' => 'RUANGAN MARKETING',
    'GTK-02-03-63' => 'LEMARI IT',
    'GTK-02-03-64' => 'RUANGAN PRODUKSI',
    'GTK-03-01-01' => 'RUANGAN OPERASIONAL BMS',
    'GTK-03-01-02' => 'GUDANG JAKARTA',
    'GTK-03-01-03' => 'GUDANG JAKARTA',
    'GTK-03-01-04' => 'GUDANG JAKARTA',
    'GTK-03-01-05' => 'RUANGAN LIVESTREAMING',
    'GTK-03-01-06' => 'GUDANG MEDAN',
    'GTK-03-01-07' => 'GUDANG MEDAN',
    'GTK-03-01-08' => 'GUDANG MAKASSAR',
    'GTK-03-01-09' => 'GUDANG MAKASSAR',
    'GTK-03-01-10' => 'GUDANG BANJARMASIN',
    'GTK-03-01-11' => 'GUDANG BANJARMASIN',
    'GTK-03-01-12' => 'GUDANG PONTIANAK',
    'GTK-03-01-13' => 'GUDANG PONTIANAK',
    'GTK-03-01-14' => 'GUDANG PEKANBARU',
    'GTK-03-01-15' => 'GUDANG PEKANBARU',
    'GTK-03-01-16' => 'GUDANG PALEMBANG',
    'GTK-03-01-17' => 'GUDANG PALEMBANG',
    'GTK-03-01-18' => 'GUDANG DENPASAR',
    'GTK-03-01-19' => 'GUDANG DENPASAR',
    'GTK-03-01-20' => 'GUDANG SURABAYA',
    'GTK-03-01-21' => 'GUDANG SURABAYA',
    'GTK-03-01-22' => 'RUANGAN LIVESTREAMING',
    'GTK-03-01-23' => 'RUANGAN LIVESTREAMING',
    'GTK-03-03-01' => 'LEMARI IT',
    'GTK-03-03-02' => 'RUANGAN FINANCE & ACCOUNTING',
];

$updatedCount = 0;

foreach ($roomMappings as $tag => $room) {
    $affected = DB::table('assets')
        ->where('asset_tag', $tag)
        ->update(['room' => $room]);

    if ($affected > 0) {
        echo "✅ Updated {$tag} -> {$room}\n";
        $updatedCount++;
    } else {
        echo "⚠️ Tag {$tag} not found or unchanged\n";
    }
}

echo "\nInjection complete. Total assets updated: {$updatedCount}\n";
