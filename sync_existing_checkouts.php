<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Asset;
use App\Models\Checkout;

$assets = Asset::where(function($q) {
    $q->where('status', 'checked_out')->orWhereNotNull('primary_user');
})->get();

foreach ($assets as $asset) {
    if (!$asset->currentCheckout()->exists()) {
        Checkout::create([
            'asset_id' => $asset->id,
            'primary_user' => $asset->primary_user ?: 'Pengguna Asset',
            'secondary_user' => $asset->secondary_user,
            'checked_out_at' => $asset->updated_at ?? now(),
            'checkout_notes' => 'Log sinkronisasi checkout awal',
        ]);
        echo "Backfilled checkout for Asset Tag: {$asset->asset_tag}\n";
    }
}

echo "Checkout history sync complete!\n";
