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
    <section class="flex justify-center align-center pt-36">
        <div class="w-3/4">
            <div>
                <h3 class="text-orange-400 text-3xl">{{ ucfirst($literatureItem['category']) }}</h3>
                <h1 class="cs-title">{{ $literatureItem['title'] }}</h1>
                <p class="text-lg">{{ $literatureItem['description'] }}</p>
                <div class="flex mt-5 flex-wrap gap-4 mb-8">
                    @foreach ($literatureItem['availableVariants'] as $variant)
                        <a href="{{ route('read.index', ['variantId' => $variant['id']]) }}"
                            class="text-orange-600 border border-orange-600 px-4 py-2 rounded hover:bg-orange-600 hover:text-white transition">{{ ucfirst($variant['language']) }}</a>
                    @endforeach
                </div>
            </div>
            @if (isset($literatureItem['url']))
                <object class="w-7/8 h-screen">
                    <embed src="{{ route('read.getLiteratureBinary', ['id' => $literatureItem['id']]) }}"
                        type="application/pdf" width="100%" height="600px" />
                </object>
            @else
                <p>No literature PDF available for this language. Please select another language
                    from the list above.</p>
            @endif
    </section>

</html>
