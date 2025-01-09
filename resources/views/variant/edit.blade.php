<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Variant</title>
    <meta name="description"
        content="Abdulbaqi Mayi (Abdulbaghi Ahmad) is a renowned child and adolescent psychiatrist working to improve mental health care in Sweden and Kurdistan.">
    @vite('resources/css/app.css')
</head>

<body>
    @component('components.header')
    @endcomponent
    {{-- {{ dd(get_defined_vars()) }} --}}

    <div class="max-w-3xl mx-auto pt-36">
        <h1 class="text-2xl font-bold text-gray-900">
            You are currently editing the <span class="text-orange-600">{{ ucfirst($variant->language) }}</span> version
        </h1>

        <div class="flex gap-3 my-6">
            @foreach ($variants as $var)
                @if ($var->language === $variant->language)
                    <a href="{{ route('variant.edit', ['variant' => $var->id]) }}"
                        class="px-4 py-2 bg-orange-600 text-white border border-white-600 rounded-md text-center">{{ ucfirst($var->language) }}</a>
                @else
                    <a href="{{ route('variant.edit', ['variant' => $var->id]) }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50 text-center">{{ ucfirst($var->language) }}</a>
                @endif
            @endforeach
        </div>

        <div class="bg-orange-100 rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-medium mb-6">Current Content</h2>
            <div class="mb-4">
                <label class="text-xl block mb-1">Title</label>
                <div>{{ $variant->title }}</div>
            </div>
            <div>
                <label class="block text-xl mb-1">Description</label>
                <div class="text-gray-900">{{ $variant->description ?? '-' }}</div>
            </div>
        </div>

        <div class="bg-orange-100 rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Content</h2>
            <form action={{ route('variant.update', ['variant' => $variant->id]) }} enctype="multipart/form-data"
                method="POST" class="space-y-6">
                @csrf
                @method('PATCH')
                <!-- Title -->
                @if ($errors->any())
                    {{ implode('', $errors->all('<div>:message</div>')) }}
                @endif
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">New Title</label>
                    <input type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter new title" name="title">
                </div>
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">New Description</label>
                    <textarea rows="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter new description" name="description"></textarea>
                </div>
                <div class="mb-8">
                    <label class="block font-medium text-gray-700 mb-1">New PDF File</label>
                    <input type="file" name="file" accept=".pdf"
                        class="w-full text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

</body>

</html>
