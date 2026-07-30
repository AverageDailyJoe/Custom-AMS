<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'reporter_name',
        'reporter_department',
        'contact_number',
        'location_id',
        'room',
        'room_notes',
        'asset_id',
        'asset_tag',
        'asset_name',
        'category',
        'subject',
        'description',
        'scheduled_date',
        'scheduled_time_slot',
        'due_date',
        'priority',
        'assigned_to',
        'assigned_to_name',
        'status',
        'reschedule_reason',
        'resolution_notes',
        'resolved_at',
        'pengajuan_aset_id',
        'dispose_aset_id',
        'berita_acara_id',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'due_date' => 'date',
        'resolved_at' => 'datetime',
        'attachments' => 'array',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pengajuanAset(): BelongsTo
    {
        return $this->belongsTo(PengajuanAset::class);
    }

    public function disposeAset(): BelongsTo
    {
        return $this->belongsTo(DisposeAset::class);
    }

    public function beritaAcara(): BelongsTo
    {
        return $this->belongsTo(BeritaAcara::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(AssetMaintenanceLog::class);
    }

    public static function generateTicketNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        $num = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "TCK/IT/{$year}/{$month}/{$num}";
    }
}
