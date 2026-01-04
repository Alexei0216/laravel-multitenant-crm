<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Pipeline;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PipelinesTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_pipelines_for_user()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $this->actingAs($user);

        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);

        $response = $this->getJson('/api/pipelines');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $pipeline->id]);
    }

    public function test_user_cannot_view_pipeline_of_another_tenant()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $otherPipeline = Pipeline::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/api/pipelines/{$otherPipeline->id}");
        $response->assertStatus(403);
    }

    public function test_create_pipeline()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $this->actingAs($user);

        $data = ['name' => 'New Pipeline', 'is_common' => false];

        $response = $this->postJson('/api/pipelines', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'New Pipeline']);
    }

    public function test_update_pipeline()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $this->actingAs($user);

        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);

        $response = $this->putJson("/api/pipelines/{$pipeline->id}", ['name' => 'Updated Name']);
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_delete_pipeline()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $this->actingAs($user);

        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);

        $response = $this->deleteJson("/api/pipelines/{$pipeline->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('pipelines', ['id' => $pipeline->id]);
    }
}
