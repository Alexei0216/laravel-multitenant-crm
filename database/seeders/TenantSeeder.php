<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'name' => 'Tenant 1',
            'slug' => 'tenant1',
        ]);

        User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin Tenant 1',
            'email' => 'admin@tenant1.test',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
    }
}
