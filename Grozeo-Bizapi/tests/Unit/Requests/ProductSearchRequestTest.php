<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\Search\ProductSearchRequest;
use Illuminate\Support\Facades\Validator;

class ProductSearchRequestTest extends TestCase
{
    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $request = new ProductSearchRequest();
        return Validator::make($data, $request->rules());
    }

    public function test_valid_search_passes(): void
    {
        $validator = $this->validate([
            'keyword' => 'paracetamol',
        ]);

        $this->assertTrue($validator->passes());
    }

    public function test_keyword_is_required(): void
    {
        $validator = $this->validate([]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('keyword', $validator->errors()->toArray());
    }

    public function test_keyword_minimum_length(): void
    {
        $validator = $this->validate([
            'keyword' => 'a',
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_keyword_maximum_length(): void
    {
        $validator = $this->validate([
            'keyword' => str_repeat('a', 201),
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_page_must_be_integer(): void
    {
        $validator = $this->validate([
            'keyword' => 'test',
            'page' => 'abc',
        ]);

        $this->assertTrue($validator->fails());
    }

    public function test_page_must_be_positive(): void
    {
        $validator = $this->validate([
            'keyword' => 'test',
            'page' => 0,
        ]);

        $this->assertTrue($validator->fails());
    }
}
