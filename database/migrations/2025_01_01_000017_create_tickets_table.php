<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            
            // Reporter & Location
            $table->string('reporter_name');
            $table->string('reporter_department');
            $table->string('contact_number')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('room')->nullable();
            
            // Asset Connection
            $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->string('asset_tag')->nullable();
            $table->string('asset_name')->nullable();
            
            // Category & Issues
            $table->enum('category', [
                'hardware',
                'software',
                'network_wifi',
                'printer_scanner',
                'access_rights',
                'scheduled_service',
                'other',
            ])->default('hardware');
            
            $table->string('subject');
            $table->text('description');
            
            // Schedule & SLA
            $table->date('scheduled_date');
            $table->string('scheduled_time_slot')->default('09:00 - 12:00');
            $table->date('due_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // IT Assignment & Status
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('assigned_to_name')->nullable();
            $table->enum('status', [
                'open',
                'scheduled',
                'in_progress',
                'pending_sparepart',
                'resolved',
                'closed',
                'rescheduled',
            ])->default('open');
            
            $table->text('reschedule_reason')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            
            // Procurement, Disposal & Berita Acara Relations
            $table->foreignId('pengajuan_aset_id')->nullable()->constrained('pengajuan_asets')->nullOnDelete();
            $table->foreignId('dispose_aset_id')->nullable()->constrained('dispose_asets')->nullOnDelete();
            $table->foreignId('berita_acara_id')->nullable()->constrained('berita_acaras')->nullOnDelete();
            
            $table->json('attachments')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
