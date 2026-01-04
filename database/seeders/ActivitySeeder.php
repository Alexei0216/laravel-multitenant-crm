<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Deal;
use App\Models\Client;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $users = User::where('tenant_id', $tenant->id)->get();
            $clients = Client::where('tenant_id', $tenant->id)->get();
            $deals = Deal::where('tenant_id', $tenant->id)->get();

            foreach ($users as $user) {
                Activity::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $user->id,
                    'deal_id' => $deals->first()->id ?? null,
                    'client_id' => $clients->first()->id ?? null,
                    'type' => 'call',
                    'note' => 'Initial call with client',
                ]);
            }
        }
    }
}
