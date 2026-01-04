<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Deal;
use App\Models\Tenant;
use App\Models\Client;

class DealSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $clients = Client::where('tenant_id', $tenant->id)->get();

            foreach ($clients as $client) {
                Deal::create([
                    'tenant_id' => $tenant->id,
                    'client_id' => $client->id,
                    'title' => 'Deal with ' . $client->name,
                    'status' => 'new',
                    'amount' => rand(1000, 10000),
                ]);
            }
        }
    }
}
