<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literature Management</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex bg-gray-50 text-gray-800">
    @component('components.siderbar')
    @endcomponent
    <!-- Forms -->

    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-lg w-3/4">
        <h1 class="text-3xl font-bold text-blue-800">{{ $literatureItem['title'] }}</h1>

        <!-- Description Section -->
        <div class="mt-6">
            <h3 class="text-xl font-semibold text-blue-700">Description:</h3>
            <p class="mt-2 text-gray-700">{{ $literatureItem['description']}}</p>
        </div>

        <!-- Available Languages Section -->
        <div class="mt-6">
            <h3 class="text-xl font-semibold text-blue-700">Available Languages:</h3>
            <p class="mt-2 text-gray-700">{{ $literatureItem['availableLanguages'] }}</p>
        </div>

        <!-- Category Section -->
        <div class="mt-6">
            <h3 class="text-xl font-semibold text-blue-700">Category:</h3>
            <p class="mt-2 text-gray-700">{{ $literatureItem['category'] }}</p>
        </div>

        <!-- PDF Reader Section -->
        <div class="mt-6">
            <h3 class="text-xl font-semibold text-blue-700">Read the Book:</h3>
            <object class="w-full h-screen border rounded-lg" data="/literatureVariant/{{$literatureItem['id']}}" type="application/pdf">
                <p class="text-gray-700">Your browser does not support PDFs.
                    <a href="path/to/your/book.pdf" class="text-blue-600 hover:underline">Download the PDF</a> to read
                    it.
                </p>
            </object>
        </div>
    </div>
</body>

</html>
