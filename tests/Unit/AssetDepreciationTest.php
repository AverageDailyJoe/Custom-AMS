<?php

namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetDepreciationTest extends TestCase
{
    use RefreshDatabase;

    public function test_standard_laptop_depreciation_rate_is_25_percent(): void
    {
        $category = Category::factory()->create(['name' => 'LAPTOP']);
        $asset = Asset::factory()->create([
            'category_id' => $category->id,
            'purchase_cost' => 10000000,
            'purchase_year' => (int) date('Y') - 2, // 2 years old
        ]);

        $this->assertEquals(25.0, $asset->depreciation_rate);
        $this->assertEquals(50.0, $asset->depreciation_percent);
        $this->assertEquals(5000000.0, $asset->current_book_value);
    }

    public function test_server_depreciation_rate_is_20_percent(): void
    {
        $category = Category::factory()->create(['name' => 'SERVER']);
        $asset = Asset::factory()->create([
            'category_id' => $category->id,
            'purchase_cost' => 20000000,
            'purchase_year' => (int) date('Y') - 5, // 5 years old (100% depreciated)
        ]);

        $this->assertEquals(20.0, $asset->depreciation_rate);
        $this->assertEquals(100.0, $asset->depreciation_percent);
        $this->assertEquals(0.0, $asset->current_book_value);
    }

    public function test_depreciation_percent_never_exceeds_100_percent(): void
    {
        $category = Category::factory()->create(['name' => 'LAPTOP']);
        $asset = Asset::factory()->create([
            'category_id' => $category->id,
            'purchase_cost' => 12000000,
            'purchase_year' => (int) date('Y') - 10, // 10 years old (way past useful life)
        ]);

        $this->assertEquals(100.0, $asset->depreciation_percent);
        $this->assertEquals(0.0, $asset->current_book_value);
    }
}
