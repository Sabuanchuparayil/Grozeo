<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\Location\NearestBranchRequest;
use Illuminate\Support\Facades\Validator;

class NearestBranchRequestTest extends TestCase
{
    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new NearestBranchRequest();
        return Validator::make($data, $request->rules());
    }

    public function test_valid_coordinates_pass(): void
    {
        $validator = $this->validate([
            'latitude' => 12.9716,
            'longitude' => 77.5946,
        ]);

        $this->assertTrue($validator->passes());
    }

    public function test_latitude_is_required(): void
    {
        $validator = $this->validate([
            'longitude' => 77.5946,
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_longitude_is_required(): void
    {
        $validator = $this->validate([
            'latitude' => 12.9716,
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_latitude_must_be_within_range(): void
    {
        $validator = $this->validate([
            'latitude' => 91,
            'longitude' => 77.5946,
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_longitude_must_be_within_range(): void
    {
        $validator = $this->validate([
            'latitude' => 12.9716,
            'longitude' => 181,
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_negative_coordinates_pass(): void
    {
        $validator = $this->validate([
            'latitude' => -33.8688,
            'longitude' => -151.2093,
        ]);

        $this->assertTrue($validator->passes());
    }
}
