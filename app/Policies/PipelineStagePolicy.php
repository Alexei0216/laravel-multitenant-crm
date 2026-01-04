<?php

namespace App\Policies;

use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PipelineStagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->tenant_id;
    }

    public function view(User $user, PipelineStage $pipelineStage): bool
    {
        $pipeline = $pipelineStage->pipeline;

        return $pipeline->is_common || $pipeline->tenant_id === $user->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->tenant_id;
    }

    public function update(User $user, PipelineStage $pipelineStage): bool
    {
        $pipeline = $pipelineStage->pipeline;

        return !$pipeline->is_common && $pipeline->tenant_id === $user->tenant_id;
    }

    public function delete(User $user, PipelineStage $pipelineStage): bool
    {
        $pipeline = $pipelineStage->pipeline;

        if ($pipeline->is_common) {
            return $user->role === 'owner';
        }

        return $pipeline->tenant_id === $user->tenant_id;
    }

    public function restore(User $user, PipelineStage $pipelineStage): bool
    {
        return false;
    }

    public function forceDelete(User $user, PipelineStage $pipelineStage): bool
    {
        return false;
    }
}
