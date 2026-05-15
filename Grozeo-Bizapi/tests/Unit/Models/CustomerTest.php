<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = new Customer();
    }

    public function test_uses_correct_table(): void
    {
        $this->assertEquals('retaline_customer', $this->customer->getTable());
    }

    public function test_uses_correct_primary_key(): void
    {
        $this->assertEquals('cust_id', $this->customer->getKeyName());
    }

    public function test_implements_jwt_subject(): void
    {
        $this->assertInstanceOf(\Tymon\JWTAuth\Contracts\JWTSubject::class, $this->customer);
    }

    public function test_has_custom_timestamps(): void
    {
        $this->assertEquals('cust_created_at', Customer::CREATED_AT);
        $this->assertEquals('cust_updated_at', Customer::UPDATED_AT);
    }

    public function test_has_cart_relationship(): void
    {
        $this->assertTrue(method_exists($this->customer, 'cartEntries'));
    }

    public function test_has_orders_relationship(): void
    {
        $this->assertTrue(method_exists($this->customer, 'orders'));
    }

    public function test_has_address_relationship(): void
    {
        $this->assertTrue(method_exists($this->customer, 'address'));
    }

    public function test_has_saved_items_relationship(): void
    {
        $this->assertTrue(method_exists($this->customer, 'savedItems'));
    }

    public function test_has_primary_address_relationship(): void
    {
        $this->assertTrue(method_exists($this->customer, 'primaryAddress'));
    }

    public function test_jwt_custom_claims_returns_empty_array(): void
    {
        $this->assertEquals([], $this->customer->getJWTCustomClaims());
    }
}
