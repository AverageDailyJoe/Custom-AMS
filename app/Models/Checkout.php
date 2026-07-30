<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkout extends Model
{
    protected $fillable = [
        'asset_id',
        'user_id',
        'primary_user',
        'secondary_user',
        'checked_out_by',
        'checked_in_by',
        'checked_out_at',
        'checked_in_at',
        'checkout_notes',
        'checkin_notes',
        'checkout_attachment',
        'checkin_attachment',
        'checkout_attachments',
        'checkin_attachments',
        'component_checklist',
    ];

    public function getHolderNameAttribute(): string
    {
        if ($this->primary_user) {
            return $this->secondary_user ? "{$this->primary_user} / {$this->secondary_user}" : $this->primary_user;
        }

        return $this->user?->name ?? '-';
    }

    protected $casts = [
        'checked_out_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checkout_attachments' => 'array',
        'checkin_attachments' => 'array',
        'component_checklist' => 'array',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    // The person the asset was checked out to.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function checkedOutByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    public function checkedInByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function isActive(): bool
    {
        return $this->checked_in_at === null;
    }

    public function getAllAttachments(): array
    {
        $files = [];

        if (is_array($this->checkout_attachments)) {
            $files = array_merge($files, $this->checkout_attachments);
        } elseif (!empty($this->checkout_attachment)) {
            $files[] = $this->checkout_attachment;
        }

        if (is_array($this->checkin_attachments)) {
            $files = array_merge($files, $this->checkin_attachments);
        } elseif (!empty($this->checkin_attachment)) {
            $files[] = $this->checkin_attachment;
        }

        return array_values(array_unique(array_filter($files)));
    }

    public function getAllAttachmentsCount(): int
    {
        return count($this->getAllAttachments());
    }
}
