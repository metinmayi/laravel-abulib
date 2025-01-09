<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literature Management</title>
    @vite('resources/css/app.css')
    @vite('resources/css/read.css')
</head>

<body>
    @component('components.header')
    @endcomponent
    <section class="flex justify-center align-center pt-32">
        <div class="w-3/4">
            <div>
                <div class="flex flex-wrap gap-4">
                    @foreach ($literatureItem['availableVariants'] as $variant)
                        <a href="{{ route('read.index', ['variantId' => $variant['id']]) }}"
                            class="text-orange-600 border border-orange-600 px-2 py-1 rounded">{{ __('messages.' . ucfirst($variant['language'])) }}</a>
                    @endforeach
                </div>
                <h3 class="text-orange-400 text-3xl mt-8">{{ __('messages.' . ucfirst($literatureItem['category'])) }}
                </h3>
                <h1 class="cs-title">{{ $literatureItem['title'] }}</h1>
                <p class="text-lg mb-10">{{ $literatureItem['description'] }}</p>
            </div>
            @if (isset($literatureItem['url']))
                <a href="{{ route('read.getLiteratureFile', ['id' => $literatureItem['id']]) }}"
                    class="text-white bg-green-600 border border-green-600 px-6 py-3 rounded">{{ __('Read PDF') }}</a>
            @else
                <p>{{ __('messages.no-pdf') }}
                </p>
            @endif
    </section>

</html>
