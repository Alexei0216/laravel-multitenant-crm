<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Policies\ClientPolicy;
use App\Policies\DealPolicy;
use App\Policies\PipelinePolicy;
use App\Policies\PipelineStagePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Deal::class, DealPolicy::class);
        Gate::policy(Pipeline::class, PipelinePolicy::class);
        Gate::policy(PipelineStage::class, PipelineStagePolicy::class);
    }
}
