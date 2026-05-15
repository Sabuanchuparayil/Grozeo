<?php

namespace App\Services;

use App\Contracts\ProductServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductService implements ProductServiceInterface
{
    public function getById(int $productId, int $storegroupId): ?array
    {
        $product = DB::table('products')
            ->where('id', $productId)
            ->where('storegroup_id', $storegroupId)
            ->first();

        return $product ? (array) $product : null;
    }

    public function getByCategory(int $categoryId, int $storegroupId, int $page = 1): array
    {
        return DB::table('products')
            ->where('category_id', $categoryId)
            ->where('storegroup_id', $storegroupId)
            ->where('is_active', 1)
            ->paginate(20, ['*'], 'page', $page)
            ->toArray();
    }

    public function search(string $keyword, int $storegroupId, int $page = 1): array
    {
        return DB::table('products')
            ->where('storegroup_id', $storegroupId)
            ->where('is_active', 1)
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                      ->orWhere('sku', 'like', "%{$keyword}%");
            })
            ->paginate(20, ['*'], 'page', $page)
            ->toArray();
    }

    public function getOffers(int $storegroupId, int $page = 1): array
    {
        return DB::table('products')
            ->where('storegroup_id', $storegroupId)
            ->where('is_active', 1)
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->paginate(20, ['*'], 'page', $page)
            ->toArray();
    }
}
