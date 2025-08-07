<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GAS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-r from-[#FFE4F3] to-[#FFEEF2] min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <x-application-logo class="w-20 h-20 mx-auto" />
            <h2 class="mt-4 text-2xl font-bold text-gray-900">Welcome Back!</h2>
            <p class="text-gray-600">Please sign in to your account</p>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-[#595758]">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 block w-full px-3 py-2 border border-[#FFC8FB] rounded-md shadow-sm focus:outline-none focus:ring-[#FF92C2] focus:border-[#FF92C2]">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#595758]">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full px-3 py-2 border border-[#FFC8FB] rounded-md shadow-sm focus:outline-none focus:ring-[#FF92C2] focus:border-[#FF92C2]">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-[#FF92C2] focus:ring-[#FF92C2] border-[#FFC8FB] rounded">
                        <label for="remember" class="ml-2 block text-sm text-[#595758]">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-[#FF92C2] hover:text-[#FFC8FB]">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-[#FF92C2] hover:bg-[#FFC8FB] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF92C2]">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</body>
</html>
