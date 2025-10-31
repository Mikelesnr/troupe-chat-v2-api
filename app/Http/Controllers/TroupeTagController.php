<?php

namespace App\Http\Controllers;

use App\Models\Troupe;
use Illuminate\Http\Request;

class TroupeTagController extends Controller
{
    public function syncTags(Request $request, $troupeId)
    {
        $troupe = Troupe::findOrFail($troupeId);

        $validated = $request->validate([
            'interest_tag_ids' => 'required|array',
            'interest_tag_ids.*' => 'uuid|exists:interest_tags,id',
        ]);

        $troupe->interestTags()->sync($validated['interest_tag_ids']);

        return response()->json($troupe->load('interestTags'));
    }

    public function detachTag($troupeId, $tagId)
    {
        $troupe = Troupe::findOrFail($troupeId);
        $troupe->interestTags()->detach($tagId);

        return response()->json(['message' => 'Tag detached']);
    }
}
