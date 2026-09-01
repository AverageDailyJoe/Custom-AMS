<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->string('department')->nullable()->after('secondary_user');
            $table->foreignId('location_id')->nullable()->after('department')->constrained('locations')->nullOnDelete();
            $table->string('room')->nullable()->after('location_id');
        });
    }

    public function down(): void
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn(['department', 'location_id', 'room']);
        });
    }
};
