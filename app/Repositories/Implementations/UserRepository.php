<?php

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): array
    {
        return User::find($id)->toArray();
    }

    public function update(array $user): array
    {
        $user = User::updateOrCreate(['id' => $user['id'] ?? null], $user);
        return $user->toArray();
    }
}
