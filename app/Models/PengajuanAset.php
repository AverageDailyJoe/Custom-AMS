<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanAset extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_number',
        'request_date',
        'title',
        'requester_name',
        'requester_department',
        'asset_id',
        'target_asset_holder',
        'area',
        'item_type',
        'quantity',
        'priority',
        'status',
        'reason',
        'specification_requested',
        'estimated_cost',
        'items',
        'has_lbs',
        'lbs_items',
        'lbs_notes',
        'shipping_cost',
        'service_fee',
        'other_fee',
        'additional_fees',
        'uang_muka',
        'adjustment_amount',
        'approver_name',
        'approver_title',
        'attachments',
        'created_by',
        'company',
    ];

    protected $casts = [
        'request_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'other_fee' => 'decimal:2',
        'uang_muka' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
        'has_lbs' => 'boolean',
        'attachments' => 'array',
        'items' => 'array',
        'lbs_items' => 'array',
        'additional_fees' => 'array',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (PengajuanAset $pengajuan) {
            if ($pengajuan->asset_id && empty($pengajuan->target_asset_holder)) {
                $asset = Asset::find($pengajuan->asset_id);
                if ($asset) {
                    $pengajuan->target_asset_holder = $asset->holder_name !== '-' ? $asset->holder_name : ($asset->location?->name ?? 'In Stock');
                }
            }

            if ($pengajuan->isLbsFilled()) {
                $pengajuan->has_lbs = true;
            }
        });
    }

    /**
     * Check if any LBS (Laporan Biaya Settlement) data has been filled.
     */
    public function isLbsFilled(): bool
    {
        if ($this->has_lbs) {
            return true;
        }

        if (is_array($this->lbs_items) && count($this->lbs_items) > 0) {
            foreach ($this->lbs_items as $item) {
                if (!empty($item['title']) || (isset($item['unit_cost']) && (float)$item['unit_cost'] > 0)) {
                    return true;
                }
            }
        }

        if (is_array($this->additional_fees) && count($this->additional_fees) > 0) {
            foreach ($this->additional_fees as $fee) {
                if (!empty($fee['name']) || (isset($fee['amount']) && (float)$fee['amount'] > 0)) {
                    return true;
                }
            }
        }

        if ((float)($this->shipping_cost ?? 0) > 0 || (float)($this->service_fee ?? 0) > 0 || (float)($this->other_fee ?? 0) > 0) {
            return true;
        }

        if (!empty(trim((string) $this->lbs_notes))) {
            return true;
        }

        if ((float)($this->adjustment_amount ?? 0) != 0) {
            return true;
        }

        return false;
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
                    'lbs_title' => $item['lbs_title'] ?? null,
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
     * Get list of LBS realization items (if filled), or fall back to PPB items.
     */
    public function getLbsItemsList(): array
    {
        if ($this->has_lbs && is_array($this->lbs_items) && count($this->lbs_items) > 0) {
            $formatted = [];
            foreach ($this->lbs_items as $item) {
                $qty = (int) ($item['quantity'] ?? 1);
                if ($qty < 1) $qty = 1;
                $unitCost = (float) ($item['unit_cost'] ?? $item['estimated_cost'] ?? 0);
                $total = $unitCost * $qty;
                if (isset($item['ppn_amount']) && (float)$item['ppn_amount'] > 0) {
                    $total += (float)$item['ppn_amount'];
                }

                $formatted[] = [
                    'title' => $item['title'] ?? 'Item Realisasi LBS',
                    'lbs_title' => $item['title'] ?? null,
                    'item_type' => $item['item_type'] ?? 'Laptop',
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'total_cost' => $total,
                    'specification' => $item['specification'] ?? '',
                ];
            }
            return $formatted;
        }

        return $this->getItemDetailsList();
    }

    /**
     * Get combined list of item details + additional fee rows.
     */
    public function getAllRequestItemsList(bool $forLbs = false): array
    {
        $items = ($forLbs || $this->has_lbs) ? $this->getLbsItemsList() : $this->getItemDetailsList();
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
