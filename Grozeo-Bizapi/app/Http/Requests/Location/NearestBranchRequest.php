<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class NearestBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'order_method' => 'sometimes|string|in:delivery,pickup',
        ];
    }
}
