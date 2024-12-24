<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catherine Conelly</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex bg-gray-50 text-gray-800">
   @component('components.siderbar')
       
   @endcomponent 
    <!-- Main Content -->
    <div class="w-3/4 flex flex-col items-center justify-center bg-gray-100">
        <img src="{{ asset('images/abdul.jpg') }}" alt="Catherine Conelly" class="w-2/3 max-w-md rounded-lg shadow-md">
        <footer class="mt-8">
            <p class="text-lg italic">Abdulbaghi Ahmad, Child and Adolescence Psychiatrist</p>
        </footer>
    </div>
</body>
</html>
