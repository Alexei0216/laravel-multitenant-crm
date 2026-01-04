<?php

namespace Database\Factories;

use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PipelineStageFactory extends Factory
{
    protected $model = PipelineStage::class;

    public function definition()
    {
        return [
            'pipeline_id' => Pipeline::factory(),
            'name'        => $this->faker->word(),
            'color'       => $this->faker->hexColor(),
            'order'       => $this->faker->numberBetween(1, 10),
        ];
    }
}
