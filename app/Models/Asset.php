<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Asset extends Model
{
    protected $fillable = [
        'asset_tag',
        'serial',
        'asset_model_id',
        'location_id',
        'room',
        'department',
        'primary_user',
        'secondary_user',
        'processor',
        'ram',
        'storage_hdd',
        'storage_ssd',
        'vga_card',
        'operating_system',
        'monitor_id',
        'monitor_spec',
        'status',
        'condition',
        'purchase_date',
        'purchase_year',
        'purchase_cost',
        'warranty',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'attachments' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Asset $asset) {
            if ($asset->isDirty('status') && $asset->status === 'disposed') {
                $asset->primary_user = null;
                $asset->secondary_user = null;
            }
        });

        static::saved(function (Asset $asset) {
            if ($asset->status === 'disposed') {
                $asset->checkouts()->whereNull('checked_in_at')->update([
                    'checked_in_at' => now(),
                    'checkin_notes' => 'Automatic checkin due to IT Asset Disposal',
                ]);
            }
        });
    }

    public function assetModel(): BelongsTo
    {
        return $this->belongsTo(AssetModel::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function checkouts(): HasMany
    {
        return $this->hasMany(Checkout::class)->latest('checked_out_at');
    }

    // The currently active checkout (checked_in_at is null), if any.
    public function currentCheckout(): HasOne
    {
        return $this->hasOne(Checkout::class)->whereNull('checked_in_at')->latestOfMany('checked_out_at');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class)->latest('scheduled_date');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(AssetMaintenanceLog::class)->latest('performed_at');
    }

    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out' || !empty($this->primary_user);
    }

    public function getHolderNameAttribute(): string
    {
        if ($this->status === 'disposed') {
            return '-';
        }

        if (!empty($this->primary_user)) {
            return !empty($this->secondary_user) ? "{$this->primary_user} ({$this->secondary_user})" : $this->primary_user;
        }

        $activeCheckout = $this->relationLoaded('currentCheckout') ? $this->currentCheckout : $this->currentCheckout()->first();
        if ($activeCheckout) {
            return $activeCheckout->holder_name;
        }

        return '-';
    }

    /**
     * Depreciation Calculation (Straight-Line Method)
     * Standard Global IT Lifecycle: 4 Years (25% per year) for Laptop, PC, Monitor, Printer.
     * 5 Years (20% per year) for Server / Network Infrastructure.
     */
    public function getDepreciationRateAttribute(): float
    {
        $categoryName = strtolower($this->assetModel?->category?->name ?? '');

        if (str_contains($categoryName, 'server') || str_contains($categoryName, 'network') || str_contains($categoryName, 'switch')) {
            return 20.0; // 5 Years life cycle
        }

        return 25.0; // 4 Years life cycle (Standard Global IT Hardware)
    }

    public function getAgeInYearsAttribute(): int
    {
        $purchaseYear = $this->purchase_year;
        if (!$purchaseYear && $this->purchase_date) {
            $purchaseYear = (int) $this->purchase_date->format('Y');
        }

        if (!$purchaseYear) {
            return 0;
        }

        $currentYear = (int) date('Y');
        return max(0, $currentYear - (int)$purchaseYear);
    }

    public function getDepreciationPercentAttribute(): float
    {
        return min(100.0, $this->age_in_years * $this->depreciation_rate);
    }

    public function getCurrentBookValueAttribute(): float
    {
        $cost = (float) $this->purchase_cost;
        if ($cost <= 0) {
            return 0.0;
        }

        $percentRemaining = (100.0 - $this->depreciation_percent) / 100.0;
        return max(0.0, round($cost * $percentRemaining, 2));
    }

    /**
     * Check out this asset to a user / primary user.
     */
    public function checkoutToUser(?string $primaryUser = null, ?string $secondaryUser = null, ?User $user = null, ?string $notes = null, $attachments = null): Checkout
    {
        $adminId = Auth::id() ?: 1;
        $attachmentsArray = is_array($attachments) ? $attachments : ($attachments ? [$attachments] : null);

        $checkout = $this->checkouts()->create([
            'user_id' => $user?->id,
            'primary_user' => $primaryUser,
            'secondary_user' => $secondaryUser,
            'checked_out_by' => $adminId,
            'checked_out_at' => now(),
            'checkout_notes' => $notes,
            'checkout_attachments' => $attachmentsArray,
            'checkout_attachment' => is_array($attachments) ? ($attachments[0] ?? null) : $attachments,
        ]);

        $this->update([
            'status' => 'checked_out',
            'primary_user' => $primaryUser,
            'secondary_user' => $secondaryUser,
        ]);

        return $checkout;
    }

    /**
     * Check in the asset's currently active checkout.
     */
    public function checkin(?string $notes = null, $attachments = null, string $newStatus = 'in_stock', ?array $componentChecklist = null): ?Checkout
    {
        $adminId = Auth::id() ?: 1;
        $checkout = $this->currentCheckout()->first();
        $attachmentsArray = is_array($attachments) ? $attachments : ($attachments ? [$attachments] : null);

        if ($checkout instanceof Model && method_exists($checkout, 'update')) {
            $checkout->update([
                'checked_in_at' => now(),
                'checked_in_by' => $adminId,
                'checkin_notes' => $notes,
                'checkin_attachments' => $attachmentsArray,
                'checkin_attachment' => is_array($attachments) ? ($attachments[0] ?? null) : $attachments,
                'component_checklist' => $componentChecklist,
            ]);
        }

        $this->update([
            'status' => $newStatus,
            'primary_user' => null,
            'secondary_user' => null,
        ]);

        return $checkout;
    }
}
