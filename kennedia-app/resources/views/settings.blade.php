<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kennedia Consulting - Settings</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white text-gray-900" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <header class="bg-[#1F6F4A] text-white border-b border-[#1F6F4A]">
        <div class="max-w-5xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 ring-1 ring-white/20 backdrop-blur flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('2.png') }}" alt="Kennedia Consulting" class="h-7 w-auto" />
                    </div>
                    <div>
                        <h1 class="m-0 text-lg font-semibold tracking-tight">Settings</h1>
                        <p class="m-0 text-xs opacity-90">Manage your account password</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="text-sm px-4 py-2 bg-white/10 ring-1 ring-white/20 text-white rounded-lg hover:bg-white/15 transition">Dashboard</a>
                    <a href="{{ route('how.to.use') }}" class="text-sm px-4 py-2 bg-white/10 ring-1 ring-white/20 text-white rounded-lg hover:bg-white/15 transition">How To Use</a>
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
                <div class="mb-4 rounded-lg border border-[#1F6F4A]/40 bg-[#1F6F4A]/5 text-[#1F6F4A] px-3 py-2 text-sm">
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
                    <input id="current_password" name="current_password" type="password" required class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#1F6F4A]" />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">New Password</label>
                    <input id="password" name="password" type="password" required minlength="8" class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#1F6F4A]" />
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirm New Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" class="w-full rounded-lg border border-gray-300 px-3 py-2 outline-none focus:border-[#1F6F4A]" />
                </div>
                <button type="submit" class="w-full py-3 bg-[#1F6F4A] text-white font-semibold rounded-lg hover:bg-[#9BC53D] transition">
                    Update Password
                </button>
            </form>
        </section>
    </main>
</body>
</html>
