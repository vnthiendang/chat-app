<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id'];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    /**
     * Return the other user instance for a given user id.
     */
    public function otherUser($userId)
    {
        if ($this->user_one_id == $userId) {
            return $this->userTwo;
        }
        return $this->userOne;
    }
}