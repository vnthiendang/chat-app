<?php

use App\Http\Controllers\ChatsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PostsController;

// Route::get('/', [LoginController::class, 'showForm'])->name('login');

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Route::post('/posts', [PostsController::class, 'store'])->name('posts.store');
// Route::get('/posts/{post}', [PostsController::class, 'show'])->name('posts.show');
Route::get('/posts', [PostsController::class, 'index']);
Route::get('/search', [PostsController::class, 'search']);

Auth::routes();
// chat
// Route::get('/chat', [ChatsController::class, 'index']);
// Chat routes
    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');
Route::get('/messages', [ChatsController::class, 'fetchMessages']);
Route::post('/messages', [ChatsController::class, 'sendMessage']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
