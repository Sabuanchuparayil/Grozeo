<?php

namespace App\Contracts;

interface CartServiceInterface
{
    public function getCart(int $customerId, int $storegroupId): array;

    public function addItem(int $customerId, int $productId, int $quantity, int $storegroupId): array;

    public function updateItem(int $cartItemId, int $quantity, int $customerId): array;

    public function removeItem(int $cartItemId, int $customerId): bool;

    public function clearCart(int $customerId, int $storegroupId): bool;
}
