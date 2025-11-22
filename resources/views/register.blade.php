<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram Register</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-sm w-full">

        <!-- Signup Box -->
        <div class="bg-white border border-gray-300 p-8">
            <h1 class="text-4xl font-bold text-center mb-4 font-sans">Instagram</h1>
            <p class="text-gray-500 text-center text-sm mb-4">
                Sign up to see photos and videos from your friends.
            </p>

            <button class="flex items-center justify-center w-full bg-blue-500 text-white py-2 rounded-sm font-semibold hover:bg-blue-600 transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Facebook_Logo.png" 
                     alt="Facebook" class="w-5 h-5 mr-2"> Log in with Facebook
            </button>

            <div class="flex items-center my-4">
                <div class="flex-grow h-px bg-gray-300"></div>
                <span class="mx-4 text-gray-500 text-sm font-semibold">OR</span>
                <div class="flex-grow h-px bg-gray-300"></div>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Full name" class="w-full border border-gray-300 rounded-sm px-3 py-2" value="{{ old('name') }}">
                    @error('name')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email" class="w-full border border-gray-300 rounded-sm px-3 py-2" value="{{ old('email') }}">
                    @error('email')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <input type="text" name="username" placeholder="Username" class="w-full border border-gray-300 rounded-sm px-3 py-2" value="{{ old('username') }}">
                    @error('username')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" class="w-full border border-gray-300 rounded-sm px-3 py-2">
                    @error('password')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full border border-gray-300 rounded-sm px-3 py-2">
                    @error('password_confirmation')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                </div>
                <button class="w-full bg-blue-500 text-white py-2 rounded-sm font-semibold">Sign Up</button>
            </form>


            <p class="text-xs text-gray-500 text-center mt-4">
                By signing up, you agree to our Terms, Privacy Policy, and Cookies Policy.
            </p>
        </div>

        <!-- Login Link -->
        <div class="bg-white border border-gray-300 p-4 text-center mt-4">
            <p class="text-sm">
                Have an account? 
                <a href="{{ route('login') }}" class="text-blue-500 font-semibold">Log in</a>
            </p>
        </div>

    </div>
</div>

</body>
</html>
