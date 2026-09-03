<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'asset_tag' => 'GTK-' . fake()->unique()->numerify('##-##-##'),
            'asset_model_id' => AssetModel::factory(),
            'location_id' => Location::factory(),
            'status' => 'in_stock',
            'purchase_cost' => fake()->numberBetween(5000000, 25000000),
            'purchase_date' => fake()->date(),
            'purchase_year' => (int) date('Y'),
        ];
    }
}
