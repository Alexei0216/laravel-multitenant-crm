<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            Client::create([
                'tenant_id' => $tenant->id,
                'name' => 'Client 1 ' . $tenant->name,
                'email' => 'client1@' . $tenant->slug . '.com',
                'phone' => '+10000000001',
            ]);

            Client::create([
                'tenant_id' => $tenant->id,
                'name' => 'Client 2 ' . $tenant->name,
                'email' => 'client2@' . $tenant->slug . '.com',
                'phone' => '+10000000002',
            ]);
        }
    }
}
