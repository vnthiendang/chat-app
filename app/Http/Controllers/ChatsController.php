<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatsController extends Controller {

    public function __construct()
    {
    //   $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
      return Message::with('sender')->get();
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
      $user = Auth::user();

      $message = $user->messages()->create([
        'content' => $request->input('content'),
        'conversation_id' => $request->input('conversation_id'),
        'sender_id'=> $request->input('sender_id'),
      ]);
      broadcast(new MessageSent($user, $message))->toOthers();
      MessageSent::dispatch($user, $message);

      return ['status' => 'Message Sent!'];
    }


}