<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->username }} - Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <div class="flex justify-between items-center px-4 py-3 border-b bg-white sticky top-0 z-50">
        <div class="text-2xl font-bold">📷 Instagram</div>
        <div>
            <span class="mr-4">{{ $user->username }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Profile Header -->
    <div class="max-w-4xl mx-auto mt-8 px-4">
        <div class="flex flex-col md:flex-row items-center md:items-start">
            <!-- Avatar -->
            <img src="{{ asset('svg/profile.svg') }}" class="w-36 h-36 rounded-full border" alt="Profile Picture">

            <!-- Info -->
            <div class="md:ml-8 mt-4 md:mt-0 flex-1">
                <div class="flex flex-col md:flex-row items-center md:items-center space-y-2 md:space-y-0 md:space-x-4">
                    <h2 class="text-2xl font-light">{{ $user->username }}</h2>
                    <a href="/p" class="bg-blue-500 text-white px-4 py-1 rounded font-semibold">Add Post</a>
                </div>

                <div class="flex space-x-6 mt-4 text-sm text-gray-700">
                    <span><strong>{{ $user->posts->count() }}</strong> posts</span>
                    <span><strong>{{ $user->followers->count() }}</strong> followers</span>
                    <span><strong>{{ $user->following->count() }}</strong> following</span>

                    <form method="POST" action="{{ route('follow.toggle', $user->id) }}">
                        @csrf
                        <button type="submit" class="text-red-500 font-bold">Follow</button>
                    </form>
                </div>

                @if($user->profile)
                    <div class="mt-4 text-sm">
                        <strong>{{ $user->profile->title }}</strong><br>
                        {{ $user->profile->description }}<br>
                        @if($user->profile->url)
                            <a href="{{ $user->profile->url }}" class="text-blue-500">{{ $user->profile->url }}</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="max-w-4xl mx-auto mt-8 px-4">
        @if($user->posts->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($user->posts as $post)
                    <a href="{{ route('posts.show', $post->id) }}" class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                        <img src="{{ asset($post->image) }}" class="w-full h-80 object-cover" alt="Post Image">
                        <div class="p-3 text-sm">
                            <p>{{ $post->caption }}</p>
                            <span class="text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">No posts yet.</p>
        @endif
    </div>

</body>
</html>
