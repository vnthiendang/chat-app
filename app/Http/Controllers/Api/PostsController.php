<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidationRequest;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Post;
use App\Http\Resources\Product as ProductResource;
use Log;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        if (count($posts) == 0) {
            return response()->json(['message' => 'No posts found'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        // return ProductResource::collection($posts);
        return response()->json([
            'data' => $posts, 
            'status_code' => JsonResponse::HTTP_OK
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ValidationRequest $request)
    {
        // Handle file upload
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // Save to database
        try {
            $posts = Post::create([
                'caption' => $request->caption,
                'image' => $imageName,
                'user_id' => $request->user_id,
            ]);
            Log::info('Post created successfully: ', ['post_id' => $posts->id]);
        } catch (\Exception $e) {
            Log::error('Failed to create post: ', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create post', 'error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        $posts = Post::create($request->all());

        return response()->json($posts, JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(?Post $product)
    {
        // return new ProductResource($product);
        if (!$product) return response()->json(['message' => 'not found'], 404);
        return response()->json([
            'data' => new ProductResource($product), 
            'status_code' => JsonResponse::HTTP_OK
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // return $post->update($request->all());
        try {
            $post->update($request->all());
            return response()->json(['message' => 'Post updated successfully'], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update post', 'error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // $post->delete();
        if (is_null($post)) {
            return response()->json(['message' => 'Post not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        try {
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully'], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete post', 'error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function toggle(Request $request, Post $post)
    {
        $authUser = $request->user(); // token Sanctum
        if (! $authUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $like = Like::where('user_id', $authUser->id)
                    ->where('post_id', $post->id)
                    ->first();

        if ($like) {
            $like->delete();
        } else {
            $like = Like::create([
                'user_id' => $authUser->id,
                'post_id' => $post->id
            ]);
        }

        return response()->json([
            'data' => $like,
            'status_code' => JsonResponse::HTTP_OK
        ]);
    }
}
