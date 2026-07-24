<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            // Person the asset is checked out TO
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Who performed the checkout / checkin (admin/operator)
            $table->foreignId('checked_out_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('checked_out_at');
            $table->timestamp('checked_in_at')->nullable();
            $table->text('checkout_notes')->nullable();
            $table->text('checkin_notes')->nullable();
            $table->timestamps();

            $table->index(['asset_id', 'checked_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};
