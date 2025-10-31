<?php

namespace App\Http\Controllers;

use App\Models\UserInterest;
use Illuminate\Http\Request;

class UserInterestController extends Controller
{
    public function index()
    {
        return UserInterest::with(['user', 'interestTag'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'interest_tag_id' => 'required|uuid|exists:interest_tags,id',
        ]);

        $link = UserInterest::create($validated);

        return response()->json($link->load(['user', 'interestTag']), 201);
    }

    public function show($id)
    {
        return UserInterest::with(['user', 'interestTag'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $link = UserInterest::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|uuid|exists:users,id',
            'interest_tag_id' => 'sometimes|uuid|exists:interest_tags,id',
        ]);

        $link->update($validated);

        return response()->json($link->load(['user', 'interestTag']));
    }

    public function destroy($id)
    {
        $link = UserInterest::findOrFail($id);
        $link->delete();

        return response()->json(['message' => 'User interest tag removed']);
    }
}
