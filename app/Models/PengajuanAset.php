<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanAset extends Model
{
    protected $fillable = [
        'request_number',
        'request_date',
        'title',
        'requester_name',
        'requester_department',
        'item_type',
        'quantity',
        'priority',
        'status',
        'reason',
        'specification_requested',
        'estimated_cost',
        'approver_name',
        'approver_title',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'request_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'attachments' => 'array',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateRequestNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        $num = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "REQ/IT/{$year}/{$month}/{$num}";
    }
}
