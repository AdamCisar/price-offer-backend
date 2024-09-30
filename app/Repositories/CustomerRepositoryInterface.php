<?php

namespace App\Repositories;

interface CustomerRepositoryInterface
{
    public function save(array $customer): array;

    public function findById(int $id): array;
}