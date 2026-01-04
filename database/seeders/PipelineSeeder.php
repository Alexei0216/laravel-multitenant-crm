<?php

namespace Database\Seeders;

use App\Models\Pipeline;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class PipelineSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 5) as $i) {
            Pipeline::factory()->create([
                'is_common' => true,
                'tenant_id' => null,
                'order' => $i,
                'name' => "Common Pipeline #{$i}",
            ]);
        }

        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            foreach (range(1, 3) as $i) {
                Pipeline::factory()->create([
                    'tenant_id' => $tenant->id,
                    'is_common' => false,
                    'order' => $i,
                    'name' => "Tenant {$tenant->id} Pipeline #{$i}",
                ]);
            }
        }
    }
}
