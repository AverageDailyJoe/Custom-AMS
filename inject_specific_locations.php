<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== INJECTING ALL 235 LOKASI SPESIFIK (RUANGAN) FROM EXCEL SHEET ===\n";

$roomMappings = require __DIR__ . '/excel_room_mapping.php';

$updatedCount = 0;
$notFoundCount = 0;

foreach ($roomMappings as $tag => $room) {
    $affected = DB::table('assets')
        ->where('asset_tag', $tag)
        ->update(['room' => $room]);

    if ($affected > 0) {
        echo "✅ Updated {$tag} -> {$room}\n";
        $updatedCount++;
    } else {
        echo "⚠️ Tag {$tag} not found or room value already set to {$room}\n";
        $notFoundCount++;
    }
}

echo "\nInjection complete!\n";
echo "Total updated: {$updatedCount}\n";
echo "Total unchanged / not found: {$notFoundCount}\n";
