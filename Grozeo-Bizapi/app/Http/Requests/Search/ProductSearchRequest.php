<?php

namespace App\Http\Requests\Search;

use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => 'required|string|min:2|max:200',
            'page' => 'sometimes|integer|min:1',
            'order_method' => 'sometimes|string|in:delivery,pickup',
            'branch_id' => 'sometimes|integer',
        ];
    }
}
