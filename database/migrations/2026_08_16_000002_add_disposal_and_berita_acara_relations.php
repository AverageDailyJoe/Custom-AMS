<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispose_asets', function (Blueprint $table) {
            if (!Schema::hasColumn('dispose_asets', 'buyer_name')) {
                $table->string('buyer_name')->nullable()->after('estimated_salvage_value');
            }
            if (!Schema::hasColumn('dispose_asets', 'buyer_contact')) {
                $table->string('buyer_contact')->nullable()->after('buyer_name');
            }
            if (!Schema::hasColumn('dispose_asets', 'buyer_address')) {
                $table->string('buyer_address')->nullable()->after('buyer_contact');
            }
            if (!Schema::hasColumn('dispose_asets', 'selling_price')) {
                $table->decimal('selling_price', 15, 2)->nullable()->after('buyer_address');
            }
            if (!Schema::hasColumn('dispose_asets', 'berita_acara_id')) {
                $table->foreignId('berita_acara_id')->nullable()->after('attachments')->constrained('berita_acaras')->nullOnDelete();
            }
        });

        Schema::table('berita_acaras', function (Blueprint $table) {
            if (!Schema::hasColumn('berita_acaras', 'dispose_aset_id')) {
                $table->foreignId('dispose_aset_id')->nullable()->after('asset_id')->constrained('dispose_asets')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dispose_asets', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('dispose_asets', 'berita_acara_id')) {
                $table->dropForeign(['berita_acara_id']);
                $cols[] = 'berita_acara_id';
            }
            if (Schema::hasColumn('dispose_asets', 'buyer_name')) $cols[] = 'buyer_name';
            if (Schema::hasColumn('dispose_asets', 'buyer_contact')) $cols[] = 'buyer_contact';
            if (Schema::hasColumn('dispose_asets', 'buyer_address')) $cols[] = 'buyer_address';
            if (Schema::hasColumn('dispose_asets', 'selling_price')) $cols[] = 'selling_price';
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        Schema::table('berita_acaras', function (Blueprint $table) {
            if (Schema::hasColumn('berita_acaras', 'dispose_aset_id')) {
                $table->dropForeign(['dispose_aset_id']);
                $table->dropColumn('dispose_aset_id');
            }
        });
    }
};
