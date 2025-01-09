<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta name="description"
        content="Abdulbaqi Mayi (Abdulbaghi Ahmad) is a renowned child and adolescent psychiatrist working to improve mental health care in Sweden and Kurdistan.">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex min-h-screen">
    @component('components.header')
    @endcomponent
    <!-- Main Content -->
    <div class="flex-1 flex justify-center items-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login</h2>
            <form action="{{ route('auth.login') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Email Field -->
                <div>
                    @error('Error')
                        <h2 class="text-red-700">{{ $message }}</h2>
                    @enderror
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Enter your email" />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Enter your password" />
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
