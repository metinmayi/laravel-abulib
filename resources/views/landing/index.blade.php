<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catherine Conelly</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex bg-gray-50 text-gray-800">
    <!-- Sidebar -->
    <div class="w-1/4 bg-white p-6">
        <h1 class="text-3xl font-serif leading-tight mb-10">
            Abdulbaghi<br>Ahmad
        </h1>
        <nav>
            <ul class="space-y-4">
                <li><a href="#about" class="text-lg hover:text-gray-500">About Abdulbaghi</a></li>
                <li><a href="/library" class="text-lg hover:text-gray-500">Library</a></li>
                <li><a href="/login" class="text-lg hover:text-gray-500">Login</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="w-3/4 flex flex-col items-center justify-center bg-gray-100">
        <img src="{{ asset('images/abdul.jpg') }}" alt="Catherine Conelly" class="w-2/3 max-w-md rounded-lg shadow-md">
        <footer class="mt-8">
            <p class="text-lg italic">Abdulbaghi Ahmad, Child and Adolescence Psychiatrist</p>
        </footer>
    </div>
</body>
</html>
