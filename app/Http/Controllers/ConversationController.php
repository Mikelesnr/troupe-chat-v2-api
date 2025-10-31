<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ConversationResource;

class ConversationController extends Controller
{
    public function index()
    {
        return Conversation::with('participants')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_ids' => 'required|array|min:1',
        ]);

        $conversation = Conversation::create([
            'created_by' => Auth::id(),
        ]);

        $allParticipants = array_unique([...$validated['participant_ids'], Auth::id()]);

        foreach ($allParticipants as $userId) {
            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'user_id' => $userId,
            ]);
        }

        return response()->json($conversation->load('participants'), 201);
    }

    public function show($id)
    {
        $conversation = Conversation::with(['participants', 'messages'])->findOrFail($id);
        return new ConversationResource($conversation);
    }

    public function update(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);

        $validated = $request->validate([
            'participant_ids' => 'array',
        ]);

        if (isset($validated['participant_ids'])) {
            ConversationParticipant::where('conversation_id', $id)->delete();

            $allParticipants = array_unique([...$validated['participant_ids'], Auth::id()]);

            foreach ($allParticipants as $userId) {
                ConversationParticipant::create([
                    'conversation_id' => $id,
                    'user_id' => $userId,
                ]);
            }
        }

        return response()->json($conversation->load('participants'));
    }

    public function destroy($id)
    {
        $conversation = Conversation::findOrFail($id);
        $conversation->delete();

        return response()->json(['message' => 'Conversation deleted']);
    }
}
