<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PipelineStagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_stages()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);
        $stage = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id]);

        $this->actingAs($user);

        $response = $this->getJson("/api/pipelines/{$pipeline->id}/stages");
        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $stage->id]);
    }

    public function test_create_stage()
    {
        $tenant = Tenant::factory()->create();
        $tenantUser = User::factory()->for($tenant)->create(['role' => 'owner']);

        $pipeline = Pipeline::factory()->create([
            'tenant_id' => $tenant->id,
            'is_common' => false
        ]);

        $data = ['name' => 'New Stage', 'color' => 'red'];

        $this->actingAs($tenantUser);

        $response = $this->postJson("/api/pipelines/{$pipeline->id}/stages", $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'New Stage']);
    }

    public function test_update_stage()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);
        $stage = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id]);

        $this->actingAs($user);

        $response = $this->putJson("/api/pipelines/{$pipeline->id}/stages/{$stage->id}", ['name' => 'Updated Stage']);
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Stage']);
    }

    public function test_delete_stage()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);
        $stage = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id]);

        $this->actingAs($user);

        $response = $this->deleteJson("/api/pipelines/{$pipeline->id}/stages/{$stage->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('pipeline_stages', ['id' => $stage->id]);
    }
}
