<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            User::create([
                'tenant_id' => $tenant->id,
                'name' => 'Owner ' . $tenant->name,
                'email' => 'owner@' . $tenant->slug . '.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]);

            User::create([
                'tenant_id' => $tenant->id,
                'name' => 'Member ' . $tenant->name,
                'email' => 'member@' . $tenant->slug . '.com',
                'password' => Hash::make('password'),
                'role' => 'member',
            ]);
        }
    }
}
