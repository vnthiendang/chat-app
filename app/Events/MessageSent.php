<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $conversation;
    public $receiver;

    /**
     * create new event instance
     * @param \App\Models\User $user
     * @param \App\Models\Message $message
     * @param \App\Models\Conversation $conversation
     */
    public function __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
        // $this->conversation = $conversation;
        // $this->receiver = $receiver;
    }

    public function broadcastWith(){
        return [
            'sender_id'=>$this->user->id,
            'content'=>$this->message->id,
            'conversation_id'=>11,
            // 'receiver_id'=>$this->receiver->id,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return  \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        error_log($this->user);
        // error_log($this->receiver);
        return new PrivateChannel('chat.' .$this->receiver->id);
        
    }
}