<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function findById(int $id): array;

    public function update(array $user, int $id): array;
}
