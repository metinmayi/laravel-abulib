<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex bg-gray-50 text-gray-800">
    <!-- Sidebar  -->
    <div class="w-1/4 p-6">
        <h1 class="text-3xl font-serif leading-tight mb-10"><a href="/">
                Abdulbaghi<br>Ahmad
            </a>
        </h1>
        <nav>
            <ul class="space-y-4">
                <li><a href="#about" class="text-lg hover:text-gray-500">About Abdulbaghi</a></li>
                <li><a href="/library" class="text-lg hover:text-gray-500">Library</a></li>
                <li><a href="/login" class="text-lg hover:text-gray-500">Login</a></li>
            </ul>
        </nav>
    </div>
    <!-- Page -->
    <main class="max-w-6xl p-6 mt-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($litteratureList as $litterature)
            <!-- Literature Card -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden transform hover:scale-105 transition duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-2 text-gray-900">{{$litterature->title}}</h2>
                    <p class="text-gray-600 text-sm mb-4">{{$litterature->description}}</p>
                    <div class="text-xs text-gray-500 mb-4 space-y-1">
                        <div><strong>Languages:</strong>{{implode(',', $litterature->availableLanguages)}}</div>
                        <div><strong>Category:</strong> {{$litterature->category}}</div>
                    </div>
                    <button class="w-full bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600 transition">
                        Read
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </main>
</body>

</html>