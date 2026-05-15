<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|min:1',
            'order_method' => 'sometimes|string|in:delivery,pickup',
            'branch_id' => 'sometimes|integer',
        ];
    }
}
