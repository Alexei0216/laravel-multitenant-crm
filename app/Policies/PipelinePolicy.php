<?php

namespace App\Policies;

use App\Models\Pipeline;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PipelinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tenant_id;
    }

    public function view(User $user, Pipeline $pipeline): bool
    {
        return $pipeline->is_commin || $pipeline->tenant_id === $user->tenant_id;
    }

    public function createStage(User $user, Pipeline $pipeline): bool
    {
        return $pipeline->is_common ? $user->role === 'owner' : $pipeline->tenant_id === $user->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->tenant_id;
    }

    public function update(User $user, Pipeline $pipeline): bool
    {
        return !$pipeline->is_common && $pipeline->tenant_id === $user->tenant_id;
    }

    public function delete(User $user, Pipeline $pipeline): bool
    {
        if ($pipeline->is_common) {
            return $user->role === 'owner';
        }

        return $pipeline->tenant_id === $user->tenant_id;
    }

    public function restore(User $user, Pipeline $pipeline): bool
    {
        return false;
    }

    public function forceDelete(User $user, Pipeline $pipeline): bool
    {
        return false;
    }
}
