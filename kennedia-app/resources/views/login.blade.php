<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Kennedia Consulting</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-white via-emerald-50 to-green-100 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#2d9657] to-[#206d43] text-white px-8 py-7">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 ring-1 ring-white/20 backdrop-blur flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('logo.png') }}" alt="Kennedia Consulting" class="h-10 w-auto" />
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-center tracking-tight">Kennedia Consulting</h1>
                <p class="text-center text-sm opacity-90 mt-1">Convocation Extractor Console</p>
            </div>

            <!-- Login Form -->
            <div class="px-8 py-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 text-center">Sign In</h2>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                        <ul class="text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d9657] focus:border-[#2d9657] outline-none transition"
                            placeholder="admin@kennediaconsulting.net"
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2d9657] focus:border-[#2d9657] outline-none transition"
                            placeholder="••••••••"
                        />
                    </div>

                    <button 
                        id="loginSubmitBtn"
                        type="submit"
                        class="w-full bg-[#2d9657] hover:bg-[#206d43] text-white font-semibold px-4 py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center gap-2"
                    >
                        <span id="loginSubmitText">Sign In</span>
                        <svg id="loginSubmitSpinner" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"></path>
                        </svg>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Default credentials: admin@kennediaconsulting.net / admin@1234
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">© {{ date('Y') }} Kennedia Consulting</p>
        </div>
    </div>
    <script>
        const loginForm = document.getElementById('loginForm');
        const loginSubmitBtn = document.getElementById('loginSubmitBtn');
        const loginSubmitText = document.getElementById('loginSubmitText');
        const loginSubmitSpinner = document.getElementById('loginSubmitSpinner');

        if (loginForm && loginSubmitBtn && loginSubmitText && loginSubmitSpinner) {
            loginForm.addEventListener('submit', () => {
                loginSubmitBtn.disabled = true;
                loginSubmitBtn.classList.add('opacity-60', 'cursor-not-allowed');
                loginSubmitText.textContent = 'Signing In...';
                loginSubmitSpinner.classList.remove('hidden');
            });
        }
    </script>
</body>
</html>
