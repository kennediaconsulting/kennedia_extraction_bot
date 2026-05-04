<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Kennedia Consulting</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center px-4" style="background: #FFFFFF; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div style="width: 420px; max-width: 100%;">
        <div class="bg-white rounded-2xl shadow-[0_20px_44px_rgba(31,111,74,0.12)] overflow-hidden border" style="border-color: rgba(31,111,74,0.20); box-shadow: 0 20px 44px rgba(31,111,74,0.12), inset 0 0 0 1px rgba(255,255,255,0.85), inset 0 0 0 2px rgba(31,111,74,0.08);">

            <!-- Forest Green top accent bar -->
            <div class="h-2 bg-[#1F6F4A]"></div>

            <!-- Logo section — tight, prominent -->
            <div class="relative overflow-hidden flex flex-col items-center pt-6 pb-3 px-6">
                <img src="{{ asset('2.png') }}" alt="" aria-hidden="true" class="pointer-events-none absolute left-1/2 top-1/2 h-auto w-44 -translate-x-1/2 -translate-y-1/2 opacity-[0.05]" />
                <img src="{{ asset('1.png') }}" alt="Kennedia Consulting" class="relative z-10 w-64 h-auto" style="max-width: 260px;" />
                <p class="mt-2 text-[11px] text-[#9E9E9E] uppercase tracking-[0.2em]">Convocation Extractor Console</p>
            </div>

            <!-- Divider -->
            <div class="mx-6 border-t" style="border-color: rgba(158,158,158,0.35);"></div>

            <!-- Login Form -->
            <div class="px-6 pt-4 pb-5">

                <p class="text-center text-[11px] uppercase tracking-[0.16em] text-[#3A3A3A] mb-3">Secure Sign In</p>

                @if ($errors->any())
                    <div class="px-4 py-3 rounded-lg mb-4 border" style="background: rgba(158,158,158,0.12); border-color: rgba(58,58,58,0.28); color: #3A3A3A;">
                        <ul class="text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="px-4 py-3 rounded-lg mb-4 text-sm border" style="background: rgba(155,197,61,0.18); border-color: rgba(31,111,74,0.30); color: #1F6F4A;">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-[13px] font-semibold text-[#3A3A3A] mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            class="w-full px-4 py-2.5 border bg-white rounded-xl focus:ring-2 focus:ring-[#1F6F4A] focus:border-[#1F6F4A] outline-none transition"
                            style="border-color: rgba(158,158,158,0.55); color: #3A3A3A;"
                            placeholder="admin@kennediaconsulting.net"
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-[13px] font-semibold text-[#3A3A3A] mb-2">Password</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="w-full px-4 py-2.5 pr-12 border bg-white rounded-xl focus:ring-2 focus:ring-[#1F6F4A] focus:border-[#1F6F4A] outline-none transition"
                                style="border-color: rgba(158,158,158,0.55); color: #3A3A3A;"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                id="togglePasswordBtn"
                                aria-label="Show password"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-[#9E9E9E] hover:text-[#1F6F4A]"
                            >
                                <svg id="eyeOpenIcon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eyeClosedIcon" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C5 20 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94"></path>
                                    <path d="M9.9 4.24A10.87 10.87 0 0 1 12 4c7 0 11 8 11 8a21.79 21.79 0 0 1-3.18 4.78"></path>
                                    <path d="M1 1l22 22"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button 
                        id="loginSubmitBtn"
                        type="submit"
                        class="w-full bg-[#1F6F4A] hover:bg-[#9BC53D] text-white font-semibold tracking-wide px-4 py-2.5 rounded-xl transition duration-200 shadow-md hover:shadow-lg inline-flex items-center justify-center gap-2"
                    >
                        <span id="loginSubmitText">Sign In</span>
                        <svg id="loginSubmitSpinner" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"></path>
                        </svg>
                    </button>
                </form>

                <div class="mt-5 text-center">
                    <p class="text-xs text-[#9E9E9E]">
                        Default credentials: admin@kennediaconsulting.net / admin@1234
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-[#3A3A3A]">© {{ date('Y') }} Kennedia Consulting</p>
        </div>
    </div>
    <script>
        const loginForm = document.getElementById('loginForm');
        const loginSubmitBtn = document.getElementById('loginSubmitBtn');
        const loginSubmitText = document.getElementById('loginSubmitText');
        const loginSubmitSpinner = document.getElementById('loginSubmitSpinner');
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const eyeOpenIcon = document.getElementById('eyeOpenIcon');
        const eyeClosedIcon = document.getElementById('eyeClosedIcon');

        if (passwordInput && togglePasswordBtn && eyeOpenIcon && eyeClosedIcon) {
            togglePasswordBtn.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                togglePasswordBtn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                eyeOpenIcon.classList.toggle('hidden', isHidden);
                eyeClosedIcon.classList.toggle('hidden', !isHidden);
            });
        }

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
