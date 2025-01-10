<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <meta name="description"
        content="Abdulbaqi Mayi (Abdulbaghi Ahmad) is a renowned child and adolescent psychiatrist working to improve mental health care in Sweden and Kurdistan.">
    @vite('resources/css/app.css')
</head>

<body>
    @component('components.header')
    @endcomponent
    <div class="max-w-7xl mx-auto">

        <h1 class="ml-5 md:ml-0 text-3xl font-bold text-orange-600 pt-36 mb-8">{{ __('messages.Library') }}</h1>
        @component('components.filter-accordion')
        @endcomponent
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-11/12 mx-auto md:w-full">
            <!-- Fiction Card -->
            @foreach ($literatureList as $item)
                <div
                    class="bg-white rounded-lg overflow-hidden transform transition-all duration-300 hover:scale-105 hover:bg-orange-50 ring-1 ring-black ring-opacity-5 shadow-[0_4px_12px_rgba(234,88,12,0.25)]">
                    <a href="{{ "reader/$item->variantId" }}">
                        <div class="p-6">
                            <div class="text-sm font-semibold text-indigo-600 mb-2">{{ __(ucfirst($item->category)) }}
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">{{ __(ucfirst($item->title)) }}</h2>
                            <p class="text-gray-600 mb-4">{{ __(ucfirst($item->description ?? '-')) }}</p>
                            <div class="flex gap-2">
                                @foreach ($item->availableLanguages as $lang)
                                    <img src="{{ asset("images/$lang-flag.svg") }}" alt={{ $lang . ' flag' }}
                                        class="px-2 bg-gray-100 text-sm rounded-md" />
                                @endforeach
                            </div>
                        </div>
                    </a>
                    @auth
                        <a href="{{ route('variant.edit', ['variant' => $item->variantId]) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md transition-colors">Edit</a>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>
