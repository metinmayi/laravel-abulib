<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Literature Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-center mb-8">Literature Management</h1>

    <!-- Menu -->
    <div class="flex justify-center space-x-4 mb-8">
      <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" href="/admin/newliterature">Upload New Literature</a>
      <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="showForm('add-language')">Add Language</a>
      <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="showForm('edit')">Edit Literature</a>
      <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="showForm('delete')">Delete Literature</a>
    </div>
    <!-- Forms -->
    <div id="upload" class="form-container bg-white p-6 rounded shadow-md">
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

        <label for="category" class="block text-sm font-medium mb-2">Category:</label>
        <select id="category" name="category" placeholder="Select the category of the literature" required class="block w-full p-2 border rounded mb-4">
          <option value="article">Article</option>
          <option value="book">Book</option>
          <option value="poem">Poem</option>
          <option value="research">Research</option>
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
      </form>
    </div>
  </div>


</body>
</html>
