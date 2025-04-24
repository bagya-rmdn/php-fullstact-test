<?php

namespace App\Http\Controllers;

use App\Models\MyClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MyClientController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'slug' => 'required|string|max:100|unique:my_client',
            'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $client = new MyClient();
        $client->name = $request->name;
        $client->slug = $request->slug;
        $client->is_project = $request->is_project ?? '0';
        $client->self_capture = $request->self_capture ?? '1';
        $client->client_prefix = $request->client_prefix;

        // Upload Image to S3
        if ($request->hasFile('client_logo')) {
            $path = $request->file('client_logo')->store('client_logos', 's3');
            $client->client_logo = $path;
        }

        $client->save();
        $client->saveToRedis();

        return response()->json($client, 201);
    }

    public function update(Request $request, $slug)
    {
        $client = MyClient::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'string|max:250',
            'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $client->name = $request->name ?? $client->name;

        if ($request->hasFile('client_logo')) {
            Storage::disk('s3')->delete($client->client_logo);
            $path = $request->file('client_logo')->store('client_logos', 's3');
            $client->client_logo = $path;
        }

        $client->save();
        $client->deleteFromRedis();
        $client->saveToRedis();

        return response()->json($client);
    }

    public function destroy($slug)
    {
        $client = MyClient::where('slug', $slug)->firstOrFail();
        $client->deleteFromRedis();
        $client->deleted_at = now();
        $client->save();

        return response()->json(['message' => 'Client deleted success']);
    }
}
