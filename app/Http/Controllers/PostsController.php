<?php

namespace App\Http\Controllers;

use App\Exceptions\PostsException;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth; 

class PostsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum')->only(['create', 'store']);
    // }
    public function index()
    {
        $posts = Post::all();
        return view('posts.search', compact('posts'));
    }
    public function search(Request $request)
    {
        // return view('posts.search', compact('posts'));
        if ($request->ajax()) {
            $output = '';
            $query = $request->search;
            $posts = Post::where('caption', 'LIKE', "%{$query}%")->get();
            if (count($posts) > 0) {
                foreach ($posts as $post) {
                    $output .= '
                    <tr>
                        <td>' . $post->id . '</td>
                        <td>' . $post->caption . '</td>
                        <td>' . $post->image . '</td>
                    </tr>
                    ';
                }
                return response($output);
            } else {
                throw new PostsException();
            }
        }
    }
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'caption' => 'required|max:255',
            'image'   => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle file upload
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // Save to database
        Post::create([
            'user_id' => 1,
            'caption' => $request->caption,
            'image'   => 'images/' . $imageName
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'status_code' => 201
        ], 201);
    }

    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return view('posts.show', compact('post'));
    }

}
