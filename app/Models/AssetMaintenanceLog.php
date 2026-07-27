<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMaintenanceLog extends Model
{
    protected $fillable = [
        'asset_id',
        'ticket_id',
        'pengajuan_aset_id',
        'dispose_aset_id',
        'berita_acara_id',
        'maintenance_type',
        'title',
        'description',
        'cost',
        'performed_by',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'date',
        'cost' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
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
}
