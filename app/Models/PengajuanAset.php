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
        'area',
        'item_type',
        'quantity',
        'priority',
        'status',
        'reason',
        'specification_requested',
        'estimated_cost',
        'items',
        'approver_name',
        'approver_title',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'request_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'attachments' => 'array',
        'items' => 'array',
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

    /**
     * Get list of item details, falling back to legacy single-item columns if items array is empty.
     */
    public function getItemDetailsList(): array
    {
        if (is_array($this->items) && count($this->items) > 0) {
            $formatted = [];
            foreach ($this->items as $item) {
                $qty = (int) ($item['quantity'] ?? 1);
                if ($qty < 1) $qty = 1;
                $unitCost = (float) ($item['estimated_cost'] ?? 0);
                $total = $unitCost * $qty;

                $formatted[] = [
                    'title' => $item['title'] ?? $item['item_name'] ?? 'Item Aset',
                    'item_type' => $item['item_type'] ?? 'Laptop',
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'total_cost' => $total,
                    'specification' => $item['specification'] ?? $item['specification_requested'] ?? '',
                ];
            }
            return $formatted;
        }

        $qty = (int) ($this->quantity ?? 1);
        if ($qty < 1) $qty = 1;
        $unitCost = (float) ($this->estimated_cost ?? 0);

        return [
            [
                'title' => $this->title ?? 'Pengajuan Aset',
                'item_type' => $this->item_type ?? 'Laptop',
                'quantity' => $qty,
                'unit_cost' => $unitCost,
                'total_cost' => $unitCost * $qty,
                'specification' => $this->specification_requested ?? '',
            ]
        ];
    }

    /**
     * Get grand total estimated cost for all items.
     */
    public function getGrandTotalCostAttribute(): float
    {
        $items = $this->getItemDetailsList();
        $sum = 0;
        foreach ($items as $item) {
            $sum += (float) ($item['total_cost'] ?? 0);
        }
        return $sum;
    }
}
