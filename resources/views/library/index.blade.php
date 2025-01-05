<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    @vite('resources/css/app.css')
</head>

<body>
    @component('components.header')
    @endcomponent
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-orange-600 pt-36 mb-8">{{ __('Library') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Fiction Card -->
            @foreach ($literatureList as $item)
                <a href="{{ "reader/$item->variantId" }}">
                    <div
                        class="bg-white rounded-lg overflow-hidden transform transition-all duration-300 hover:scale-105 hover:bg-orange-50 ring-1 ring-black ring-opacity-5 shadow-[0_4px_12px_rgba(234,88,12,0.25)]">
                        <div class="p-6">
                            <div class="text-sm font-semibold text-indigo-600 mb-2">{{ __(ucfirst($item->category)) }}
                            </div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">{{ __(ucfirst($item->title)) }}</h2>
                            <p class="text-gray-600 mb-4">{{ __(ucfirst($item->description ?? '-')) }}</p>
                            <div class="flex gap-2">
                                @foreach ($item->availableLanguages as $lang)
                                    <img src="{{ asset("images/$lang-flag.svg") }}"
                                        class="px-2 bg-gray-100 text-sm rounded-md" />
                                @endforeach

                            </div>
                        </div>
                    </div>
                </a>
            @endforeach

        </div>
    </div>

</body>

</html>
