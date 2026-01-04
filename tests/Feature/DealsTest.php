<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Models\Deal;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DealsTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_deals()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);
        $stage1 = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 1]);
        $deal = Deal::factory()->create([
            'tenant_id' => $user->tenant_id,
            'client_id' => $client->id,
            'stage_id' => $stage1->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/deals');
        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $deal->id]);
    }

    public function test_create_deal()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);
        $stage = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 1]);

        $this->actingAs($user);

        $data = [
            'client_id' => $client->id,
            'title' => 'New Deal',
            'stage_id' => $stage->id
        ];

        $response = $this->postJson('/api/deals', $data);
        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Deal']);
    }

    public function test_update_deal()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);
        $stage1 = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 1]);
        $deal = Deal::factory()->create([
            'tenant_id' => $user->tenant_id,
            'client_id' => $client->id,
            'stage_id' => $stage1->id,
        ]);

        $this->actingAs($user);

        $response = $this->putJson("/api/deals/{$deal->id}", ['title' => 'Updated Deal']);
        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Deal']);
    }

    public function test_delete_deal()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);
        $stage1 = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 1]);
        $deal = Deal::factory()->create([
            'tenant_id' => $user->tenant_id,
            'client_id' => $client->id,
            'stage_id' => $stage1->id,
        ]);

        $this->actingAs($user);

        $response = $this->deleteJson("/api/deals/{$deal->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('deals', ['id' => $deal->id]);
    }

    public function test_move_deal_to_next_stage()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);

        $stage1 = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 1]);
        $stage2 = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 2]);

        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);
        $deal = Deal::factory()->create([
            'tenant_id' => $user->tenant_id,
            'client_id' => $client->id,
            'stage_id' => $stage1->id,
            'pipeline_id' => $pipeline->id, // <--- важно
        ]);

        $this->actingAs($user);

        $response = $this->postJson("/api/deals/{$deal->id}/next-stage");
        $response->assertStatus(200)
            ->assertJsonFragment(['stage_id' => $stage2->id]);
    }

    public function test_move_deal_to_specific_stage()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);

        $stage1 = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 1]);
        $stage2 = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 2]);

        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);
        $deal = Deal::factory()->create([
            'tenant_id' => $user->tenant_id,
            'client_id' => $client->id,
            'stage_id' => $stage1->id,
            'pipeline_id' => $pipeline->id, // <--- важно
        ]);

        $this->actingAs($user);

        $response = $this->postJson("/api/deals/{$deal->id}/move-to-stage/{$stage2->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['stage_id' => $stage2->id]);
    }

    public function test_index_filters_by_stage()
    {
        $user = User::factory()->for(Tenant::factory())->create();
        $pipeline = Pipeline::factory()->create(['tenant_id' => $user->tenant_id]);
        $stage1 = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 1]);
        $stage2 = PipelineStage::factory()->create(['pipeline_id' => $pipeline->id, 'order' => 2]);

        $client = Client::factory()->create(['tenant_id' => $user->tenant_id]);
        $deal1 = Deal::factory()->create(['tenant_id' => $user->tenant_id, 'client_id' => $client->id, 'stage_id' => $stage1->id]);
        $deal2 = Deal::factory()->create(['tenant_id' => $user->tenant_id, 'client_id' => $client->id, 'stage_id' => $stage2->id]);

        $this->actingAs($user);

        $response = $this->getJson("/api/deals?stage_id={$stage1->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $deal1->id])
            ->assertJsonMissing(['id' => $deal2->id]);
    }
}
