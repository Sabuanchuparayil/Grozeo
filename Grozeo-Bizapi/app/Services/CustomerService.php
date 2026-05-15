<?php

namespace App\Services;

use App\Contracts\CustomerServiceInterface;
use App\Http\Repositories\Customer\CustomerRepository;
use App\Http\Repositories\Customer\AddressRepository;

class CustomerService implements CustomerServiceInterface
{
    private CustomerRepository $customerRepository;
    private AddressRepository $addressRepository;

    public function __construct(CustomerRepository $customerRepository, AddressRepository $addressRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
    }

    public function findById(int $customerId): ?array
    {
        return $this->customerRepository->findById($customerId);
    }

    public function findByEmail(string $email, int $storegroupId): ?array
    {
        return $this->customerRepository->findByEmail($email, $storegroupId);
    }

    public function updateProfile(int $customerId, array $data): bool
    {
        return $this->customerRepository->updateProfile($customerId, $data);
    }

    public function getAddresses(int $customerId, int $storegroupId): array
    {
        return $this->addressRepository->getAddresses($customerId, $storegroupId);
    }

    public function addAddress(int $customerId, array $addressData): array
    {
        return $this->addressRepository->addAddress($customerId, $addressData);
    }
}
