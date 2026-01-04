<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Client::class);

        $tenant = $request->user()->tenant;

        $clients = Client::where('tenant_id', $tenant->id)->paginate(15);

        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Client::class);

        $tenant = $request->user()->tenant;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email,NULL,id,tenant_id,' . $tenant->id,
            'phone' => 'nullable|string|max:50|unique:clients,phone,NULL,id,tenant_id,' . $tenant->id,
        ]);

        $data['tenant_id'] = $tenant->id;
        $client = Client::create($data);

        return response()->json($client, 201);
    }

    public function show(Request $request, Client $client)
    {
        $this->authorize('view', $client);

        return response()->json($client);
    }

    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);

        $tenant = $request->user()->tenant;

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email,' . $client->id . ',id,tenant_id,' . $tenant->id,
            'phone' => 'nullable|string|max:50|unique:clients,phone,' . $client->id . ',id,tenant_id,' . $tenant->id,
        ]);

        $client->update($data);

        return response()->json($client);
    }

    public function destroy(Request $request, Client $client)
    {
        $this->authorize('delete', $client);

        $client->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
