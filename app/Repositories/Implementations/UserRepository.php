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

    public function update(array $user, int $id): array
    {
        $user = User::find($id);

        $user->update($user);

        return $user->toArray();
    }
}
