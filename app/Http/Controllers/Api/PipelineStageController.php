<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\Http\Request;

class PipelineStageController extends Controller
{
    public function index(Request $request, string $pipelineId)
    {
        $pipeline = Pipeline::findOrFail($pipelineId);

        $this->authorize('view', $pipeline);

        return response()->json([
            'success' => true,
            'data' => $pipeline->stages,
            'message' => 'Stages retrieved successfully',
        ]);
    }

    public function store(Request $request, string $pipelineId)
    {
        $pipeline = Pipeline::findOrFail($pipelineId);

        $this->authorize('createStage', $pipeline);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
        ]);

        if (!isset($data['order'])) {
            $maxOrder = $pipeline->stages()->max('order');
            $data['order'] = $maxOrder ? $maxOrder + 1 : 1;
        }

        $data['pipeline_id'] = $pipeline->id;

        $stage = PipelineStage::create($data);

        return response()->json([
            'success' => true,
            'data' => $stage,
            'message' => 'Stage created successfully',
        ], 201);
    }

    public function show(Request $request, string $pipelineId, string $stageId)
    {
        $stage = PipelineStage::findOrFail($stageId);

        $this->authorize('view', $stage->pipeline);

        return response()->json([
            'success' => true,
            'data' => $stage,
            'message' => 'Stage retrieved successfully',
        ]);
    }

    public function update(Request $request, string $pipelineId, string $stageId)
    {
        $stage = PipelineStage::findOrFail($stageId);

        $this->authorize('update', $stage->pipeline);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'color' => 'sometimes|nullable|string|max:50',
            'order' => 'sometimes|integer',
        ]);

        $stage->update($data);

        return response()->json([
            'success' => true,
            'data' => $stage,
            'message' => 'Stage updated successfully',
        ]);
    }

    public function destroy(Request $request, string $pipelineId, string $stageId)
    {
        $stage = PipelineStage::findOrFail($stageId);

        $this->authorize('delete', $stage->pipeline);

        $stage->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }
}
