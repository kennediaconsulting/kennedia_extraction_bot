<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kennedia Consulting - Settings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-emerald-50/40 text-gray-900 font-sans">
    <header class="bg-gradient-to-r from-[#2d9657] to-[#206d43] text-white border-b-4 border-[#1b5b38]">
        <div class="max-w-5xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="m-0 text-lg font-semibold tracking-tight">Settings</h1>
                    <p class="m-0 text-xs opacity-90">Manage your account password</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="text-sm px-4 py-2 bg-white/10 ring-1 ring-white/20 text-white rounded-lg hover:bg-white/15 transition">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm px-4 py-2 bg-white/10 ring-1 ring-white/20 text-white rounded-lg hover:bg-white/15 transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-8">
        <section class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-1">Update Password</h2>
            <p class="text-sm text-gray-600 mb-4">Signed in as {{ $userEmail }}</p>

            @if(session('success'))
                <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-800 px-3 py-2 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-lg border border-red-300 bg-red-50 text-red-700 px-3 py-2 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('settings.password') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="current_password" class="block text-sm font-medium mb-1">Current Password</label>
                    <input id="current_password" name="current_password" type="password" required class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#2d9657]" />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">New Password</label>
                    <input id="password" name="password" type="password" required minlength="8" class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#2d9657]" />
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirm New Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#2d9657]" />
                </div>
                <button type="submit" class="w-full py-3 bg-[#2d9657] text-white font-semibold rounded-lg hover:bg-[#206d43] transition">
                    Update Password
                </button>
            </form>
        </section>
    </main>
</body>
</html>
