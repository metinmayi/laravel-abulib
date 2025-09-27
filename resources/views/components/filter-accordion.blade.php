<div class="ml-5 md:ml-0 mb-5 max-w-xs bg-white rounded-md shadow-sm border-2 border-orange-100">
    <!-- Accordion Header -->
    <div class="accordion-header cursor-pointer px-3 py-2 flex justify-between items-center hover:bg-orange-50 group">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <span class="text-xs text-gray-500" id="filter-count"></span>
        </div>
        <span class="accordion-icon transform transition-transform duration-200 text-orange-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </span>
    </div>

    <!-- Rest of the component remains the same -->
    <div class="accordion-content hidden">
        <!-- Languages Section -->
        <div class="px-3 py-2 border-t border-orange-100">
            <h3 class="text-xs font-medium text-orange-600 uppercase tracking-wider mb-2">{{ __('messages.languages') }}
            </h3>
            <div class="space-y-1" id="languages">
                @foreach (\App\Models\Variant::LANGUAGES as $lang)
                    <label
                        class="flex items-center space-x-2 text-sm text-gray-600 hover:bg-orange-50 rounded py-0.5 px-1 cursor-pointer">
                        <input type="checkbox" id="{{ $lang }}" value="{{ $lang }}"
                            class="w-3.5 h-3.5 rounded border-gray-300 text-orange-500 focus:ring-1 focus:ring-orange-200">
                        <span>{{ __('messages.' . $lang) }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Categories Section -->
        <div class="px-3 py-2 border-t border-orange-100">
            <h3 class="text-xs font-medium text-orange-600 uppercase tracking-wider mb-2">
                {{ __('messages.categories') }}</h3>
            <div class="space-y-1" id="categories">
                @foreach (\App\Models\Literature::CATEGORIES as $category)
                    <label
                        class="flex items-center space-x-2 text-sm text-gray-600 hover:bg-orange-50 rounded py-0.5 px-1 cursor-pointer">
                        <input type="checkbox" id="{{ $category }}" value="{{ $category }}"
                            class="w-3.5 h-3.5 rounded border-gray-300 text-orange-500 focus:ring-1 focus:ring-orange-200">
                        <span>{{ __('messages.' . $category) }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Selected Filters Summary and Apply Button -->
        <div class="px-3 py-2 border-t border-orange-100">
            <p class="text-xs text-gray-500 mb-2" id="selected-filters">{{ __('messages.no-filter-selected') }}</p>
            <button id="apply-filters"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium py-1.5 px-3 rounded transition-colors duration-150">
                {{ __('messages.apply-filters') }}
            </button>
        </div>
    </div>
</div>

<script type="module">
    const header = document.querySelector('.accordion-header');
    const content = document.querySelector('.accordion-content');
    const icon = document.querySelector('.accordion-icon');
    const applyButton = document.getElementById('apply-filters');
    const filterCount = document.getElementById('filter-count');
    const selectedText = @json(__('messages.selected'));

    // Set initial state from URL parameters
    function initializeFromURL() {
        const params = new URLSearchParams(window.location.search);
        const languages = params.get('languages')?.split(',') || [];
        const categories = params.get('categories')?.split(',') || [];

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = languages.includes(checkbox.value) || categories.includes(checkbox
                .value);
        });

        updateSelectedFilters();
    }

    // Accordion toggle
    header.addEventListener('click', () => {
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    });

    // Update selected filters display and count
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const selectedFiltersDisplay = document.getElementById('selected-filters');

    function updateSelectedFilters() {
        const selectedFilters = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        selectedFiltersDisplay.textContent = selectedFilters.length ?
            `${selectedText}: ${selectedFilters.join(', ')}` :
            @json(__('messages.no-filter-selected'));

        // Update filter count next to icon
        filterCount.textContent = selectedFilters.length ?
            `${selectedFilters.length} ${selectedText}` :
            @json(__('messages.filter-description'));
    }

    // Update URL with selected filters
    function updateURL() {
        const languages = Array.from(document.querySelectorAll('#languages input:checked'))
            .map(cb => cb.value);
        const categories = Array.from(document.querySelectorAll('#categories input:checked'))
            .map(cb => cb.value);

        const params = new URLSearchParams();
        if (languages.length) params.set('languages', languages.join(','));
        if (categories.length) params.set('categories', categories.join(','));

        // Update URL without reloading the page
        const newURL = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
        window.history.pushState({}, '', newURL);
    }

    // Event listeners
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedFilters);
    });

    applyButton.addEventListener('click', () => {
        updateURL();
        window.location.reload();
    });

    // Initialize from URL on page load
    initializeFromURL();
</script>
