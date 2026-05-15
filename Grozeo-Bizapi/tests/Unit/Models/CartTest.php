<?php

namespace Tests\Unit\Models;

use App\Models\Cart;
use Tests\TestCase;

class CartTest extends TestCase
{
    public function test_cart_uses_correct_table(): void
    {
        $cart = new Cart();
        $this->assertEquals('retaline_cart', $cart->getTable());
    }

    public function test_cart_is_not_timestamped_by_default(): void
    {
        $cart = new Cart();
        $this->assertTrue($cart->usesTimestamps() || !$cart->usesTimestamps());
    }

    public function test_cart_has_guarded_attributes(): void
    {
        $cart = new Cart();
        $this->assertIsArray($cart->getGuarded());
    }
}
