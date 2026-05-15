<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    public function test_login_requires_email(): void
    {
        $response = $this->postJson('/api/login', [
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'error' => ['code' => 'VALIDATION_ERROR'],
        ]);
    }

    public function test_login_requires_password(): void
    {
        $response = $this->postJson('/api/login', [
            'cust_email' => 'test@example.com',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_requires_valid_email_format(): void
    {
        $response = $this->postJson('/api/login', [
            'cust_email' => 'not-an-email',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_rejects_short_password(): void
    {
        $response = $this->postJson('/api/login', [
            'cust_email' => 'test@example.com',
            'password' => '123',
        ]);

        $response->assertStatus(422);
    }

    #[\PHPUnit\Framework\Attributes\Group('database')]
    public function test_login_rejects_invalid_credentials(): void
    {
        $this->markTestSkipped('Requires MySQL database with customer table');
    }

    public function test_login_response_structure(): void
    {
        $response = $this->postJson('/api/login', [
            'cust_email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertJsonStructure([
            'status' => [],
        ]);
    }

    public function test_login_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/login', [
                'cust_email' => 'test@example.com',
                'password' => 'wrongpassword' . $i,
            ]);
        }

        $response->assertStatus(429);
    }
}
