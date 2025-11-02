<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ConversationResource;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    public function index()
    {
        return Conversation::with('participants')->get();
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'participant_ids' => 'required|array|min:1',
                'participant_ids.*' => 'uuid|exists:users,id',
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

            return new ConversationResource($conversation->load(['participants', 'messages']));
        } catch (\Throwable $e) {
            Log::error('Conversation creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function show($id)
    {
        $conversation = Conversation::with(['participants', 'messages'])->findOrFail($id);
        return new ConversationResource($conversation);
    }

    public function mine()
    {
        $userId = Auth::id();

        $conversations = Conversation::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with(['participants', 'messages'])
            ->latest()
            ->get();

        return ConversationResource::collection($conversations);
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
