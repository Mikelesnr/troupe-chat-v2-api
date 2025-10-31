<?php

namespace App\Http\Controllers;

use App\Models\InterestTag;
use Illuminate\Http\Request;

class InterestTagController extends Controller
{
    public function index()
    {
        return InterestTag::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:interest_tags,name',
        ]);

        $tag = InterestTag::create($validated);

        return response()->json($tag, 201);
    }

    public function show($id)
    {
        return InterestTag::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $tag = InterestTag::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:interest_tags,name,' . $id,
        ]);

        $tag->update($validated);

        return response()->json($tag);
    }

    public function destroy($id)
    {
        $tag = InterestTag::findOrFail($id);
        $tag->delete();

        return response()->json(['message' => 'Interest tag deleted']);
    }
}
