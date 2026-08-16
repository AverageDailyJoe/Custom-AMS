<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_asets', 'shipping_cost')) {
                $table->decimal('shipping_cost', 15, 2)->default(0)->after('items');
            }
            if (!Schema::hasColumn('pengajuan_asets', 'service_fee')) {
                $table->decimal('service_fee', 15, 2)->default(0)->after('shipping_cost');
            }
            if (!Schema::hasColumn('pengajuan_asets', 'other_fee')) {
                $table->decimal('other_fee', 15, 2)->default(0)->after('service_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('pengajuan_asets', 'shipping_cost')) $cols[] = 'shipping_cost';
            if (Schema::hasColumn('pengajuan_asets', 'service_fee')) $cols[] = 'service_fee';
            if (Schema::hasColumn('pengajuan_asets', 'other_fee')) $cols[] = 'other_fee';
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
