<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Checkout;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CheckoutFactory extends Factory
{
    protected $model = Checkout::class;

    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'user_id' => User::factory(),
            'primary_user' => fake()->name(),
            'department' => fake()->randomElement(['IT', 'HRD', 'FINANCE', 'SALES', 'MARKETING']),
            'checked_out_by' => 1,
            'checked_out_at' => now(),
        ];
    }
}
