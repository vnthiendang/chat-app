<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- @vite('resources/css/app.css') -->
    <link href="{{ mix('../resources/css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex items-center justify-center">
    <div class="flex max-w-4xl w-full">
        
        <!-- Left: Phone Image -->
        <div class="hidden md:flex w-1/2 justify-center items-center">
            <img alt="Instagram Preview" class="max-h-[600px]">
        </div>

        <!-- Right: Login Box -->
        <div class="flex flex-col w-full md:w-1/2 space-y-6">
            <!-- Login Form -->
            <div class="bg-white border border-gray-300 p-8">
                <h1 class="text-4xl font-bold text-center mb-8 font-sans">Instagram</h1>
                
                <form action="{{ route('login') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="email" name="email" placeholder="Email" class="w-full border border-gray-300 rounded-sm px-3 py-2">
                    <input type="password" name="password" placeholder="Password" class="w-full border border-gray-300 rounded-sm px-3 py-2">
                    <button class="w-full bg-blue-500 text-white py-2 rounded-sm font-semibold">Log In</button>
                </form>


                <div class="flex items-center my-4">
                    <div class="flex-grow h-px bg-gray-300"></div>
                    <span class="mx-4 text-gray-500 text-sm font-semibold">OR</span>
                    <div class="flex-grow h-px bg-gray-300"></div>
                </div>

                <button class="flex items-center justify-center w-full text-blue-900 text-sm font-semibold">
                    <img
                         alt="Facebook" class="w-5 h-5 mr-2"> Log in with Facebook
                </button>

                <a href="#" class="block text-center text-xs text-blue-900 mt-4">Forgot password?</a>
            </div>

            <!-- Signup Box -->
            <div class="bg-white border border-gray-300 p-4 text-center">
                <p class="text-sm">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-blue-500 font-semibold">Sign up</a>
                </p>
            </div>

            <div id="app">
                <HelloWorld msg="Welcome to Your Vue.js App"></HelloWorld>
            </div>
            
        </div>
    </div>
</div>
<script src="{{ mix('../resources/js/app.js') }}" type="module"></script>
</body>
</html>
