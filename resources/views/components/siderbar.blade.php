    <div class="w-1/4 bg-white p-6">
        <h1 class="text-3xl font-serif leading-tight mb-10">
            <a href="/">Abdulbaghi<br>Ahmad</a>
        </h1>
        <nav>
            <ul class="space-y-4">
                <li><a href="#about" class="text-lg hover:text-gray-500">About Abdulbaghi</a></li>
                <li><a href="/library" class="text-lg hover:text-gray-500">Library</a></li>
                <li><a href="/login" class="text-lg hover:text-gray-500">Login</a></li>
                <li><a href="/admin" class="text-lg hover:text-gray-500">Admin</a></li>
            </ul>

            @auth
            <h1 class="mt-10 text-3xl">Admin Panel</h1>
            <ul class="space-y-4">
                <li><a href="/admin/newliterature" class="text-lg hover:text-gray-500">Upload literature</a></li>
                <li><a href="/admin/newvariant" class="text-lg hover:text-gray-500">Upload Language Variant</a></li>
            </ul>
            @endauth
        </nav>
    </div>