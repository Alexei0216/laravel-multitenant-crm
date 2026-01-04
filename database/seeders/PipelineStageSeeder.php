<?php

namespace Database\Seeders;

use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\Database\Seeder;

class PipelineStageSeeder extends Seeder
{
    public function run(): void
    {
        $pipelines = Pipeline::all();

        foreach ($pipelines as $pipeline) {
            $stages = [
                ['name' => 'New'],
                ['name' => 'In Progress'],
                ['name' => 'Negotiation'],
                ['name' => 'Approval'],
                ['name' => 'Success'],
                ['name' => 'Rejected'],
            ];

            foreach ($stages as $index => $stage) {
                PipelineStage::create([
                    'pipeline_id' => $pipeline->id,
                    'name'        => $stage['name'],
                    'order'       => $index + 1,
                    'color'       => $this->randomColor(),
                ]);
            }
        }
    }

    private function randomColor(): string
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
