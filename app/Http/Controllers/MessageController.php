<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMsgRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Conversation;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(Request $request, $conversationId)
    {
        $me = $request->user();
        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->participantRows()->where('user_id', $me->id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->paginate(20);
        return response()->json($messages);
    }

    public function store(StoreMsgRequest $request)
    {
        $me = $request->user();
        $conversation = Conversation::with('participantRows')->findOrFail($request->input('conversation_id'));

        // participant check
        $isParticipant = $conversation->participantRows->contains('user_id', $me->id);
        if (!$isParticipant) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // rules for direct: must have exactly 2 participants
        if ($conversation->type === 'direct') {
            if ($conversation->participantRows()->count() !== 2) {
                return response()->json(['message' => 'Invalid conversation participants for direct type'], 422);
            }
        }

        // Save message and update conversation + participant unread counts in transaction
        $message = null;
        DB::transaction(function () use ($conversation, $me, $request, &$message) {
            $message = Message::create([
                'sender_id' => $me->id,
                'conversation_id' => $conversation->id,
                'content' => $request->input('content'),
                'status' => 'sent',
            ]);

            // update conversation last_message_id and updated_at
            $conversation->last_message_id = $message->id;
            $conversation->touch();
            $conversation->save();

            // increment unread_count for all participants except sender
            DB::table('conversation_participants')
                ->where('conversation_id', $conversation->id)
                ->where('user_id', '<>', $me->id)
                ->increment('unread_count', 1);
        });

        return response()->json($message, 201);
    }
}