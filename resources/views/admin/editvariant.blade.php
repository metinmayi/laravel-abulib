<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Variant</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex bg-gray-50 text-gray-800">
    @component('components.siderbar')
    @endcomponent

    <div class="w-3/4 p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-6">Edit Literature</h1>

        <!-- Current Information -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Current Information</h2>
            <p><strong>Title:</strong> <span id="current-title">{{ $variant->title }}</span></p>
            <p><strong>Description:</strong> <span id="current-description">{{ $variant->description }}</span></p>
            <p><strong>Language:</strong> <span id="current-language">{{ $variant->language }}</span></p>
        </div>

        <form action={{ route('variant.edit', ['variant' => $variant->id]) }} method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            <!-- Title -->
            @if ($errors->any())
                {{ implode('', $errors->all('<div>:message</div>')) }}
            @endif
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <div class="flex items-center gap-2">
                    <input type="text" id="title" name="title"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:opacity-50"
                        disabled />
                    <button type="button"
                        onclick="document.getElementById('title').disabled = false; this.disabled = true;"
                        class="px-3 py-1 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Edit
                    </button>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <div class="flex items-center gap-2">
                    <textarea id="description" name="description" rows="4"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:opacity-50"
                        disabled required></textarea>
                    <button type="button"
                        onclick="document.getElementById('description').disabled = false; this.disabled = true;"
                        class="px-3 py-1 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Edit
                    </button>
                </div>
            </div>

            <!-- Language -->
            <div>
                <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                <div class="flex items-center gap-2">
                    <select id="language" name="language" placeholder="Enter the language of the literature" required disabled
                        class="block w-full p-2 border rounded mb-4">
                        @foreach (\App\Models\Literature::LANGUAGES as $val)
                            <option value="{{ $val }}">{{ ucfirst($val) }}</option>
                        @endforeach
                    </select>
                    <button type="button"
                        onclick="document.getElementById('language').disabled = false; this.disabled = true;"
                        class="px-3 py-1 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Edit
                    </button>
                </div>
            </div>

            <!-- PDF File -->
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700">Upload PDF</label>
                <input type="file" id="file" name="file" accept="application/pdf"
                    class="mt-1 block w-full text-gray-900 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full px-4 py-2 bg-blue-500 text-white font-medium text-sm rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Save Changes
                </button>
            </div>
        </form>
        <form action={{ route('literature.destroy', ['literature' => $variant->literature_id]) }} method="POST" class="space-y-6">
            @csrf
            @method('DELETE')
            <div>
                <button type="submit"
                    class="mt-10 w-full px-4 py-2 bg-red-500 text-white font-medium text-sm rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Delete Literature
                </button>
            </div>
        </form>
    </div>

</body>

</html>
