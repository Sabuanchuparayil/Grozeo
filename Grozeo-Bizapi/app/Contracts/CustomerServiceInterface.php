<?php

namespace App\Contracts;

interface CustomerServiceInterface
{
    public function findById(int $customerId): ?array;

    public function findByEmail(string $email, int $storegroupId): ?array;

    public function updateProfile(int $customerId, array $data): bool;

    public function getAddresses(int $customerId, int $storegroupId): array;

    public function addAddress(int $customerId, array $addressData): array;
}
