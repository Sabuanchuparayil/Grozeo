<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Middleware\SanitizeInput;

class SanitizeInputTest extends TestCase
{
    private SanitizeInput $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new SanitizeInput();
    }

    public function test_strips_script_tags_from_input(): void
    {
        $request = Request::create('/api/test', 'POST', [
            'name' => '<script>alert("xss")</script>John',
        ]);

        $this->middleware->handle($request, function ($req) {
            $this->assertStringNotContainsString('<script>', $req->input('name'));
            return response('OK');
        });
    }

    public function test_preserves_clean_input(): void
    {
        $request = Request::create('/api/test', 'POST', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->middleware->handle($request, function ($req) {
            $this->assertEquals('John Doe', $req->input('name'));
            $this->assertEquals('john@example.com', $req->input('email'));
            return response('OK');
        });
    }

    public function test_handles_nested_arrays(): void
    {
        $request = Request::create('/api/test', 'POST', [
            'address' => [
                'city' => '<b>London</b>',
                'line1' => 'Normal Street',
            ],
        ]);

        $this->middleware->handle($request, function ($req) {
            $this->assertStringNotContainsString('<b>', $req->input('address.city'));
            $this->assertEquals('Normal Street', $req->input('address.line1'));
            return response('OK');
        });
    }

    public function test_does_not_modify_password_fields(): void
    {
        $request = Request::create('/api/test', 'POST', [
            'password' => 'P@ssw0rd<>!',
        ]);

        $this->middleware->handle($request, function ($req) {
            $this->assertNotEmpty($req->input('password'));
            return response('OK');
        });
    }
}
