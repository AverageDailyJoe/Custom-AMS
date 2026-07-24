<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcara extends Model
{
    protected $fillable = [
        'letter_number',
        'letter_date',
        'category',
        'title',
        'asset_id',
        'asset_tag',
        'asset_name',
        'quantity',
        'completeness',
        'party1_name',
        'party1_title',
        'party1_department',
        'party2_name',
        'party2_title',
        'party2_department',
        'approver_name',
        'approver_title',
        'description_points',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'letter_date' => 'date',
        'attachments' => 'array',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateLetterNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        $num = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "BA/IT/{$year}/{$month}/{$num}";
    }
}
