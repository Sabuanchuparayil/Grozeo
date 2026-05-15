<?php

namespace App\Services;

use App\Contracts\OrderServiceInterface;
use App\Http\Repositories\Order\OrderRepositoryInterface;

class OrderService implements OrderServiceInterface
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getOrderById(int $orderId, int $storegroupId): ?array
    {
        return $this->orderRepository->getOrderById($orderId, $storegroupId);
    }

    public function getOrdersByCustomer(int $customerId, int $storegroupId, int $page = 1): array
    {
        return $this->orderRepository->getOrdersByCustomer($customerId, $storegroupId, $page);
    }

    public function updateOrderStatus(int $orderId, string $status, int $storegroupId): bool
    {
        return $this->orderRepository->updateOrderStatus($orderId, $status, $storegroupId);
    }

    public function cancelOrder(int $orderId, int $customerId, string $reason): bool
    {
        return $this->orderRepository->cancelOrder($orderId, $customerId, $reason);
    }
}
