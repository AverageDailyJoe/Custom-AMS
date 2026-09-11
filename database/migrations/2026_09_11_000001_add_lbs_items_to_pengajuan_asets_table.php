<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->boolean('has_lbs')->default(false)->after('items');
            $table->json('lbs_items')->nullable()->after('has_lbs');
            $table->text('lbs_notes')->nullable()->after('lbs_items');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->dropColumn(['has_lbs', 'lbs_items', 'lbs_notes']);
        });
    }
};
