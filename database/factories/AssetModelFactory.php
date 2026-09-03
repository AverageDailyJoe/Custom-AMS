<?php

namespace Database\Factories;

use App\Models\AssetModel;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetModelFactory extends Factory
{
    protected $model = AssetModel::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' ' . fake()->randomNumber(4),
            'manufacturer' => fake()->randomElement(['HP', 'Lenovo', 'Dell', 'Asus', 'Apple']),
            'model_number' => fake()->bothify('MN-####-??'),
            'category_id' => Category::factory(),
        ];
    }
}
