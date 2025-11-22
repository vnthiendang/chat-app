<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostsController;

// auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('/me', [AuthController::class, 'userProfile'])->middleware('auth:api');
    Route::post('/change-pass', [AuthController::class, 'changePassWord'])->middleware('auth:api');    
});

// posts
Route::group(["prefix"=> "posts"], function () {
    Route::get('/', [PostsController::class, 'index'])->name('posts.index');
    Route::get('{post}', [PostsController::class, 'show'])->name('posts.show');
    Route::post('/', [PostsController::class, 'store'])->name('posts.store');
    Route::put('{post}', [PostsController::class, 'update'])->name('posts.update');
    Route::patch('{post}', [PostsController::class, 'update'])->name('posts.update');
    Route::delete('{post}', [PostsController::class, 'destroy'])->name('posts.destroy');
    Route::get('/add', [PostsController::class, 'create']);
    Route::post('{post}/like', [PostsController::class, 'toggle'])
        ->name('posts.like');
});

// users
Route::group([
    'middleware' => 'api',
    'prefix' => 'users'

], function ($router) {
    Route::get('/search?username=', [AuthController::class, 'search'])->middleware('auth:api');
    // Route::post('/change-pass', [AuthController::class, 'changePassWord'])->middleware('auth:api');    
});

// conversations
Route::group([
    'middleware' => 'api',
    'prefix' => 'conversations'

], function ($router) {
    Route::get('/', [ConversationController::class, 'index'])->middleware('auth:api');
    Route::get('{conversation}', [ConversationController::class, 'show'])->middleware('auth:api');
    Route::post('/', [ConversationController::class, 'store']);
    Route::get('{conversation}/messages', [MessageController::class, 'index']);
    // Route::patch('{conversation}/seen', [AuthController::class, 'seen'])->middleware('auth:api');    
});

// messages
Route::group([
    'middleware' => 'api',
    'prefix' => 'messages'

], function ($router) {
    // Route::get('/', [ConversationController::class, 'index'])->middleware('auth:api');
    // Route::get('{conversation}', [ConversationController::class, 'show'])->middleware('auth:api');
    Route::post('/', [MessageController::class, 'store']);
});

// friends
Route::group([
    'middleware' => 'api',
    'prefix' => 'friends'

], function ($router) {
    Route::get('/', [FriendController::class, 'getAllFriends'])->middleware('auth:api');
    Route::get('/requests', [FriendController::class, 'getFriendRequests'])->middleware('auth:api');
    Route::post('/requests', [FriendController::class, 'addFriend']);
    Route::post('/requests/{friend_request}/accept', [FriendController::class, 'acceptFriendRequest']);
    Route::post('/requests/{friend_request}/decline', [FriendController::class, 'declineFriendRequest']);
});
