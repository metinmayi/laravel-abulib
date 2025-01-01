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
                <h4 class="text-2xl mt-3">Languages: </h4>
                <ul class="list-disc ml-5 mt-2 mb-5">
                    @foreach ($literatureItem['availableLanguages'] as $lang)
                        <li>{{ ucfirst($lang) }}</li>
                    @endforeach
                </ul>
            </div>
            <object class="w-7/8 h-screen">
                <embed src="{{ route('read.getLiteratureBinary', ['id' => $literatureItem['id']]) }}"
                    type="application/pdf" width="100%" height="600px" />
            </object>
    </section>

</html>
