<?php

namespace Tests\Feature\Api\Health;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_ping_endpoint_returns_ok(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'ok',
            'service' => 'grozeo-bizapi',
        ]);
    }

    public function test_ping_response_structure(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertJsonStructure([
            'status',
            'service',
        ]);
    }
}
