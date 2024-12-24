<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Literature Management</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-center mb-8">Literature Management</h1>

    <!-- Menu -->
    <div class="flex justify-center space-x-4 mb-8">
      <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" href="/admin/newliterature">Upload New Literature</button>
      <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="showForm('add-language')">Add Language</button>
      <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="showForm('edit')">Edit Literature</button>
      <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="showForm('delete')">Delete Literature</button>
      <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <!-- Forms -->
    <div id="upload" class="form-container hidden bg-white p-6 rounded shadow-md">
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
        <input type="text" id="language" name="language" placeholder="Enter the language of the literature" required class="block w-full p-2 border rounded mb-4">

        <label for="category" class="block text-sm font-medium mb-2">category:</label>
        <input type="text" id="category" name="category" placeholder="Enter the category of the literature" required class="block w-full p-2 border rounded mb-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
      </form>
    </div>

    <div id="add-language" class="form-container hidden bg-white p-6 rounded shadow-md">
      <h2 class="text-2xl font-semibold mb-4">Add Language to Existing Literature</h2>
      <form action="/add-language" method="POST">
        <label for="literature-id" class="block text-sm font-medium mb-2">Literature ID:</label>
        <input type="text" id="literature-id" name="literature-id" placeholder="Enter the literature ID" required class="block w-full p-2 border rounded mb-4">

        <label for="language" class="block text-sm font-medium mb-2">Language:</label>
        <input type="text" id="language" name="language" placeholder="Enter the new language" required class="block w-full p-2 border rounded mb-4">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
      </form>
    </div>

    <div id="edit" class="form-container hidden bg-white p-6 rounded shadow-md">
      <h2 class="text-2xl font-semibold mb-4">Edit Literature</h2>
      <form action="/edit" method="POST">
        <label for="literature-id-edit" class="block text-sm font-medium mb-2">Literature ID:</label>
        <input type="text" id="literature-id-edit" name="literature-id" placeholder="Enter the literature ID" required class="block w-full p-2 border rounded mb-4">

        <label for="new-name" class="block text-sm font-medium mb-2">New Name:</label>
        <input type="text" id="new-name" name="new-name" placeholder="Enter the new name" required class="block w-full p-2 border rounded mb-4">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
      </form>
    </div>

    <div id="delete" class="form-container hidden bg-white p-6 rounded shadow-md">
      <h2 class="text-2xl font-semibold mb-4">Delete Literature</h2>
      <form action="/delete" method="POST">
        <label for="literature-id-delete" class="block text-sm font-medium mb-2">Literature ID:</label>
        <input type="text" id="literature-id-delete" name="literature-id" placeholder="Enter the literature ID" required class="block w-full p-2 border rounded mb-4">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Delete</button>
      </form>
    </div>
  </div>


</body>
</html>
