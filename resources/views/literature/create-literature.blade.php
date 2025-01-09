<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literature Management</title>
    <meta name="description"
        content="Abdulbaqi Mayi (Abdulbaghi Ahmad) is a renowned child and adolescent psychiatrist working to improve mental health care in Sweden and Kurdistan.">
    @vite('resources/css/app.css')
    <script>
        // Toggle function for collapsible sections
        function toggleSection(language) {
            const section = document.getElementById(language + '-section');
            section.classList.toggle('hidden');
        }
    </script>
</head>

<body>
    @component('components.header')
    @endcomponent
    <div class="max-w-4xl mx-auto pt-36">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Upload <span class="text-orange-600">New Literature</span></h1>

        <form id="literatureForm" action="/literature" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    id="category" name="category" placeholder="Select a category" required
                    class="block w-full p-2 border rounded mb-4">
                    @foreach (\App\Models\Literature::CATEGORIES as $val)
                        <option value="{{ $val }}">{{ ucfirst($val) }}</option>
                    @endforeach
                </select>
            </div>
            @foreach (\App\Models\Literature::LANGUAGES as $val)
                <div class="overflow-hidden bg-white shadow rounded-lg">
                    <details class="w-full">
                        <summary
                            class="px-6 py-4 bg-orange-100 border-b border-gray-200 font-medium text-gray-900 cursor-pointer hover:bg-orange-200">
                            {{ ucfirst($val) }}</summary>
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="{{ $val . '-title' }}"
                                    class="block text-sm font-medium text-gray-700">Title</label>
                                <input id="{{ $val . '-title' }}" name="literatures[{{ $val }}][title]"
                                    type="text" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="{{ $val . '-description' }}"
                                    class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="{{ $val . '-description' }}" name="literatures[{{ $val }}][description]" rows="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div>
                                <label for="{{ $val . '-file' }}"
                                    class="block text-sm font-medium text-gray-700">File</label>
                                <input id="{{ $val . '-file' }}" name="literatures[{{ $val }}][file]"
                                    accept="application/pdf" type="file" accept=".pdf"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <input type="hidden" name="literatures[{{ $val }}][language]"
                                value="{{ $val }}">
                        </div>
                    </details>
                </div>
            @endforeach


            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Upload Literature
            </button>
        </form>
    </div>
</body>

</html>
