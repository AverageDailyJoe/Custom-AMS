<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->string('area')->nullable()->after('requester_department');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_asets', function (Blueprint $table) {
            $table->dropColumn('area');
        });
    }
};
