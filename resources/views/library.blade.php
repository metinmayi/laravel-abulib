<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-blue-600 text-white py-4 shadow-md">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold">Library</h1>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        <h2 class="text-xl font-semibold mb-4">Available Literatures</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($litteratureList as $litterature)                
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-900">{{$litterature->title}}</h3>
                    <p class="text-sm text-gray-600 mt-2">{{$litterature->description}}</p>

                    <div class="mt-4">
                        <h4 class="text-sm font-semibold text-gray-800">Languages:</h4>
                        <ul class="list-disc list-inside text-gray-600">
                            @foreach ($litterature->availableLanguages as $language)
                                <li>{{$language}}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-4">
                        <h4 class="text-sm font-semibold text-gray-800">Category:</h4>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{$litterature->category}}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-6">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm">&copy; 2024 Library. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
