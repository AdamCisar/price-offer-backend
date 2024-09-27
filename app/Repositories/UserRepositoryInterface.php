<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function findById(int $id): array;
}
