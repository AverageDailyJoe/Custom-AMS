<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('items');
            $table->decimal('service_fee', 15, 2)->default(0)->after('shipping_cost');
            $table->decimal('other_fee', 15, 2)->default(0)->after('service_fee');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->dropColumn(['shipping_cost', 'service_fee', 'other_fee']);
        });
    }
};
