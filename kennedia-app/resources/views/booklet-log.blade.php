<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kennedia Consulting - Booklet Log</title>

    @vite(['resources/css/app.css', 'resources/js/booklet-log.js'])
</head>
<body class="bg-white text-slate-900" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <header class="bg-[#1F6F4A] text-white border-b border-[#1F6F4A]">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-white/10 ring-1 ring-white/20 backdrop-blur flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('2.png') }}" alt="Kennedia Consulting" class="h-7 w-auto" />
                    </div>
                    <div>
                        <h1 class="m-0 text-lg font-semibold tracking-tight">Kennedia Consulting</h1>
                        <p class="m-0 text-xs text-white/80">Booklet extraction logs and analytics</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-white/10">Dashboard</a>
                    <a href="{{ route('booklet.log') }}" class="text-sm px-3 py-2 rounded-lg bg-white/10">Booklet Log</a>
                    <a href="{{ route('how.to.use') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-white/10">How To Use</a>
                    <a href="{{ route('settings') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-white/10">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm px-4 py-2 bg-[#9BC53D] text-[#3A3A3A] rounded-lg hover:bg-[#8ab530] transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-6">
        <section class="rounded-2xl border border-[#1F6F4A]/20 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Booklet Log</h2>
                    <p class="text-sm text-slate-600 mt-1">Filter uploads and successful PDF extraction performance by month/year, with student rows returned per PDF.</p>
                </div>
                <form id="bookletLogFilterForm" class="flex items-end gap-3 flex-wrap">
                    <div>
                        <label for="filterYear" class="block text-sm font-medium text-slate-700 mb-1">Year</label>
                        <input id="filterYear" type="number" min="2000" max="2100" placeholder="e.g. 2026" class="rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-[#1F6F4A]" />
                    </div>
                    <div>
                        <label for="filterMonth" class="block text-sm font-medium text-slate-700 mb-1">Month</label>
                        <select id="filterMonth" class="rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-[#1F6F4A]">
                            <option value="">All months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <button type="submit" class="rounded-lg bg-[#1F6F4A] text-white px-4 py-2 text-sm font-semibold hover:bg-[#9BC53D] transition">Apply filter</button>
                    <button id="clearFilterBtn" type="button" class="rounded-lg border border-slate-300 text-slate-700 px-4 py-2 text-sm font-semibold hover:bg-slate-100 transition">Clear</button>
                </form>
            </div>

            <div id="bookletLogMsg" class="mt-3 text-sm text-slate-600"></div>
        </section>

        <section class="mt-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-slate-500">All-time uploads</div>
                <div id="overallUploads" class="mt-2 text-3xl font-bold">0</div>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-slate-500">All-time successful PDFs</div>
                <div id="overallSuccessful" class="mt-2 text-3xl font-bold">0</div>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-slate-500">All-time extracted student rows</div>
                <div id="overallRows" class="mt-2 text-3xl font-bold">0</div>
            </article>
            <article class="rounded-xl border border-[#1F6F4A]/30 bg-white p-4 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-[#1F6F4A]">Filtered uploads</div>
                <div id="filteredUploads" class="mt-2 text-3xl font-bold text-[#1F6F4A]">0</div>
            </article>
            <article class="rounded-xl border border-[#1F6F4A]/30 bg-white p-4 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-[#1F6F4A]">Filtered successful PDFs</div>
                <div id="filteredSuccessful" class="mt-2 text-3xl font-bold text-[#1F6F4A]">0</div>
            </article>
            <article class="rounded-xl border border-[#1F6F4A]/30 bg-white p-4 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-[#1F6F4A]">Filtered extracted student rows</div>
                <div id="filteredRows" class="mt-2 text-3xl font-bold text-[#1F6F4A]">0</div>
            </article>
        </section>

        <section class="mt-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <h3 class="text-lg font-semibold text-slate-900">Booklet extraction records</h3>
                <div class="text-sm text-slate-600">Each row shows uploaded booklet, extraction status, pages, and number of student rows extracted.</div>
            </div>
            <div class="mt-3 overflow-auto">
                <table class="w-full text-sm border-collapse" id="bookletLogTable">
                    <thead>
                        <tr class="bg-slate-50 text-slate-900">
                            <th class="text-left p-2 border-b">ID</th>
                            <th class="text-left p-2 border-b">Filename</th>
                            <th class="text-left p-2 border-b">Session</th>
                            <th class="text-left p-2 border-b">Status</th>
                            <th class="text-left p-2 border-b">Pages</th>
                            <th class="text-left p-2 border-b">Pages w/ Results</th>
                            <th class="text-left p-2 border-b">Students rows</th>
                            <th class="text-left p-2 border-b">API tier</th>
                            <th class="text-left p-2 border-b">Created</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="text-center text-slate-700 py-6">
        <div class="max-w-6xl mx-auto px-4">
            <small>© <span id="year"></span> Kennedia Consulting</small>
        </div>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
