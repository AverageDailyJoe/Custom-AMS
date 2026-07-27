<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisposeAset extends Model
{
    protected $fillable = [
        'disposal_number',
        'disposal_date',
        'asset_id',
        'asset_tag',
        'asset_name',
        'disposal_reason',
        'disposal_type',
        'status',
        'estimated_salvage_value',
        'created_by_name',
        'spv_name',
        'manager_name',
        'ga_recipient_name',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'estimated_salvage_value' => 'decimal:2',
        'attachments' => 'array',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateDisposalNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        $num = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "DISP/IT/{$year}/{$month}/{$num}";
    }
}
