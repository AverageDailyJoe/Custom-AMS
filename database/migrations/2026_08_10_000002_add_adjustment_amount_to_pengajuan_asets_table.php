<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->decimal('adjustment_amount', 15, 2)->default(0)->nullable()->after('additional_fees');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->dropColumn('adjustment_amount');
        });
    }
};
