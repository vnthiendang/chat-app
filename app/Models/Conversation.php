<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['type', 'created_by', 'last_message_id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    // existing belongsToMany for convenience (users)
    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_participants', 'conversation_id', 'user_id')
            ->withTimestamps()
            ->withPivot(['role', 'unread_count']);
    }

    // pivot model rows — easier for unread_count / last_seen updates
    public function participantRows()
    {
        return $this->hasMany(ConversationParticipant::class, 'conversation_id');
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }
}