<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kennedia Consulting — Convocation Extractor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/convocation.js'])
</head>
<body class="bg-emerald-50/40 text-gray-900 font-sans">
    <header class="bg-gradient-to-r from-[#2d9657] to-[#206d43] text-white border-b-4 border-[#1b5b38]">
        <div class="max-w-5xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 ring-1 ring-white/20 backdrop-blur flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('logo.png') }}" alt="Kennedia Consulting" class="h-7 w-auto" />
                    </div>
                    <div>
                        <h1 class="m-0 text-lg font-semibold tracking-tight">Kennedia Consulting</h1>
                        <p class="m-0 text-xs opacity-90">Convocation PDF Extraction Console</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm px-4 py-2 bg-white/10 ring-1 ring-white/20 text-white rounded-lg hover:bg-white/15 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-6">
        <section class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm mb-6">
            <h2 class="text-xl font-semibold mb-4">Upload Convocation PDF</h2>
            <form id="uploadForm" class="space-y-3" method="POST" action="javascript:void(0);" onsubmit="return false;">
                @csrf
                <div class="flex flex-col gap-2">
                    <label for="file" class="font-medium">PDF File</label>
                    <input id="file" name="file" type="file" accept="application/pdf" required class="rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#2d9657]" />
                    <small class="text-gray-500 text-xs">Max upload size: 100MB (PDF only)</small>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="session" class="font-medium">Session (optional)</label>
                    <input id="session" name="session" type="text" placeholder="e.g. 2021/2022" class="rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#2d9657]" />
                    <small class="text-gray-500 text-xs">If not provided, will be auto-detected from PDF</small>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="api_key_tier" class="font-medium">API Key Selection</label>
                    <select id="api_key_tier" name="api_key_tier" class="rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#2d9657]">
                        <option value="GEMINI_API_KEY_FREE_TIER_1" selected>Free Tier 1</option>
                        <option value="GEMINI_API_KEY_FREE_TIER_2">Free Tier 2</option>
                        <option value="GEMINI_API_KEY_FREE_TIER_3">Free Tier 3</option>
                        <option value="GEMINI_API_KEY_FREE_TIER_4">Free Tier 4</option>
                        <option value="GEMINI_API_KEY_FREE_TIER_5">Free Tier 5</option>
                        <option value="GEMINI_API_KEY_FREE_TIER_6">Free Tier 6</option>
                        <option value="GEMINI_API_KEY_FREE_TIER_7">Free Tier 7</option>
                        <option value="GEMINI_API_KEY_FREE_TIER_8">Free Tier 8</option>
                        <option value="GEMINI_API_KEY_FREE_TIER_9">Free Tier 9</option>
                        <option value="GEMINI_API_KEY_FREE_TIER_10">Free Tier 10</option>
                        <option value="GEMINI_API_KEY_PAID">Paid Tier</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="page_start" class="font-medium">Start Page (optional)</label>
                        <input id="page_start" name="page_start" type="number" min="1" placeholder="e.g. 1" class="rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#2d9657]" />
                    </div>
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="page_end" class="font-medium">End Page (optional)</label>
                        <input id="page_end" name="page_end" type="number" min="1" placeholder="e.g. 10" class="rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#2d9657]" />
                    </div>
                </div>
                <div id="pageValidationError" class="text-red-600 text-sm hidden">End page must be greater than or equal to start page</div>
                
                <!-- Progress bar -->
                <div id="uploadProgress" class="hidden">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div id="progressBar" class="bg-[#2d9657] h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p id="progressText" class="text-sm text-gray-600 mt-2 text-center">Uploading...</p>
                </div>
                
                <button id="uploadBtn" type="submit" class="w-full py-3 bg-[#2d9657] text-white font-semibold rounded-lg hover:bg-[#206d43] transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Upload and Extract
                </button>
            </form>
            <div id="uploadMsg" class="mt-3 text-sm"></div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm mb-6">
            <h2 class="text-xl font-semibold mb-3">Documents</h2>
            <div class="overflow-auto">
                <table class="w-full text-sm border-collapse" id="docsTable">
                    <thead>
                        <tr class="bg-gray-50 text-gray-900">
                            <th class="text-left p-2 border-b">ID</th>
                            <th class="text-left p-2 border-b">Filename</th>
                            <th class="text-left p-2 border-b">Session</th>
                            <th class="text-left p-2 border-b">Status</th>
                            <th class="text-left p-2 border-b">CSV</th>
                            <th class="text-left p-2 border-b">XLSX</th>
                            <th class="text-left p-2 border-b">Created</th>
                            <th class="text-left p-2 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm mb-6">
            <h2 class="text-xl font-semibold mb-3">Extracted Students</h2>
            <div class="overflow-auto">
                <table class="w-full text-sm border-collapse" id="resultsTable">
                    <thead>
                        <tr class="bg-gray-50 text-gray-900">
                            <th class="text-left p-2 border-b">Surname</th>
                            <th class="text-left p-2 border-b">First Name</th>
                            <th class="text-left p-2 border-b">Other Name</th>
                            <th class="text-left p-2 border-b">Course</th>
                            <th class="text-left p-2 border-b">Faculty</th>
                            <th class="text-left p-2 border-b">Grade</th>
                            <th class="text-left p-2 border-b">Qualification</th>
                            <th class="text-left p-2 border-b">Session</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div id="searchMsg" class="text-sm text-gray-600 mt-2"></div>
        </section>
    </main>

    <footer class="text-center text-emerald-950 py-6">
        <div class="max-w-5xl mx-auto px-4">
            <small>© <span id="year"></span> Kennedia Consulting</small>
        </div>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
