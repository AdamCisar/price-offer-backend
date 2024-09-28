<?php

namespace App\Services\User;

use App\Repositories\UserRepositoryInterface;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function getUserById(int $id): array
    {
        return $this->userRepository->findById($id);
    }

    public function update(array $user): array
    {
        return $this->userRepository->update($user);
    }

}