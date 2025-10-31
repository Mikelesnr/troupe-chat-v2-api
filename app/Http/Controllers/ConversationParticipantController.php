<?php

namespace App\Http\Controllers;

use App\Models\ConversationParticipant;
use Illuminate\Http\Request;

class ConversationParticipantController extends Controller
{
    public function index()
    {
        return ConversationParticipant::with(['conversation', 'user'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|uuid|exists:conversations,id',
            'user_id' => 'required|uuid|exists:users,id',
        ]);

        $participant = ConversationParticipant::create($validated);

        return response()->json($participant->load(['conversation', 'user']), 201);
    }

    public function show($id)
    {
        return ConversationParticipant::with(['conversation', 'user'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $participant = ConversationParticipant::findOrFail($id);

        $validated = $request->validate([
            'conversation_id' => 'sometimes|uuid|exists:conversations,id',
            'user_id' => 'sometimes|uuid|exists:users,id',
        ]);

        $participant->update($validated);

        return response()->json($participant->load(['conversation', 'user']));
    }

    public function destroy($id)
    {
        $participant = ConversationParticipant::findOrFail($id);
        $participant->delete();

        return response()->json(['message' => 'Participant removed']);
    }
}
