<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tenant_id;
    }

    public function view(User $user, Client $client): bool
    {
        return $client->tenant_id === $user->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->tenant_id;
    }

    public function update(User $user, Client $client): bool
    {
        return $client->tenant_id === $user->tenant_id;
    }

    public function delete(User $user, Client $client): bool
    {
        return $client->tenant_id === $user->tenant_id;
    }

    public function restore(User $user, Client $client): bool
    {
        return false;
    }

    public function forceDelete(User $user, Client $client): bool
    {
        return false;
    }
}
