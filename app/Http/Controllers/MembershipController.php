<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MembershipController extends Controller
{
    public function index()
    {
        return Membership::with(['user', 'troupe'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'troupe_id' => 'required|uuid|exists:troupes,id',
        ]);

        $troupe = \App\Models\Troupe::findOrFail($validated['troupe_id']);
        $authUserId = Auth::id();

        // Private troupe: only creator can add others
        if ($troupe->visibility === 'private') {
            if ($authUserId !== $troupe->created_by) {
                return response()->json(['error' => 'Only the troupe creator can add members to a private troupe'], 403);
            }
        }

        // Public troupe: users can only add themselves
        if ($troupe->visibility === 'public') {
            if ($validated['user_id'] !== $authUserId) {
                return response()->json(['error' => 'You can only join a public troupe as yourself'], 403);
            }
        }

        $membership = \App\Models\Membership::firstOrCreate([
            'user_id' => $validated['user_id'],
            'troupe_id' => $validated['troupe_id'],
        ]);

        return response()->json($membership->load(['user', 'troupe']), 201);
    }

    public function show($id)
    {
        return Membership::with(['user', 'troupe'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|uuid|exists:users,id',
            'troupe_id' => 'sometimes|uuid|exists:troupes,id',
        ]);

        $membership->update($validated);

        return response()->json($membership->load(['user', 'troupe']));
    }

    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->delete();

        return response()->json(['message' => 'Membership removed']);
    }
}
