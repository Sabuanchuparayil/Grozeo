<?php

namespace App\Contracts;

interface ProductServiceInterface
{
    public function getById(int $productId, int $storegroupId): ?array;

    public function getByCategory(int $categoryId, int $storegroupId, int $page = 1): array;

    public function search(string $keyword, int $storegroupId, int $page = 1): array;

    public function getOffers(int $storegroupId, int $page = 1): array;
}
