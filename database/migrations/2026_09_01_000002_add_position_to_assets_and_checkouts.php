<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('position')->nullable()->after('department');
        });
        Schema::table('checkouts', function (Blueprint $table) {
            $table->string('position')->nullable()->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('position');
        });
        Schema::table('checkouts', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
