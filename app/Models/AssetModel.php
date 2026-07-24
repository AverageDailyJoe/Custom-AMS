<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetModel extends Model
{
    protected $fillable = ['category_id', 'name', 'manufacturer', 'model_number'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->manufacturer} {$this->name}");
    }
}
