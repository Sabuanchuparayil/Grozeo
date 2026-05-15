<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;

class LoginRequestTest extends TestCase
{
    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new LoginRequest();
        return Validator::make($data, $request->rules());
    }

    public function test_valid_login_data_passes(): void
    {
        $validator = $this->validate([
            'cust_email' => 'test@example.com',
            'password' => 'secret123',
        ]);

        $this->assertTrue($validator->passes());
    }

    public function test_email_is_required(): void
    {
        $validator = $this->validate([
            'password' => 'secret123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('cust_email', $validator->errors()->toArray());
    }

    public function test_password_is_required(): void
    {
        $validator = $this->validate([
            'cust_email' => 'test@example.com',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_email_must_be_valid_format(): void
    {
        $validator = $this->validate([
            'cust_email' => 'not-an-email',
            'password' => 'secret123',
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_password_minimum_length(): void
    {
        $validator = $this->validate([
            'cust_email' => 'test@example.com',
            'password' => '12345',
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_password_maximum_length(): void
    {
        $validator = $this->validate([
            'cust_email' => 'test@example.com',
            'password' => str_repeat('a', 129),
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_email_maximum_length(): void
    {
        $validator = $this->validate([
            'cust_email' => str_repeat('a', 247) . '@test.com',
            'password' => 'secret123',
        ]);

        $this->assertTrue($validator->fails());
    }
}
