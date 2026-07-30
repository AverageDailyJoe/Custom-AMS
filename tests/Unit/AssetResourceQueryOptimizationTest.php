<?php

namespace Tests\Unit;

use App\Filament\Resources\AssetResource;
use Tests\TestCase;

class AssetResourceQueryOptimizationTest extends TestCase
{
    public function test_asset_resource_eager_loads_current_checkout_for_listing(): void
    {
        $builder = AssetResource::getEloquentQuery();

        $this->assertArrayHasKey('currentCheckout', $builder->getEagerLoads());
    }
}
