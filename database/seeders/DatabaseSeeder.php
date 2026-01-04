<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            TenantSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            DealSeeder::class,
            ActivitySeeder::class,
            PipelineSeeder::class,
            PipelineStageSeeder::class,
        ]);
    }
}
