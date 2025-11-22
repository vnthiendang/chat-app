<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Post;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject; 

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Friend requests
    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'from_user_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'to_user_id');
    }

    // Friendships (one row per pair)
    public function friendshipsAsUserOne()
    {
        return $this->hasMany(Friend::class, 'user_one_id');
    }

    public function friendshipsAsUserTwo()
    {
        return $this->hasMany(Friend::class, 'user_two_id');
    }

    /**
     * Return a query for friend users (other side of the friendship).
     * Note: this returns a Query\Builder — call ->get() to fetch.
     */
    public function friends()
    {
        $idsOne = $this->friendshipsAsUserOne()->pluck('user_two_id');
        $idsTwo = $this->friendshipsAsUserTwo()->pluck('user_one_id');
        $ids = $idsOne->merge($idsTwo)->unique()->values()->all();

        return self::whereIn('id', $ids);
    }

    // Conversations via pivot table conversation_participants
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants', 'user_id', 'conversation_id')
            ->withTimestamps();
    }

    // Messages sent by user
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}
