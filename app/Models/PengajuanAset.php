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
        'shipping_cost',
        'service_fee',
        'other_fee',
        'additional_fees',
        'approver_name',
        'approver_title',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'request_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'other_fee' => 'decimal:2',
        'attachments' => 'array',
        'items' => 'array',
        'additional_fees' => 'array',
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
     * Get list of additional fee rows (Dynamic or fixed fallback).
     */
    public function getAdditionalFeesList(): array
    {
        $fees = [];

        // Dynamic additional_fees repeater array
        if (is_array($this->additional_fees) && count($this->additional_fees) > 0) {
            foreach ($this->additional_fees as $fee) {
                $amount = (float) ($fee['amount'] ?? 0);
                if ($amount == 0) continue;

                $fees[] = [
                    'title' => $fee['name'] ?? $fee['description'] ?? 'Biaya Lainnya',
                    'item_type' => 'Biaya Tambahan',
                    'quantity' => 1,
                    'unit_cost' => $amount,
                    'total_cost' => $amount,
                    'specification' => 'Biaya Transaksi / Operasional',
                ];
            }
            if (count($fees) > 0) {
                return $fees;
            }
        }

        // Fallback to legacy fixed fields
        $shipping = (float) ($this->shipping_cost ?? 0);
        $service = (float) ($this->service_fee ?? 0);
        $other = (float) ($this->other_fee ?? 0);

        if ($shipping > 0) {
            $fees[] = [
                'title' => 'Biaya Ongkos Kirim & Asuransi Pengiriman',
                'item_type' => 'Biaya Pengiriman',
                'quantity' => 1,
                'unit_cost' => $shipping,
                'total_cost' => $shipping,
                'specification' => 'Asuransi & Jasa Kurir Pengiriman Paket',
            ];
        }

        if ($service > 0) {
            $fees[] = [
                'title' => 'Biaya Layanan & Aplikasi Platform (Tokopedia / Shopee / Merchant Fee)',
                'item_type' => 'Biaya Layanan',
                'quantity' => 1,
                'unit_cost' => $service,
                'total_cost' => $service,
                'specification' => 'Biaya Transaksi Resmi Platform / Toko Online',
            ];
        }

        if ($other > 0) {
            $fees[] = [
                'title' => 'Biaya Penanganan / Handling & Admin Fee',
                'item_type' => 'Biaya Administrasi',
                'quantity' => 1,
                'unit_cost' => $other,
                'total_cost' => $other,
                'specification' => 'Biaya Penanganan / Administrasi Transaksi',
            ];
        }

        return $fees;
    }

    /**
     * Get combined list of item details + additional fee rows.
     */
    public function getAllRequestItemsList(): array
    {
        $items = $this->getItemDetailsList();
        $fees = $this->getAdditionalFeesList();

        return array_merge($items, $fees);
    }

    /**
     * Get grand total estimated cost for all items + additional fees.
     */
    public function getGrandTotalCostAttribute(): float
    {
        $allItems = $this->getAllRequestItemsList();
        $sum = 0;
        foreach ($allItems as $item) {
            $sum += (float) ($item['total_cost'] ?? 0);
        }
        return $sum;
    }
}
