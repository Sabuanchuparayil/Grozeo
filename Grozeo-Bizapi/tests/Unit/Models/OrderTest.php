<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_order_uses_correct_table(): void
    {
        $order = new Order();
        $this->assertEquals('retaline_customer_order', $order->getTable());
    }

    public function test_order_has_branch_relationship(): void
    {
        $order = new Order();
        $this->assertTrue(method_exists($order, 'branchDetails'));
    }

    public function test_order_has_items_relationship(): void
    {
        $order = new Order();
        $this->assertTrue(method_exists($order, 'orderItems') || method_exists($order, 'items'));
    }
}
