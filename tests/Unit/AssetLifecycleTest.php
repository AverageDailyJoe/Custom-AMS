<?php

namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Checkout;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_to_user_creates_record_and_updates_asset_status(): void
    {
        $admin = \App\Models\User::factory()->create();
        $this->actingAs($admin);

        $asset = Asset::factory()->create([
            'status' => 'in_stock',
        ]);
        $location = Location::factory()->create();

        $checkout = $asset->checkoutToUser(
            primaryUser: 'Budi Santoso',
            secondaryUser: 'Andi',
            department: 'IT',
            position: 'Staff IT',
            locationId: $location->id,
            room: 'Ruangan IT',
            notes: 'Penyerahan Laptop Baru'
        );

        $this->assertInstanceOf(Checkout::class, $checkout);
        $this->assertEquals('Budi Santoso', $checkout->primary_user);
        $this->assertEquals('IT', $checkout->department);
        $this->assertNull($checkout->checked_in_at);

        // Verify Asset fields are updated to reflect active checkout
        $asset->refresh();
        $this->assertEquals('checked_out', $asset->status);
        $this->assertEquals('Budi Santoso', $asset->primary_user);
        $this->assertEquals('IT', $asset->department);
        $this->assertEquals('Staff IT', $asset->position);
        $this->assertEquals($location->id, $asset->location_id);
    }

    public function test_checkin_resets_asset_fields_and_closes_checkout(): void
    {
        $admin = \App\Models\User::factory()->create();
        $this->actingAs($admin);

        $asset = Asset::factory()->create(['status' => 'in_stock']);
        $location = Location::factory()->create();

        $checkout = $asset->checkoutToUser(
            primaryUser: 'Budi Santoso',
            department: 'IT',
            locationId: $location->id,
            room: 'Ruangan IT'
        );

        $result = $asset->checkin(
            notes: 'Dikembalikan dalam keadaan baik',
            newStatus: 'in_stock',
            componentChecklist: ['layar_status' => 'baik']
        );

        $this->assertNotNull($result);

        // Verify Checkout is closed
        $checkout->refresh();
        $this->assertNotNull($checkout->checked_in_at);
        $this->assertEquals('Dikembalikan dalam keadaan baik', $checkout->checkin_notes);

        // Verify Asset status is reset to in_stock and assignment fields are cleared
        $asset->refresh();
        $this->assertEquals('in_stock', $asset->status);
        $this->assertNull($asset->primary_user);
        $this->assertNull($asset->department);
        $this->assertNull($asset->position);
        $this->assertNull($asset->location_id);
        $this->assertNull($asset->room);
    }
}
