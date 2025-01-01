<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literature Management</title>
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
    <!-- Forms -->
    <main class="max-w-6xl p-6 pt-36 w-3/4">
        <h2 class="text-2xl font-semibold mb-4">Upload New Literature</h2>
        <form id="literatureForm" action="/literature" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Category Input (Shared across all forms) -->
            <label for="category" class="block text-sm font-medium mb-2">Category:</label>
            <select id="category" name="category" placeholder="Select the category of the literature" required
                class="block w-full p-2 border rounded mb-4">
                @foreach (\App\Models\Literature::CATEGORIES as $val)
                    <option value="{{ $val }}">{{ ucfirst($val) }}</option>
                @endforeach
            </select>

            <!-- Language-Specific Forms -->
            <div id="languages">
                @foreach (\App\Models\Literature::LANGUAGES as $val)
                    <div class="language-form mb-8 p-6 border rounded-lg bg-white shadow-md">
                        <!-- Collapsible Header -->
                        <button type="button" onclick="toggleSection('{{ $val }}')"
                            class="text-xl font-semibold text-gray-700 w-full text-left mb-4">
                            {{ ucfirst($val) }} Literature
                            <span class="ml-2">{{ '▼' }}</span>
                        </button>

                        <!-- Collapsible Section -->
                        <div id="{{ $val }}-section" class="hidden">
                            <!-- Divider -->
                            <div class="border-t border-gray-300 my-4"></div>

                            <!-- Language Title -->
                            <label for="{{ $val . '-title' }}"
                                class="block text-sm font-medium mb-2">{{ ucfirst($val) . ' Title' }}:</label>
                            <input type="text" id="{{ $val . '-title' }}"
                                name="literatures[{{ $val }}][title]"
                                placeholder="{{ 'Enter the ' . $val . ' title of the literature' }}" required
                                class="block w-full p-2 border rounded mb-4">

                            <!-- Language Description -->
                            <label for="{{ $val . '-description' }}"
                                class="block text-sm font-medium mb-2">{{ ucfirst($val) . ' Description' }}:</label>
                            <textarea id="{{ $val . '-description' }}" name="literatures[{{ $val }}][description]"
                                placeholder="{{ 'Enter the ' . $val . ' description of the literature' }}"
                                class="block w-full p-2 border rounded mb-4"></textarea>

                            <!-- Language File Upload (Optional for each language) -->
                            <label for="{{ $val . '-file' }}"
                                class="block text-sm font-medium mb-2">{{ ucfirst($val) . ' File' }}
                                (Optional)
                                :</label>
                            <input type="file" id="{{ $val . '-file' }}"
                                name="literatures[{{ $val }}][file]" accept="application/pdf"
                                class="block w-full p-2 border rounded mb-4">

                            <!-- Hidden Language Field -->
                            <input type="hidden" name="literatures[{{ $val }}][language]"
                                value="{{ $val }}">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Submit Button -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
        </form>
    </main>
</body>

</html>
