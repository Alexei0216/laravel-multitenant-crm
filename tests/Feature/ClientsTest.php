<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientsTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_clients()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);

        $this->actingAs($user);

        $response = $this->getJson('/api/clients');
        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $client->id]);
    }

    public function test_create_client()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $this->actingAs($user);

        $data = ['name' => 'Client Name', 'email' => 'test@example.com'];

        $response = $this->postJson('/api/clients', $data);
        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Client Name']);
    }

    public function test_update_client()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);
        $this->actingAs($user);

        $response = $this->putJson("/api/clients/{$client->id}", ['name' => 'Updated Name']);
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_delete_client()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);
        $this->actingAs($user);

        $response = $this->deleteJson("/api/clients/{$client->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
