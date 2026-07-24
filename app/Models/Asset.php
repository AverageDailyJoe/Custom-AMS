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

    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out' || !empty($this->primary_user);
    }

    public function getHolderNameAttribute(): string
    {
        if (!empty($this->primary_user)) {
            return !empty($this->secondary_user) ? "{$this->primary_user} ({$this->secondary_user})" : $this->primary_user;
        }

        $activeCheckout = $this->currentCheckout;
        if ($activeCheckout) {
            return $activeCheckout->holder_name;
        }

        return '-';
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

        if ($checkout) {
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
