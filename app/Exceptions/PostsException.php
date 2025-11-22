<?php

namespace App\Exceptions;

use Exception;
use Log;

class PostsException extends Exception
{
    public function report()
    {
        Log::info('Post not found');
    }

    public function render($request)
    {
        return response()->view('errors.post');
    }
}
