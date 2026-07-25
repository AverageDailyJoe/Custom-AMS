<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_asets', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->date('request_date');
            $table->string('title');
            
            $table->string('requester_name');
            $table->string('requester_department');
            
            $table->string('item_type')->default('Laptop');
            $table->integer('quantity')->default(1);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            
            $table->text('reason');
            $table->text('specification_requested')->nullable();
            $table->decimal('estimated_cost', 15, 2)->nullable();
            
            $table->string('approver_name')->nullable()->default('SETYADI CANDRAWINATA');
            $table->string('approver_title')->nullable()->default('GM Finance & Operations');
            
            $table->json('attachments')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_asets');
    }
};
