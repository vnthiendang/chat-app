<!-- show post image -->
<div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
    <div class="container">
        <div class="px-4 py-2 border-b">
        
        <img src="{{ asset($post->image) }}" alt="Post Image" class="post-image">
        <div class="middle">
            <!-- <div class="text">Have a good day</div> -->
            <h2 class="text-lg font-semibold text-gray-800 text">{{ $post->user->username }}</h2>
        </div>
    </div>

<!-- show post caption -->
    <div class="px-4 py-2">
        <p class="text-gray-800">{{ $post->caption }}</p>
    </div>

<!-- Like Button -->
<div class="px-4 py-2 border-t flex items-center space-x-3">
    <form method="POST" action="{{ route('posts.like', $post->id) }}">
        @csrf
        <button type="submit" class="text-red-500 font-bold">
            ❤️ Like ({{ $post->likes->count() }})
        </button>
    </form>
</div>

<!-- Comments -->
<div class="px-4 py-2 flex-1 overflow-y-auto">
    @foreach($post->comments as $comment)
        <div class="mb-2">
            <span class="font-semibold">{{ $comment->user->username }}</span>
            {{ $comment->content }}
        </div>
    @endforeach
</div>

<!-- Add Comment -->
<form method="POST" action="{{ route('comments.store', $post->id) }}" class="px-4 py-2 border-t">
    @csrf
    <input type="text" name="content" class="w-full border rounded px-2 py-1" placeholder="Add a comment...">
</form>

<style>
    .container {
        position: relative;
        width: 50%;
    }
    .post-image {
        /* width: 30%; */
        height: auto;
        max-height: 500px;
        object-fit: cover;
        opacity: 1;
        display: block;
        transition: .5s ease;
        backface-visibility: hidden;
    }

.middle {
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%)
}

.container:hover .post-image {
  opacity: 0.3;
}

.container:hover .middle {
  opacity: 1;
}

.text {
  background-color: #4CAF50;
  color: white;
  font-size: 16px;
  padding: 16px 32px;
}
</style>
