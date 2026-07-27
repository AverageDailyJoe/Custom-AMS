<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispose_asets', function (Blueprint $table) {
            $table->id();
            $table->string('disposal_number')->unique();
            $table->date('disposal_date');
            
            $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->string('asset_tag');
            $table->string('asset_name');
            
            $table->text('disposal_reason');
            $table->enum('disposal_type', ['sale', 'destruction', 'trade_in', 'scrap'])->default('sale');
            $table->enum('status', ['pending', 'approved', 'transferred_to_ga', 'completed'])->default('pending');
            
            $table->decimal('estimated_salvage_value', 15, 2)->nullable();
            
            $table->string('created_by_name')->default('Bambang Yulianto');
            $table->string('spv_name')->nullable()->default('Supervisor IT');
            $table->string('manager_name')->nullable()->default('SETYADI CANDRAWINATA');
            $table->string('ga_recipient_name')->nullable()->default('General Affairs (GA)');
            
            $table->json('attachments')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispose_asets');
    }
};
