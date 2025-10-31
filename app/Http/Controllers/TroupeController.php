<?php

namespace App\Http\Controllers;

use App\Models\Troupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TroupeResource;

class TroupeController extends Controller
{
    public function index()
    {
        $troupes = Troupe::with(['creator', 'interestTags', 'members', 'messages'])->get();
        return TroupeResource::collection($troupes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private',
            'avatar_url' => 'nullable|url',
            'interest_tag_ids' => 'array',
            'interest_tag_ids.*' => 'uuid|exists:interest_tags,id',
        ]);

        $troupe = Troupe::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'visibility' => $validated['visibility'],
            'avatar_url' => $validated['avatar_url'] ?? null,
            'created_by' => Auth::id(),
        ]);

        if (!empty($validated['interest_tag_ids'])) {
            $troupe->interestTags()->sync($validated['interest_tag_ids']);
        }

        return new TroupeResource($troupe->load(['creator', 'interestTags', 'members', 'messages']));
    }

    public function public()
    {
        $troupes = Troupe::where('visibility', 'public')
            ->with(['creator', 'interestTags', 'members', 'messages'])
            ->get();

        return TroupeResource::collection($troupes);
    }

    public function show($id)
    {
        $troupe = Troupe::with(['creator', 'interestTags', 'members', 'messages'])->findOrFail($id);
        return new TroupeResource($troupe);
    }

    public function update(Request $request, $id)
    {
        $troupe = Troupe::findOrFail($id);

        if ($troupe->created_by !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'in:public,private',
            'avatar_url' => 'nullable|url',
            'interest_tag_ids' => 'array',
            'interest_tag_ids.*' => 'uuid|exists:interest_tags,id',
        ]);

        $troupe->update([
            'name' => $validated['name'] ?? $troupe->name,
            'description' => $validated['description'] ?? $troupe->description,
            'visibility' => $validated['visibility'] ?? $troupe->visibility,
            'avatar_url' => $validated['avatar_url'] ?? $troupe->avatar_url,
        ]);

        if (isset($validated['interest_tag_ids'])) {
            $troupe->interestTags()->sync($validated['interest_tag_ids']);
        }

        return new TroupeResource($troupe->load(['creator', 'interestTags', 'members', 'messages']));
    }

    public function destroy($id)
    {
        $troupe = Troupe::findOrFail($id);

        if ($troupe->created_by !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $troupe->delete();

        return response()->json(['message' => 'Troupe deleted']);
    }
}
