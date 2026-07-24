<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number')->unique();
            $table->date('letter_date');
            $table->enum('category', ['kehilangan', 'kerusakan_sparepart', 'transfer_asset', 'penggantian_unit'])->default('kehilangan');
            $table->string('title');
            
            $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->string('asset_tag')->nullable();
            $table->string('asset_name')->nullable();
            $table->string('quantity')->default('1 Unit');
            $table->string('completeness')->nullable()->default('1 Unit Laptop + Charger');

            $table->string('party1_name');
            $table->string('party1_title')->default('IT STAFF');
            $table->string('party1_department')->default('INFORMATION & TECHNOLOGY');

            $table->string('party2_name');
            $table->string('party2_title')->nullable();
            $table->string('party2_department')->nullable();

            $table->string('approver_name')->nullable()->default('SETYADI CANDRAWINATA');
            $table->string('approver_title')->nullable()->default('GM Finance & Operations');

            $table->text('description_points');
            $table->json('attachments')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acaras');
    }
};
