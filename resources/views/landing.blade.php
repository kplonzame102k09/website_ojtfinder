<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.meta')
    <title>ojtFinder | Find OJT Internships</title>

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

        @keyframes spin {
            to { transform: rotate(360deg); }
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

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-main text-slate-200 antialiased relative">

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

    <!-- Public Navigation -->
    <nav class="relative z-10 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <span class="text-2xl font-bold text-white">ojt<span class="text-blue-500">Finder</span></span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-slate-300 hover:text-white transition">Login</a>
                <a href="{{ route('signup') }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-lg transition">Sign Up</a>
            </div>
        </div>
    </nav>

    <header class="relative pt-32 pb-20 px-6">
        <div class="max-w-7xl mx-auto flex flex-col items-center text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-8">
                Find your path. <span class="text-blue-500">Build your Future.</span>
            </h1>
            <p class="max-w-2xl text-lg text-slate-400 leading-relaxed mb-10">
                ojtFinder is a professional-grade platform designed to bridge the gap between academic learning and industry experience. Explore verified training opportunities based on your course and location.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('signup') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/25">
                    Get Started
                </a>
                <a href="#plans" class="px-8 py-4 glass-card hover:bg-white/10 text-white font-bold rounded-xl transition-all">
                    View Pricing
                </a>
            </div>
        </div>
    </header>

    <section class="py-20 bg-slate-950/50 relative border-y border-white/5">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl font-bold text-white mb-6">What is ojtFinder?</h2>
                <div class="space-y-6 text-slate-400 text-lg">
                    <p>We simplify the search for On-the-Job Training by centralizing verified openings from top-tier companies. No more cold emails or messy spreadsheets.</p>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 w-5 h-5 bg-blue-500/20 rounded flex items-center justify-center text-blue-400 text-xs font-bold">1</span>
                            <span>Filter by industry, location, or academic course.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 w-5 h-5 bg-blue-500/20 rounded flex items-center justify-center text-blue-400 text-xs font-bold">2</span>
                            <span>Upload your resume and apply with a single click.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 w-5 h-5 bg-blue-500/20 rounded flex items-center justify-center text-blue-400 text-xs font-bold">3</span>
                            <span>Track your application status in real-time.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="glass-card p-8 rounded-3xl text-center">
                    <p class="text-3xl font-bold text-white mb-1">1.2k</p>
                    <p class="text-xs text-blue-400 uppercase font-bold tracking-widest">Active Students</p>
                </div>
                <div class="glass-card p-8 rounded-3xl text-center mt-8">
                    <p class="text-3xl font-bold text-white mb-1">450+</p>
                    <p class="text-xs text-blue-400 uppercase font-bold tracking-widest">Partner Entities</p>
                </div>
            </div>
        </div>
    </section>

    <section id="plans" class="py-24 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Choose the right plan</h2>
                <p class="text-slate-400">Simple, transparent pricing for students and companies.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="glass-card p-10 rounded-[2.5rem] transition-all">
                    <h3 class="text-blue-400 font-bold uppercase tracking-widest text-sm mb-6">For Students</h3>
                    <div class="flex items-baseline gap-2 mb-6">
                        <span class="text-5xl font-bold text-white">₱0</span>
                        <span class="text-slate-500 italic">Free Forever</span>
                    </div>
                    <ul class="space-y-4 mb-10 text-slate-300">
                        <li class="flex items-center gap-3">✔ Apply to unlimited companies</li>
                        <li class="flex items-center gap-3">✔ Digital Resume Profile</li>
                        <li class="flex items-center gap-3">✔ Application Status Tracker</li>
                    </ul>
                    <a href="{{ route('signup') }}" class="block w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition-all text-center">
                        Start Free
                    </a>
                </div>

                <div class="glass-card p-10 rounded-[2.5rem] bg-white/5 border-blue-500/30">
                    <h3 class="text-blue-400 font-bold uppercase tracking-widest text-sm mb-6">For Companies</h3>
                    <div class="flex items-baseline gap-2 mb-6">
                        <span class="text-5xl font-bold text-white">₱150</span>
                        <span class="text-slate-500">/month</span>
                    </div>

                    <ul class="space-y-4 mb-10 text-slate-300">
                        <li class="flex items-center gap-3">✔ Post unlimited OJT openings</li>
                        <li class="flex items-center gap-3">✔ Direct student messaging</li>
                        <li class="flex items-center gap-3">✔ Analytics dashboard</li>
                    </ul>
                    <a href="{{ route('signup') }}" class="block w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition-all text-center">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-white/5 bg-slate-950/50 py-16 px-6 text-center md:text-left">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-1">
                <h2 class="text-2xl font-bold text-blue-500 mb-4">ojt<span class="text-white">Finder</span></h2>
                <p class="text-sm text-slate-500 leading-relaxed">Systematically connecting the next generation of professionals with real-world industry leaders.</p>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Platform</h4>
                <ul class="space-y-4 text-sm text-slate-400 font-medium">
                    <li><a href="/" class="hover:text-blue-400">Home</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-blue-400">Login</a></li>
                    <li><a href="{{ route('signup') }}" class="hover:text-blue-400">Sign Up</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Legal</h4>
                <ul class="space-y-4 text-sm text-slate-400 font-medium">
                    <li><a href="{{ route('legal.show', 'privacy') }}" class="hover:text-blue-400">Privacy Policy</a></li>
                    <li><a href="{{ route('legal.show', 'terms') }}" class="hover:text-blue-400">Terms of Service</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Contact</h4>
                <p class="text-sm text-slate-400 mb-2">support@ojtfinder.com</p>
                <p class="text-sm text-slate-400">Philippines</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto mt-16 pt-8 border-t border-white/5 text-center text-xs text-slate-600 font-bold uppercase tracking-widest">
            © {{ date('Y') }} ojtFinder. All Rights Reserved.
        </div>
    </footer>

    <script>
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