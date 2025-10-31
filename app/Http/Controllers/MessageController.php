<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        return Message::with(['sender', 'conversation'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'troupe_id' => 'nullable|uuid|exists:troupes,id',
            'conversation_id' => 'nullable|uuid|exists:conversations,id',
        ]);

        if (empty($validated['troupe_id']) && empty($validated['conversation_id'])) {
            return response()->json(['error' => 'Message must belong to a troupe or a conversation'], 422);
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'content' => $validated['content'],
            'troupe_id' => $validated['troupe_id'] ?? null,
            'conversation_id' => $validated['conversation_id'] ?? null,
        ]);

        return response()->json($message->load(['sender']), 201);
    }

    public function show($id)
    {
        return Message::with(['sender', 'conversation'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $message->update($validated);

        return response()->json($message->load(['sender', 'conversation']));
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->delete();

        return response()->json(['message' => 'Message deleted']);
    }
}
