<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'type'];

    public function assetModels(): HasMany
    {
        return $this->hasMany(AssetModel::class);
    }
}
