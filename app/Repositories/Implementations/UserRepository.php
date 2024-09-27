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
}
