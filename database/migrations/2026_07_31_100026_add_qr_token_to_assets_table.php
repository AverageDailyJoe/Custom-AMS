<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('assets', 'qr_token')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->string('qr_token')->nullable()->unique()->after('asset_tag');
            });
        }

        // Backfill existing assets
        $assets = \App\Models\Asset::whereNull('qr_token')->orWhere('qr_token', '')->get();
        foreach ($assets as $asset) {
            $asset->qr_token = (string) \Illuminate\Support\Str::uuid();
            $asset->saveQuietly(); // save without triggering events
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('assets', 'qr_token')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->dropColumn('qr_token');
            });
        }
    }
};
