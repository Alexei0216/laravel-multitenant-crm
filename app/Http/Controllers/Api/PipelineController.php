<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pipeline;
use App\Models\User;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Pipeline::class);

        $tenant = $request->user()->tenant;
        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        $pipelines = Pipeline::where('tenant_id', $tenant->id)
            ->orWhere('is_common', true)
            ->with('stages')
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pipelines,
            'message' => 'Pipelines retrieved successfully',
        ]);
    }

    public function store(Request $request)
    {
        $tenant = $request->user()->tenant;

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        $this->authorize('create', Pipeline::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_common' => 'nullable|boolean',
        ]);

        $data['is_common'] = $data['is_common'] ?? false;

        $data['tenant_id'] = $data['is_common'] ? null : $tenant->id;

        if (!$data['is_common']) {
            $maxOrder = Pipeline::where('tenant_id', $tenant->id)->max('order');
            $data['order'] = $maxOrder ? $maxOrder + 1 : 1;
        } else {
            $data['order'] = 1;
        }

        $pipeline = Pipeline::create($data);

        return response()->json([
            'success' => true,
            'data' => $pipeline,
            'message' => 'Pipeline created successfully',
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $pipeline = Pipeline::findOrFail($id);

        $this->authorize('view', $pipeline);

        return response()->json([
            'success' => true,
            'data' => $pipeline,
            'message' => 'Pipeline retrieved successfully',
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = $request->user();
        $pipeline = Pipeline::findOrFail($id);

        $this->authorize('update', $pipeline);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'is_common' => 'sometimes|boolean',
        ]);

        if (!isset($data['is_common'])) {
            $data['is_common'] = $pipeline->is_common;
        }

        $data['tenant_id'] = $data['is_common'] ? null : ($pipeline->tenant_id ?? $user->tenant->id);

        $pipeline->update($data);

        return response()->json([
            'success' => true,
            'data' => $pipeline,
            'message' => 'Pipeline updated successfully',
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $pipeline = Pipeline::findOrFail($id);

        $this->authorize('delete', $pipeline);

        $pipeline->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
