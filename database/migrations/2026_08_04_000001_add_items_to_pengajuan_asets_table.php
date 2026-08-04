<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->json('items')->nullable()->after('specification_requested');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->dropColumn('items');
        });
    }
};
