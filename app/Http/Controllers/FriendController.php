<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\FriendRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function addFriend(Request $request)
    {
        // create FriendRequest (handle duplicate by unique index)
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate(['to_user_id' => 'required|exists:users,id|not_in:' . $user->id]);
        $toUserId = (int) $request->input('to_user_id');

        if ($toUserId == $user->id) {
            return response()->json(['message' => 'Cannot send friend request to yourself'], 400);
        }

        // check already friends
        $alreadyFriends = Friend::where(function ($q) use ($user, $toUserId) {
            $q->where('user_one_id', $user->id)
                ->where('user_two_id', $toUserId);
        })
        ->orWhere(function ($q) use ($user, $toUserId) {
            $q->where('user_one_id', $toUserId)
                ->where('user_two_id', $user->id);
        })
        ->exists();
        
        if ($alreadyFriends) {
            return response()->json(['message' => 'Already friends'], 409);
        }

        // check Existing request (pending)?
        $existingRequest = FriendRequest::where(function ($q) use ($user, $toUserId) {
            $q->where('from_user_id', $user->id)
                ->where('to_user_id', $toUserId);
        })
        ->orWhere(function ($q) use ($user, $toUserId) {
            $q->where('from_user_id', $toUserId)
                ->where('to_user_id', $user->id);
        })
        ->whereIn('status', ['pending', 'accepted'])
        ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'Friend request already exists',
                'status' => $existingRequest->status,
                'existing_request' => $existingRequest,
            ], 409);
        }
        try {
            $frRequest = FriendRequest::create([
                'from_user_id' => $user->id,
                'to_user_id' => $toUserId,
                'status' => 'pending',
                'message' => $request->input('message', ''),
            ]);
            return response()->json($frRequest, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * accept requests
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptFriendRequest(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate(['from_user_id' => 'required|exists:users,id']);
        $fromUserId = (int) $request->input('from_user_id');

        $frRequest = FriendRequest::where('from_user_id', $fromUserId)
            ->where('to_user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$frRequest) {
            return response()->json(['message' => 'Friend request not found'], 404);
        }
        try {
            DB::transaction(function () use ($frRequest, $fromUserId, $user) {
                // Update friend request status
                $frRequest->status = 'accepted';
                $frRequest->save();

                // Create friend record (ensure user_one_id < user_two_id)
                $userOne = min($fromUserId, $user->id);
                $userTwo = max($fromUserId, $user->id);

                Friend::create([
                    'user_one_id' => $userOne,
                    'user_two_id' => $userTwo,
                ]);
            });

            return response()->json(['message' => 'Friend request accepted', 'friend_request' => $frRequest], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * decline requests
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function declineFriendRequest(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate(['from_user_id' => 'required|exists:users,id']);
        $fromUserId = (int) $request->input('from_user_id');

        $frRequest = FriendRequest::where('from_user_id', $fromUserId)
            ->where('to_user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        if (!$frRequest) {
            return response()->json(['message' => 'Friend request not found'], 404);
        }

        try {
            $frRequest->status = 'rejected';
            $frRequest->save();

            return response()->json(['message' => 'Friend request declined', 'friend_request' => $frRequest], 200);    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * get all accepted friends
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllFriends(Request $request)
    {
        $user = $request->user();

        try {
            $friends = $user->friends()->paginate(20);
            return response()->json($friends);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * get friend requests for receivers
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFriendRequests(Request $request)
    {
        $user = $request->user();

        try {
            $receivedRequests = FriendRequest::where('to_user_id', $user->id)
                ->where('status', 'pending')
                ->with('fromUser:id,name,username') // eager load sender info
                ->get();
            return response()->json($receivedRequests);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}