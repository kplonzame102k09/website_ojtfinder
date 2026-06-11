<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="WAfQ8Ukar-cZVK8eQBJ2MjheJGQuAveD79Ny6ctEXtQ" />
    <meta name="description" content="Find OJT and internship opportunities near you. Browse hundreds of companies offering on-the-job training for Filipino students.">
    <meta name="keywords" content="OJT, internship, on-the-job training, Philippines, student jobs">
    <meta property="og:title" content="ojtFinder | Find OJT Internships">
    <meta property="og:description" content="Browse OJT opportunities near you">
    <meta property="og:image" content="https://ojtfinder.42web.io/public/of_logo.png">
    <meta property="og:url" content="https://ojtfinder.42web.io">
    <meta property="og:type" content="website">
    <title>ojtFinder | Home</title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZEMJ5KJY75"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-ZEMJ5KJY75');
    </script>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ 'public/of_logo.png' }}?v=1">
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
            animation: pulse 3s infinite;
            opacity: 0;
        }

        @keyframes pulse {
            0% { transform: scale(0.5); opacity: 0.8; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-main text-slate-200 antialiased relative">

    <div class="map-overlay">
        <div class="marker" style="top: 20%; left: 15%;"></div>
        <div class="marker" style="top: 60%; left: 80%; animation-delay: 1s;"></div>
        <div class="marker" style="top: 40%; left: 50%; animation-delay: 2s;"></div>
    </div>

    @include('partial.navigation')

    <header class="relative pt-32 pb-20 px-6">
        <div class="max-w-7xl mx-auto flex flex-col items-center text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-8">
                Find your path. <span class="text-blue-500">Build your Future.</span>
            </h1>
            <p class="max-w-2xl text-lg text-slate-400 leading-relaxed mb-10">
                ojtFinder is a professional-grade platform designed to bridge the gap between academic learning and industry experience. Explore verified training opportunities based on your course and location.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">

                @if(auth()->user()->role === 'guest' || !auth()->user()->role)
                    <button onclick="openModal('roleSelectionModal')" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/25">
                        Get Started
                    </button>
                @else
                    <a href="{{ auth()->user()->role === 'student' ? route('newsfeed') : route('company_dashboard') }}"
                    class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/25">
                        Go to {{ ucfirst(auth()->user()->role) }} Panel
                    </a>
                @endif

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
                    <li><a href="{{ 'home' }}" class="hover:text-blue-400">Home</a></li>
                    <li><a href="{{ ('newsfeed') }}" class="hover:text-blue-400">Newsfeed</a></li>
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

    <div id="roleSelectionModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 backdrop-blur-md z-0" onclick="closeModal('roleSelectionModal')"></div>
        <div class="w-full max-w-2xl rounded p-8 md:p-12 relative z-10 shadow-2xl border-white/10 text-center mb-10">
            <h3 class="text-3xl font-black text-white uppercase tracking-tighter mb-2">Welcome to <span class="text-blue-500">ojt</span>Finder</h3>
            <p class="text-slate-200 mb-10">Select your path to get started</p>

            <div class="grid md:grid-cols-2 gap-6">
                <button type="button" onclick="selectRole('student')" class="rounded-lg group p-8 glass-card border-white/5 hover:border-blue-500/50 transition-all text-left">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform pointer-events-none">🎓</div>
                    <h4 class="text-xl font-bold text-white mb-2 pointer-events-none">Student</h4>
                    <p class="text-sm text-slate-200 pointer-events-none">Looking for internship opportunities to build my career.</p>
                </button>

                <button type="button" onclick="selectRole('company')" class="rounded-lg bg-slate/100 group p-8 glass-card border-white/5 hover:border-blue-500/50 transition-all text-left">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform pointer-events-none">🏢</div>
                    <h4 class="text-xl font-bold text-blue-500 mb-2 pointer-events-none">Company</h4>
                    <p class="text-sm text-slate-200 pointer-events-none">Hiring talented interns and managing training programs.</p>
                </button>
            </div>

            <button type="button" onclick="closeModal('roleSelectionModal')" class="mt-8 text-slate-500 hover:text-white font-bold transition-all text-xs uppercase tracking-widest relative z-20">
                Go Back
            </button>
        </div>
    </div>

    <div id="alreadyRegisteredModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#070707]/90 backdrop-blur-md" onclick="closeModal('alreadyRegisteredModal')"></div>
        <div class="glass-card w-full max-w-md rounded-2xl p-10 relative shadow-2xl border-blue-500/20 text-center">
            <div class="w-20 h-20 bg-blue-600/20 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">🏢</div>
            <h3 class="text-2xl font-black text-white uppercase tracking-tighter mb-4">Account Linked</h3>
            <p class="text-slate-400 leading-relaxed mb-8">
                Your account is already associated with <span class="text-blue-400 font-bold">{{ auth()->user()->company->company_name ?? 'a company' }}</span>.
            </p>
            <div class="space-y-3">
                <a href="{{ route('company_dashboard') }}" class="block w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition-all">Go to Dashboard</a>
                <button onclick="closeModal('alreadyRegisteredModal')" class="block w-full py-4 text-slate-500 hover:text-white font-bold transition-all text-sm uppercase tracking-widest">Close</button>
            </div>
        </div>
    </div>

<script>
    function openModal(id) {
        const m = document.getElementById(id);
        if(m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
            document.body.style.overflow = 'hidden';
            }
        }

    function closeModal(id) {
        const m = document.getElementById(id);
        if (m && !m.hasAttribute('data-persistent')) {
            m.classList.add('hidden');
            m.classList.remove('flex');
            document.body.style.overflow = 'auto';
            }
        }

    document.addEventListener('keydown', (e) => {
        if (e.key === "Escape") {
        const openModals = document.querySelectorAll('[id$="Modal"]:not(.hidden)');
            openModals.forEach(m => closeModal(m.id));
            }
        });

    function selectRole(role) {
        if (role === 'student') {
            fetch("{{ route('user.updateRole') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ role: 'student' })
            })
            .then(res => res.json())
            .then(() => window.location.href = "{{ route('newsfeed') }}");
            return;
        }
        if (role === 'company') {
            window.location.href = "{{ route('company.register') }}";
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        @auth
            @if(auth()->user()->role === 'guest')
                openModal('roleSelectionModal');
            @endif
        @endauth
    });

    </script>
</body>
</html>
