<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | ojtFinder</title>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZEMJ5KJY75"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-ZEMJ5KJY75');
    </script>
    @vite('resources/css/app.css')
    <style>
        /* Exact background from home.blade.php */
        .bg-main { 
            background: radial-gradient(circle at top right, #070707, #0f172a); 
            background-attachment: fixed; 
        }

        /* Updated Glass Panel to match Modal style */
        .glass-panel { 
            background: rgba(255, 255, 255, 0.03); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(12px); 
        }

        /* Subtle Map Grid Overlay */
        .map-overlay { 
            position: fixed; 
            inset: 0; 
            background-image: radial-gradient(rgba(59, 130, 246, 0.08) 1px, transparent 1px); 
            background-size: 40px 40px; 
            pointer-events: none; 
            z-index: 0; 
        }
    </style>
</head>
<body class="bg-main text-slate-200 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="map-overlay"></div>

    <div class="relative z-10 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black tracking-tighter text-white">
                <span class="text-blue-500">ojt</span>Finder
            </h1>
            <div class="h-1 w-12 bg-blue-600 mx-auto mt-2 rounded-full"></div>
        </div>

        <div class="glass-panel rounded p-10 shadow-2xl text-center border border-white/10">
            <div class="w-20 h-20 bg-blue-600/10 border border-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-8 text-3xl shadow-inner">
                ✉️
            </div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Verification</h2>            
            <p class="text-sm text-slate-400 mt-4 leading-relaxed px-2">
                We are going to send a verification link to your email address. 
                Please click the link to confirm your account and access the newsfeed.
            </p>
            @if (session('status') === 'verification-link-sent')
                <div class="mt-6 p-3 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-bold uppercase tracking-wider animate-pulse">
                    A new link has been sent to your email!
                </div>
            @endif

            <div class="mt-10 space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button id="verifyBtn" type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-4 rounded-xl text-xs uppercase tracking-[0.2em] transition-all active:scale-95 shadow-lg shadow-blue-600/25">
                        Send Verification link
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:text-white transition py-2">
                        Wait, I used the wrong email (Logout)
                    </button>
                </form>
            </div>
        </div>
                                        
        <p class="mt-8 text-center text-[10px] text-slate-600 font-bold uppercase tracking-widest">
            &copy; 2026 OJT FINDER INTERN NETWORK
        </p>
    </div>
    <div id="loadingModal" class="fixed inset-0 z-[200] hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-md"></div>
        <div class="relative z-10 glass-panel rounded-xl p-8 w-full max-w-sm text-center shadow-2xl border border-white/10">
            <div class="w-14 h-14 border-4 border-blue-500/30 border-t-blue-500 rounded-full animate-spin mx-auto mb-6"></div>
            <h3 class="text-white font-bold text-lg tracking-tight">
                Sending verification email
            </h3>
            <p class="text-sm text-slate-400 mt-2">
                Please wait a moment…
            </p>
        </div>
    </div>  
    
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('verifyBtn');
        const modal = document.getElementById('loadingModal');
        if (!btn || !modal) return;
        btn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });
</script>
</body>
</html>