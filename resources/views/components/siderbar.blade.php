    <div class="w-1/4 bg-white p-6">
        <h1 class="text-3xl font-serif leading-tight mb-10">
            <a href="/">Abdulbaghi<br>Ahmad</a>
        </h1>
        <nav>
            <ul class="space-y-4">
                <li><a href="#about" class="text-lg hover:text-gray-500">About Abdulbaghi</a></li>
                <li><a href="/library" class="text-lg hover:text-gray-500">Library</a></li>
                <li><a href="/login" class="text-lg hover:text-gray-500">Login</a></li>
                @auth
                    <li><a href="{{ route('literature.create') }}" class="text-lg hover:text-gray-500">Upload literature</a>
                    </li>
                @endauth

            </ul>
        </nav>
    </div>
