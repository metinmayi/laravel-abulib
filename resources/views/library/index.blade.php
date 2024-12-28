<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex bg-gray-50 text-gray-800">
    @component('components.siderbar')@endcomponent
    <!-- Page -->
    <main class="max-w-6xl p-6 mt-10">
        <div class="flex flex-wrap gap-3 justify-evenly">
            @foreach ($literatureList as $literature)
            <!-- Literature Card -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden transform hover:scale-105 transition duration-300 w-1/4">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-2 text-gray-900">{{$literature->title}}</h2>
                    <p class="text-gray-600 text-sm mb-4">{{$literature->description}}</p>
                    <div class="text-xs text-gray-500 mb-4 space-y-1">
                        <div><strong>Languages:</strong>{{implode(',', $literature->availableLanguages)}}</div>
                        <div><strong>Category:</strong> {{$literature->category}}</div>
                    </div>
                    <a class="w-full bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600 transition" href="{{ route('read.index', ['variantId' => $literature->variantId ?? -1]) }}">
                        Read
                    </a>
                    @auth
                    <p class="mt-2">Admin Section</p>
                    <a class="bg-orange-500 text-white py-1 px-2 rounded hover:bg-orange-600 transition mt-2 justify-self-center" href="/admin/editvariant/{{$literature->variantId}}">
                        Edit
                    </a>
                    <a class="bg-green-500 text-white py-1 px-2 rounded hover:bg-green-600 transition mt-2 justify-self-center" href="asd">
                        Add language variant
                    </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </main>
</body>

</html>