<!DOCTYPE html>
<html lang="en">
<head>
  @include('partials.meta')
  <title>ojtFinder | Sign Up</title>

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

    <div class="relative z-10 min-h-screen flex">
        <!-- Left Side - Design/Branding -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-center items-center px-12 py-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent"></div>
            <div class="relative z-10 text-center max-w-lg">
                <a href="/" class="inline-flex items-center gap-2 mb-8">
                    <span class="text-4xl font-bold text-white">ojt<span class="text-blue-500">Finder</span></span>
                </a>
                <h1 class="text-5xl font-extrabold text-white mb-6 leading-tight">
                    Start your journey<br>
                    <span class="text-blue-500">today</span>
                </h1>
                <p class="text-xl text-slate-300 mb-8 leading-relaxed">
                    Join thousands of students and companies already using ojtFinder to connect and build their future.
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
                        <span class="text-slate-300">Apply to unlimited opportunities</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-slate-300">Track applications in real-time</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-slate-300">Connect with top companies</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-4 lg:p-16">
            <div class="w-full max-w-xl lg:max-w-2xl">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-6">
                    <a href="/" class="inline-flex items-center gap-2">
                        <span class="text-2xl lg:text-3xl font-bold text-white">ojt<span class="text-blue-500">Finder</span></span>
                    </a>
                </div>

                <div class="w-full animate-fadeIn">
                    <div class="mb-6 lg:mb-10">
                        <h1 class="text-2xl lg:text-3xl font-bold text-white mb-2 lg:mb-3">Create your account</h1>
                        <p class="text-slate-400 text-base lg:text-lg">Start your OJT journey today</p>
                    </div>

                    <form method="POST" action="{{ route('signup') }}" class="space-y-4 lg:space-y-5">
                        @csrf

                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="text" name="first_name" placeholder="First Name"
                                   class="flex-1 px-4 py-3 lg:px-5 lg:py-4 rounded-lg bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition" required>
                            <input type="text" name="surname" placeholder="Last Name"
                                   class="flex-1 px-4 py-3 lg:px-5 lg:py-4 rounded-lg bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition" required>
                        </div>

                        <input type="email" name="email" placeholder="Email"
                               class="w-full px-4 py-3 lg:px-5 lg:py-4 rounded-lg bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition" required>

                        <input type="tel" name="contact_number" placeholder="Contact Number"
                               class="w-full px-4 py-3 lg:px-5 lg:py-4 rounded-lg bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition" required>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <input type="text" name="barangay" placeholder="Barangay"
                                   class="px-4 py-3 lg:px-5 lg:py-4 rounded-lg bg-white/5 border border-white/10 text-white placeholder-slate-500 outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                            <input type="text" name="city" placeholder="City"
                                   class="px-4 py-3 lg:px-5 lg:py-4 rounded-lg bg-white/5 border border-white/10 text-white placeholder-slate-500 outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                            <input type="text" name="province" placeholder="Province"
                                   class="px-4 py-3 lg:px-5 lg:py-4 rounded-lg bg-white/5 border border-white/10 text-white placeholder-slate-500 outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                            <input type="text" name="region" placeholder="Postal Code"
                                   class="px-4 py-3 lg:px-5 lg:py-4 rounded-lg bg-white/5 border border-white/10 text-white placeholder-slate-500 outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition">
                        </div>

                        <div class="relative">
                            <input id="password" type="password" name="password" placeholder="Password"
                                   class="w-full px-4 py-3 lg:px-5 lg:py-4 rounded-lg bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition" required>
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-400 transition focus:outline-none px-2 cursor-pointer">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12.083a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>

                        <div>
                            <label class="text-slate-300 text-sm mb-3 block">Date of Birth</label>
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                <select name="day" class="flex-1 px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition">
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option>{{ $i }}</option>
                                    @endfor
                                </select>
                                <select name="month" class="flex-1 px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition">
                                    @php
                                        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                                    @endphp
                                    @foreach ($months as $month)
                                        <option>{{ $month }}</option>
                                    @endforeach
                                </select>
                                <select name="year" class="flex-1 px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition">
                                    @for ($y = 1990; $y <= date('Y'); $y++)
                                        <option>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-slate-300 text-sm mb-3 block">Gender</label>
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                <label class="flex-1 flex justify-between items-center px-4 py-3 border border-white/10 rounded-lg hover:bg-white/5 transition cursor-pointer">
                                    <span class="text-slate-300">Female</span>
                                    <input type="radio" name="gender" value="female" required class="accent-blue-500">
                                </label>
                                <label class="flex-1 flex justify-between items-center px-4 py-3 border border-white/10 rounded-lg hover:bg-white/5 transition cursor-pointer">
                                    <span class="text-slate-300">Male</span>
                                    <input type="radio" name="gender" value="male" class="accent-blue-500">
                                </label>
                                <label class="flex-1 flex justify-between items-center px-4 py-3 border border-white/10 rounded-lg hover:bg-white/5 transition cursor-pointer">
                                    <span class="text-slate-300">Other</span>
                                    <input type="radio" name="gender" value="other" class="accent-blue-500">
                                </label>
                            </div>
                        </div>

                        <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-6 lg:mt-8 border-t border-white/5 pt-4 lg:pt-6">
                            <div class="leading-relaxed">
                                By signing up, you agree to our <br class="sm:hidden">
                                <a href="{{ route('legal.show', 'terms') }}" class="text-blue-400 hover:text-blue-300 transition-colors underline decoration-blue-500/30 underline-offset-4">Terms</a>,
                                <a href="{{ route('legal.show', 'privacy') }}" class="text-blue-400 hover:text-blue-300 transition-colors underline decoration-blue-500/30 underline-offset-4">Privacy Policy</a>,
                                and <a href="{{ route('legal.show', 'cookies') }}" class="text-blue-400 hover:text-blue-300 transition-colors underline decoration-blue-500/30 underline-offset-4">Cookies Policy</a>.
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 lg:py-4 rounded-lg transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40">
                            Create Account
                        </button>
                    </form>

                    <div class="text-center mt-6 lg:mt-8 text-sm text-slate-400">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-blue-400 hover:text-blue-300 transition">Log In</a>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-6 lg:mt-8">
                    <a href="/" class="text-slate-500 hover:text-slate-300 text-sm transition">← Back to home</a>
                </div>
            </div>
        </div>
    </div>

<script> 
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