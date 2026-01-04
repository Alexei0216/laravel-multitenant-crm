<?php

namespace Database\Factories;

use App\Models\Pipeline;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PipelineFactory extends Factory
{
    protected $model = Pipeline::class;

    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name'      => $this->faker->words(2, true),
            'is_common' => false,
        ];
    }

    public function common()
    {
        return $this->state([
            'tenant_id' => null,
            'is_common' => true,
        ]);
    }
}
