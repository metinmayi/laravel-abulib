<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literature Management</title>
    <meta name="description"
        content="Abdulbaqi Mayi (Abdulbaghi Ahmad) is a renowned child and adolescent psychiatrist working to improve mental health care in Sweden and Kurdistan.">
    @vite('resources/css/app.css')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabButtons = document.querySelectorAll('[data-lang-tab]');
            const panels = document.querySelectorAll('[data-lang-panel]');

            function activate(lang) {
                tabButtons.forEach(btn => {
                    const isActive = btn.dataset.langTab === lang;
                    // Reset potential previous active styles first (if any were manually added later)
                    btn.classList.toggle('bg-white', isActive);
                    btn.classList.toggle('text-orange-700', isActive);
                    btn.classList.toggle('border-orange-400', isActive);
                    btn.classList.toggle('border-b-transparent', isActive);
                    btn.classList.toggle('shadow-sm', isActive);
                    btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    btn.tabIndex = isActive ? 0 : -1;
                });
                panels.forEach(p => {
                    const show = p.dataset.langPanel === lang;
                    p.classList.toggle('hidden', !show);
                    p.classList.toggle('active-panel', show);
                });
            }

            tabButtons.forEach(btn => {
                btn.addEventListener('click', e => {
                    e.preventDefault();
                    activate(btn.dataset.langTab);
                });
                btn.addEventListener('keydown', e => {
                    const currentIndex = Array.from(tabButtons).indexOf(btn);
                    if (['ArrowRight','ArrowLeft','Home','End'].includes(e.key)) {
                        e.preventDefault();
                        let newIndex = currentIndex;
                        if (e.key === 'ArrowRight') newIndex = (currentIndex + 1) % tabButtons.length;
                        if (e.key === 'ArrowLeft') newIndex = (currentIndex - 1 + tabButtons.length) % tabButtons.length;
                        if (e.key === 'Home') newIndex = 0;
                        if (e.key === 'End') newIndex = tabButtons.length - 1;
                        const target = tabButtons[newIndex];
                        target.focus();
                        activate(target.dataset.langTab);
                    }
                });
            });

            // Activate first tab by default
            if (tabButtons.length) {
                activate(tabButtons[0].dataset.langTab);
            }
        });
    </script>
</head>

<body>
    @component('components.header')
    @endcomponent
    <div class="max-w-4xl mx-auto pt-36">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Upload <span class="text-orange-600">New Literature</span></h1>

        <form id="literatureForm" action="/literature" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Unified Container -->
            <div class="mt-4 rounded-xl border border-orange-300 bg-orange-50/70 backdrop-blur-sm shadow-sm">
                <div class="p-6 pb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category" name="category" required
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                        @foreach (\App\Models\Literature::CATEGORIES as $val)
                            <option value="{{ $val }}">{{ ucfirst($val) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Language Tabs -->
                <div class="px-4">
                    <div role="tablist" aria-label="Languages" class="flex flex-wrap gap-1 -mb-px">
                        @foreach (\App\Models\Literature::LANGUAGES as $val)
                            <button type="button" role="tab" aria-selected="false"
                                data-lang-tab="{{ $val }}"
                                class="px-4 py-2 text-sm font-medium rounded-t-md border border-transparent border-b-2 border-b-orange-300/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-0 transition-colors">
                                {{ ucfirst($val) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-orange-200 mt-0 rounded-b-xl bg-white/60">
                    @foreach (\App\Models\Literature::LANGUAGES as $val)
                        <div data-lang-panel="{{ $val }}" role="tabpanel" tabindex="0" aria-labelledby="lang-tab-{{ $val }}" class="hidden">
                            <div class="p-6 space-y-4">
                                <div>
                                    <label for="{{ $val . '-title' }}" class="block text-sm font-medium text-gray-700">Title</label>
                                    <input id="{{ $val . '-title' }}" name="literatures[{{ $val }}][title]" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                                </div>
                                <div>
                                    <label for="{{ $val . '-description' }}" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea id="{{ $val . '-description' }}" name="literatures[{{ $val }}][description]" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400"></textarea>
                                </div>
                                <div>
                                    <label for="{{ $val . '-file' }}" class="block text-sm font-medium text-gray-700">File (PDF)</label>
                                    <input id="{{ $val . '-file' }}" name="literatures[{{ $val }}][file]" accept="application/pdf" type="file" accept=".pdf" class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 focus:outline-none">
                                </div>
                                <input type="hidden" name="literatures[{{ $val }}][language]" value="{{ $val }}">
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- End Language Tabs -->
            </div>


            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 py-2 px-5 border border-green-600 rounded-md shadow-sm text-sm font-semibold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 active:scale-[.98] transition">
                    Upload Literature
                </button>
            </div>
        </form>
    </div>
</body>

</html>
