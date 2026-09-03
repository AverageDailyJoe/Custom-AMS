<?php

namespace Tests\Unit;

use App\Models\Checkout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_active_returns_true_when_not_checked_in(): void
    {
        $checkout = Checkout::factory()->create([
            'checked_in_at' => null,
        ]);

        $this->assertTrue($checkout->isActive());
    }

    public function test_is_active_returns_false_when_checked_in(): void
    {
        $checkout = Checkout::factory()->create([
            'checked_in_at' => now(),
        ]);

        $this->assertFalse($checkout->isActive());
    }

    public function test_get_all_attachments_combines_and_deduplicates(): void
    {
        $checkout = Checkout::factory()->create([
            'checkout_attachment' => 'doc1.pdf',
            'checkout_attachments' => ['doc1.pdf', 'doc2.pdf'],
            'checkin_attachments' => ['doc3.jpg'],
        ]);

        $attachments = $checkout->getAllAttachments();

        $this->assertCount(3, $attachments);
        $this->assertContains('doc1.pdf', $attachments);
        $this->assertContains('doc2.pdf', $attachments);
        $this->assertContains('doc3.jpg', $attachments);
    }
}
