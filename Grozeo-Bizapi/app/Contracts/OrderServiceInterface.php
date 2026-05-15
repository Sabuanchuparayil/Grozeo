<?php

namespace App\Contracts;

interface OrderServiceInterface
{
    public function getOrderById(int $orderId, int $storegroupId): ?array;

    public function getOrdersByCustomer(int $customerId, int $storegroupId, int $page = 1): array;

    public function updateOrderStatus(int $orderId, string $status, int $storegroupId): bool;

    public function cancelOrder(int $orderId, int $customerId, string $reason): bool;
}
