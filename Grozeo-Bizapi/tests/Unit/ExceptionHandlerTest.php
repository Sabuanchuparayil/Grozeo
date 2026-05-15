<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use App\Exceptions\MsgException;
use App\Exceptions\ErrException;

class ExceptionHandlerTest extends TestCase
{
    public function test_validation_exception_returns_422(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'status',
            'error' => ['code', 'msg'],
        ]);
        $response->assertJson(['status' => 'error']);
        $response->assertJson(['error' => ['code' => 'VALIDATION_ERROR']]);
    }

    public function test_not_found_route_returns_404(): void
    {
        $response = $this->getJson('/api/nonexistent-route-xyz');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 'error',
            'error' => ['code' => 'NOT_FOUND'],
        ]);
    }

    public function test_error_response_has_consistent_structure(): void
    {
        $response = $this->getJson('/api/nonexistent-route-xyz');

        $response->assertJsonStructure([
            'status',
            'error' => [
                'code',
                'msg',
            ],
        ]);
    }

    public function test_unauthenticated_returns_401(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }
}
