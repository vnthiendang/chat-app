<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $me = $request->user();
        $userId = $me->id;

        $conversations = Conversation::whereHas('participantRows', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->with([
                'lastMessage',
                'participants' => function ($q) {
                    $q->select('users.id', 'users.name', 'users.username');
                }
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return response()->json($conversations);
    }

    public function show(Request $request, $id)
    {
        $me = $request->user();
        $conversation = Conversation::with(['lastMessage', 'participants'])
            ->findOrFail($id);

        if (!$conversation->participantRows()->where('user_id', $me->id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->paginate(20);

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Create direct conversation between authenticated user and another user.
     * Request expects 'other_user_id'.
     */
    public function store(Request $request)
    {
        $request->validate(['type' => 'required|in:direct,group', 'other_user_id' => 'sometimes|nullable|integer']);

        $me = $request->user();
        if (!$me) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($request->type !== 'direct') {
            return response()->json(['message' => 'Only direct creation implemented'], 422);
        }

        $otherId = (int) $request->input('other_user_id');
        if (!$otherId || $otherId === $me->id) {
            return response()->json(['message' => 'Cannot create conversation with yourself'], 422);
        }

        // find existing direct conversation
        $candidateConversationIds = DB::table('conversation_participants')
            ->select('conversation_id')
            ->whereIn('user_id', [$me->id, $otherId])
            ->groupBy('conversation_id')
            ->havingRaw('COUNT(*) = 2')
            ->pluck('conversation_id');

        $existing = Conversation::whereIn('id', $candidateConversationIds)
            ->where('type', 'direct')
            ->first();

        if ($existing) {
            // do not allow duplicate direct conversations
            return response()->json([
                'message' => 'Direct conversation already exists',
                'conversation' => $existing,
            ], 409);
        }

        // create new conversation and insert two participant rows
        DB::beginTransaction();
        try {// start transaction

            // 1. use Eloquent
            $conv = Conversation::create(['type' => 'direct', 'created_by' => $me->id]);

            // 2. use Query Builder
            DB::table('conversation_participants')->insert([
                [
                    'conversation_id' => $conv->id,
                    'user_id' => $me->id,
                    // 'role' => null,
                    // 'last_seen_message_id' => null,
                    'unread_count' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'conversation_id' => $conv->id,
                    'user_id' => $otherId,
                    // 'role' => null,
                    // 'last_seen_message_id' => null,
                    'unread_count' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'status_code' => 500,
            ]);
        }
        

        return response()->json($conv, 201);
    }
}