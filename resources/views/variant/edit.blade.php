<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Variant</title>
    @vite('resources/css/app.css')
</head>

<body>
    @component('components.header')
    @endcomponent

    <div class="pt-36 w-full flex flex-col items-center bg-white shadow-md rounded-lg">
        <h2 class="text-xl font-bold mb-6 text-center">You are currently editing the {{ ucfirst($variant->language) }}
            version</h2>
        <p class="mb-2">Choose a language to edit:</p>
        <div class="flex justify-center gap-3">
            @foreach ($variants as $var)
                <a href="{{ route('variant.edit', ['variant' => $var->id]) }}"
                    class="px-4 py-2 bg-blue-500 text-white font-medium text-sm rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    {{ ucfirst($var->language) }}
                </a>
            @endforeach
        </div>
        <div class="w-3/4 mt-10 bg-black bg-opacity-10 p-6 rounded-lg">
            <h1 class="text-2xl">Edit Literature</h1>
            <div class="mb-6">
                <p><strong>Title:</strong> <span id="current-title">{{ $variant->title }}</span></p>
                <p><strong>Description:</strong> <span id="current-description">{{ $variant->description }}</span></p>
            </div>

            <form action={{ route('variant.update', ['variant' => $variant->id]) }} enctype="multipart/form-data"
                method="POST" class="space-y-6">
                @csrf
                @method('PATCH')
                <!-- Title -->
                @if ($errors->any())
                    {{ implode('', $errors->all('<div>:message</div>')) }}
                @endif
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <div class="flex items-center gap-2">
                        <input type="text" id="title" name="title"
                            class="mt-1 block w-full border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:opacity-50" />
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <div class="flex items-center gap-2">
                        <textarea id="description" name="description" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:opacity-50"></textarea>
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
            <form action={{ route('literature.destroy', ['literature' => $variant->literature_id]) }} method="POST"
                class="space-y-6">
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
    </div>

</body>

</html>
