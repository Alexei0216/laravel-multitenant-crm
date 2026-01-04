<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\PipelineStage;
use App\Models\Client;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Deal::class);

        $tenant = $request->user()->tenant;

        $query = Deal::where('tenant_id', $tenant->id)
            ->with(['client', 'stage.pipeline'])
            ->orderBy('created_at', 'desc');

        if ($request->has('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }

        if ($request->has('pipeline_id')) {
            $query->whereHas('stage', function ($q) use ($request) {
                $q->where('pipeline_id', $request->pipeline_id);
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $deals = $query->get();

        return response()->json([
            'success' => true,
            'data' => $deals,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Deal::class);

        $tenant = $request->user()->tenant;

        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'status' => 'nullable|string|max:50',
            'amount' => 'nullable|numeric',
            'stage_id' => 'nullable|exists:pipeline_stages,id',
        ]);

        $client = Client::find($data['client_id']);
        if (!$client || $client->tenant_id !== $tenant->id) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        if (!empty($data['stage_id'])) {
            $stage = PipelineStage::find($data['stage_id']);
            if (!$stage) {
                return response()->json(['message' => 'Stage not found'], 404);
            }

            $data['pipeline_id'] = $stage->pipeline_id;

            $pipeline = $stage->pipeline;
            if (!$pipeline->is_common && $pipeline->tenant_id !== $tenant->id) {
                return response()->json(['message' => 'Stage not allowed'], 403);
            }
        }

        $data['tenant_id'] = $tenant->id;

        $deal = Deal::create($data);

        return response()->json([
            'success' => true,
            'data' => $deal->load(['client', 'stage.pipeline']),
            'message' => 'Deal created successfully',
        ], 201);
    }

    public function show(Request $request, Deal $deal)
    {
        $this->authorize('view', $deal);

        return response()->json([
            'success' => true,
            'data' => $deal->load(['client', 'stage.pipeline']),
        ]);
    }

    public function update(Request $request, Deal $deal)
    {
        $this->authorize('update', $deal);

        $tenant = $request->user()->tenant;

        $data = $request->validate([
            'client_id' => 'sometimes|required|exists:clients,id',
            'title' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|nullable|string|max:50',
            'amount' => 'sometimes|numeric',
            'stage_id' => 'sometimes|nullable|exists:pipeline_stages,id',
        ]);

        if (array_key_exists('client_id', $data)) {
            $client = Client::find($data['client_id']);
            if (!$client || $client->tenant_id !== $tenant->id) {
                return response()->json(['message' => 'Client not found'], 404);
            }
        }

        if (array_key_exists('stage_id', $data) && !is_null($data['stage_id'])) {
            $stage = PipelineStage::find($data['stage_id']);
            if (!$stage) {
                return response()->json(['message' => 'Stage not found'], 404);
            }

            $pipeline = $stage->pipeline;
            if (!$pipeline->is_common && $pipeline->tenant_id !== $tenant->id) {
                return response()->json(['message' => 'Stage not allowed'], 403);
            }

            $data['pipeline_id'] = $stage->pipeline_id;
        }

        $deal->update($data);

        return response()->json([
            'success' => true,
            'data' => $deal->fresh()->load(['client', 'stage.pipeline']),
            'message' => 'Deal updated successfully',
        ]);
    }

    public function destroy(Request $request, Deal $deal)
    {
        $this->authorize('delete', $deal);

        $deal->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function nextStage(Deal $deal)
    {
        $this->authorize('update', $deal);

        if (!$deal->stage || !$deal->pipeline_id) {
            return response()->json([
                'success' => false,
                'message' => 'Deal has no stage or pipeline assigned'
            ], 400);
        }

        $currentStage = $deal->stage;

        $nextStage = PipelineStage::where('pipeline_id', $deal->pipeline_id)
            ->where('order', '>', $currentStage->order)
            ->orderBy('order')
            ->first();

        if (!$nextStage) {
            return response()->json([
                'success' => false,
                'message' => 'No next stage found'
            ], 404);
        }

        $deal->update(['stage_id' => $nextStage->id]);

        return response()->json([
            'success' => true,
            'message' => 'Deal moved to next stage',
            'data' => $deal->fresh()->load('stage')
        ]);
    }

    public function moveToStage(Deal $deal, PipelineStage $stage)
    {
        $this->authorize('update', $deal);

        if ($stage->pipeline_id !== $deal->pipeline_id) {
            return response()->json(['message' => 'Invalid stage for this pipeline'], 400);
        }

        $deal->update(['stage_id' => $stage->id]);

        return response()->json([
            'success' => true,
            'message' => 'Deal moved successfully',
            'data' => $deal->fresh(),
        ]);
    }
}
