<?php

namespace App\Policies;

use App\Models\Deal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DealPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tenant_id;
    }

    public function view(User $user, Deal $deal): bool
    {
        return $deal->tenant_id === $user->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->tenant_id;
    }

    public function update(User $user, Deal $deal): bool
    {
        return $deal->tenant_id === $user->tenant_id;
    }

    public function delete(User $user, Deal $deal): bool
    {
        return $deal->tenant_id === $user->tenant_id;
    }

    public function restore(User $user, Deal $deal): bool
    {
        return false;
    }

    public function forceDelete(User $user, Deal $deal): bool
    {
        return false;
    }
}
