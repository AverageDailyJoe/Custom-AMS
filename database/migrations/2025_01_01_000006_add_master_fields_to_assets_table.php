<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('room')->nullable();
            $table->string('department')->nullable();
            $table->string('primary_user')->nullable();
            $table->string('secondary_user')->nullable();
            $table->string('processor')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage_hdd')->nullable();
            $table->string('storage_ssd')->nullable();
            $table->string('vga_card')->nullable();
            $table->string('monitor_id')->nullable();
            $table->string('monitor_spec')->nullable();
            $table->string('condition')->default('bagus');
            $table->integer('purchase_year')->nullable();
            $table->string('warranty')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'room',
                'department',
                'primary_user',
                'secondary_user',
                'processor',
                'ram',
                'storage_hdd',
                'storage_ssd',
                'vga_card',
                'monitor_id',
                'monitor_spec',
                'condition',
                'purchase_year',
                'warranty',
            ]);
        });
    }
};
