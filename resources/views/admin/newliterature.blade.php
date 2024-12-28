<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Literature Management</title>
  @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex bg-gray-50 text-gray-800">
  @component('components.siderbar')@endcomponent
  <!-- Forms -->
  <main class="max-w-6xl p-6 mt-10 w-3/4">
    <h2 class="text-2xl font-semibold mb-4">Upload New Literature</h2>
    <form action="/literature" method="POST" enctype="multipart/form-data">
      @csrf
      <label for="file" class="block text-sm font-medium mb-2">Upload PDF:</label>
      <input type="file" id="pdf" name="file" accept="application/pdf" required class="block w-full p-2 border rounded mb-4">

      <label for="title" class="block text-sm font-medium mb-2">Title:</label>
      <input type="text" id="title" name="title" placeholder="Enter the title of the literature" required class="block w-full p-2 border rounded mb-4">

      <label for="description" class="block text-sm font-medium mb-2">Description:</label>
      <textarea type="text" id="description" name="description" placeholder="Enter the description of the literature" required class="block w-full p-2 border rounded mb-4"></textarea>

      <label for="language" class="block text-sm font-medium mb-2">Language:</label>
      <select id="language" name="language" placeholder="Enter the language of the literature" required class="block w-full p-2 border rounded mb-4">
        @foreach (\App\Models\Literature::LANGUAGES as $val)
        <option value="{{ $val }}">{{ ucfirst($val) }}</option>
        @endforeach
      </select>
      <label for="category" class="block text-sm font-medium mb-2">Category:</label>
      <select id="category" name="category" placeholder="Select the category of the literature" required class="block w-full p-2 border rounded mb-4">
        @foreach (\App\Models\Literature::CATEGORIES as $val)
        <option value="{{ $val }}">{{ ucfirst($val) }}</option>
        @endforeach
      </select>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
    </form>
  </main>


</body>

</html>