<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            
            $table->foreignId('ticket_id')->nullable()->constrained('tickets')->nullOnDelete();
            $table->foreignId('pengajuan_aset_id')->nullable()->constrained('pengajuan_asets')->nullOnDelete();
            $table->foreignId('dispose_aset_id')->nullable()->constrained('dispose_asets')->nullOnDelete();
            $table->foreignId('berita_acara_id')->nullable()->constrained('berita_acaras')->nullOnDelete();
            
            $table->enum('maintenance_type', [
                'repair',
                'sparepart_replacement',
                'routine_service',
                'upgrade',
                'disposal',
            ])->default('repair');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->string('performed_by')->nullable();
            $table->date('performed_at');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance_logs');
    }
};
