<?php

namespace Database\Factories;

use App\Models\Deal;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\PipelineStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
{
    protected $model = Deal::class;

    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'client_id' => Client::factory(),
            'stage_id'  => null,
            'title'     => $this->faker->sentence(3),
            'status'    => 'new',
            'amount'    => $this->faker->randomFloat(2, 0, 10000),
        ];
    }

    public function withStage(PipelineStage $stage)
    {
        return $this->state(fn() => [
            'stage_id' => $stage->id,
            'tenant_id' => $stage->pipeline->tenant_id,
        ]);
    }
}
