<!DOCTYPE html>
<html lang="en">
<head>
  @include('partials.meta')
  <title>ojtFinder | Login</title>

  @include('partials.gtag')

  @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-main {
            background: radial-gradient(circle at top right, #070707, #0f172a);
            background-attachment: fixed;
        }

        .map-overlay {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }

        @keyframes scaleUp {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .animate-scaleUp { animation: scaleUp 0.4s ease-out forwards; }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .marker {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #3b82f6;
            border-radius: 50%;
            filter: blur(1px);
        }

        .marker::after {
            content: '';
            position: absolute;
            inset: -8px;
            border: 1px solid #3b82f6;
            border-radius: 50%;
            animation: markerPulse 3s infinite;
            opacity: 0;
        }

        @keyframes markerPulse {
            0% { transform: scale(0.5); opacity: 0.8; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        .page-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top right, #070707, #0f172a);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(59, 130, 246, 0.2);
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
	</style>
</head>
<body class="bg-main text-slate-200 antialiased relative min-h-screen">

    <!-- Page Loader -->
    <div id="pageLoader" class="page-loader">
        <div class="flex flex-col items-center gap-4">
            <div class="spinner"></div>
            <p class="text-blue-400 font-black tracking-widest text-sm uppercase animate-pulse">Loading...</p>
        </div>
    </div>

    <div class="map-overlay">
        <div class="marker" style="top: 20%; left: 15%;"></div>
        <div class="marker" style="top: 60%; left: 80%; animation-delay: 1s;"></div>
        <div class="marker" style="top: 40%; left: 50%; animation-delay: 2s;"></div>
    </div>

    <div id="login-loader" style="display: none;" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-slate-950/80 backdrop-blur-md">
        <div class="flex flex-col items-center gap-4">
            <div class="h-16 w-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-blue-400 font-black tracking-widest text-sm uppercase animate-pulse">Authenticating...</p>
        </div>
    </div>

    <div class="relative z-10 min-h-screen flex">
        <!-- Left Side - Design/Branding -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-center items-center px-12 py-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent"></div>
            <div class="relative z-10 text-center max-w-lg">
                <a href="/" class="inline-flex items-center gap-2 mb-8">
                    <span class="text-4xl font-bold text-white">ojt<span class="text-blue-500">Finder</span></span>
                </a>
                <h1 class="text-5xl font-extrabold text-white mb-6 leading-tight">
                    Welcome back<br>
                    <span class="text-blue-500">to ojtFinder</span>
                </h1>
                <p class="text-xl text-slate-300 mb-8 leading-relaxed">
                    Continue your journey to finding the perfect OJT opportunity. Access your dashboard and connect with top companies.
                </p>
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="glass-card p-6 rounded-2xl text-center">
                        <p class="text-3xl font-bold text-white mb-1">1.2k+</p>
                        <p class="text-sm text-blue-400 uppercase font-bold tracking-widest">Students</p>
                    </div>
                    <div class="glass-card p-6 rounded-2xl text-center">
                        <p class="text-3xl font-bold text-white mb-1">450+</p>
                        <p class="text-sm text-blue-400 uppercase font-bold tracking-widest">Companies</p>
                    </div>
                </div>
                <div class="space-y-4 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-slate-300">Access your applications</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-slate-300">Connect with recruiters</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-slate-300">Track your progress</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-2xl">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <a href="/" class="inline-flex items-center gap-2">
                        <span class="text-3xl font-bold text-white">ojt<span class="text-blue-500">Finder</span></span>
                    </a>
                </div>

                <div class="w-full animate-fadeIn">
                    <div class="mb-10">
                        <h1 class="text-3xl font-bold text-white mb-3">Welcome back</h1>
                        <p class="text-slate-400 text-lg">Sign in to continue to your account</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg text-sm font-medium mb-6">
                            <strong>Whoops!</strong> Something went wrong.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-3">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com"
                                    class="w-full px-5 py-4 rounded-lg bg-white/5 border {{ $errors->has('email') ? 'border-red-500' : 'border-white/10' }} text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition" required autofocus>

                                @error('email')
                                    <p class="text-red-400 text-xs mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-3">Password</label>
                                <div class="relative">
                                    <input id="password" type="password" name="password" placeholder="••••••••"
                                        class="w-full px-5 py-4 rounded-lg bg-white/5 border {{ $errors->has('password') ? 'border-red-500' : 'border-white/10' }} text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition" required>

                                    @error('password')
                                        <p class="text-red-400 text-xs mt-1 font-semibold">{{ $message }}</p>
                                    @enderror

                                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-400 transition focus:outline-none px-2">
                                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12.083a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}
                                        class="rounded border-white/20 bg-white/5 text-blue-500 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer">
                                    <label for="remember" class="text-slate-300 text-sm cursor-pointer">Remember me</label>
                                </div>

                                <a href="{{ route('password.request') }}" class="text-sm text-blue-400 hover:text-blue-300 transition">Forgot password?</a>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-lg transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40">
                                Sign In
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-slate-400 text-sm">
                            Don't have an account?
                            <a href="{{ route('signup') }}" class="font-semibold text-blue-400 hover:text-blue-300 transition">Sign up</a>
                        </p>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-8">
                    <a href="/" class="text-slate-500 hover:text-slate-300 text-sm transition">← Back to home</a>
                </div>
            </div>
        </div>
    </div>

    @if (session('throttleSeconds'))
        <div id="throttleModal" class="fixed inset-0 flex items-center justify-center bg-slate-950/90 backdrop-blur-sm z-[110]">
            <div class="bg-slate-900 border border-red-500/30 rounded-xl shadow-2xl max-w-sm w-full p-8 text-center animate-scaleUp">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-500/10 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2zm-10 0V7a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3h12a3 3 0 003-3v-8a3 3 0 00-3-3H9z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Security Lockout</h3>
                <p class="text-slate-300 mb-6">Too many failed attempts. For your security, login is disabled for:</p>

                <div class="text-5xl font-black text-blue-500 mb-6 tracking-widest" id="countdownTimer">
                    {{ session('throttleSeconds') }}s
                </div>

                <p class="text-xs text-slate-500 uppercase tracking-tighter">Please wait until the timer hits zero</p>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div class="bg-slate-900 rounded-xl shadow-xl max-w-sm w-full p-6 text-center animate-scaleUp">
                <h3 class="text-2xl font-bold text-white mb-2">Success 🎉</h3>
                <p class="text-slate-300 mb-4">{{ session('success') }}</p>
                <button onclick="closeModal()" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg w-full transition-all shadow-lg shadow-blue-500/25">
                    OK
                </button>
            </div>
        </div>
    @endif

<script>
    function closeModal(){
        document.getElementById('successModal').style.display = 'none';
    }

    const loginForm = document.querySelector('form');
    const loader = document.getElementById('login-loader');

    loginForm.addEventListener('submit', function() {
        loader.style.display = 'flex';
        const btn = this.querySelector('button[type="submit"]');
        if(btn) btn.disabled = true;
    });

    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />`;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12.083a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />`;
        }
    }
    @if (session('throttleSeconds'))
        (function() {
            let timeLeft = {{ session('throttleSeconds') }};
            const timerDisplay = document.getElementById('countdownTimer');
            const countdown = setInterval(() => {
                timeLeft--;
                timerDisplay.textContent = timeLeft + "s";

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    document.getElementById('throttleModal').style.display = 'none';
                    window.location.reload();
                }
            }, 1000);
        })();
    @endif

    window.addEventListener('load', function() {
        const loader = document.getElementById('pageLoader');
        if (loader) {
            setTimeout(() => {
                loader.style.opacity = '0';
                loader.style.transition = 'opacity 0.5s ease-out';
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }, 500);
        }
    });
  </script>
</body>
</html>