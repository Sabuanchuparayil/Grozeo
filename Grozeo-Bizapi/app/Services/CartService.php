<?php

namespace App\Services;

use App\Contracts\CartServiceInterface;
use App\Http\Repositories\Cart\CartRepository;

class CartService implements CartServiceInterface
{
    private CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getCart(int $customerId, int $storegroupId): array
    {
        return $this->cartRepository->getCart($customerId, $storegroupId);
    }

    public function addItem(int $customerId, int $productId, int $quantity, int $storegroupId): array
    {
        return $this->cartRepository->addItem($customerId, $productId, $quantity, $storegroupId);
    }

    public function updateItem(int $cartItemId, int $quantity, int $customerId): array
    {
        return $this->cartRepository->updateItem($cartItemId, $quantity, $customerId);
    }

    public function removeItem(int $cartItemId, int $customerId): bool
    {
        return $this->cartRepository->removeItem($cartItemId, $customerId);
    }

    public function clearCart(int $customerId, int $storegroupId): bool
    {
        return $this->cartRepository->clearCart($customerId, $storegroupId);
    }
}
