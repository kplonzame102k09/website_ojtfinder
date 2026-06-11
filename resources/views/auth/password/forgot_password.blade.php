<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Access | ojtFinder</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen font-sans relative overflow-hidden">
    <div class="absolute inset-0 backdrop-blur-sm z-0"></div>

    <!--========================== Loader ==========================-->
    <div id="loader" class="hidden absolute inset-0 bg-slate-900/70 z-50 flex items-center justify-center">
        <div class="w-16 h-16 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div class="relative mx-auto max-w-md w-full bg-slate-800 rounded-xl shadow-2xl overflow-hidden animate-fadeIn">
        <div class="px-8 py-6 text-center bg-slate-900 border-b border-slate-700">
            <h2 class="font-extrabold text-2xl text-white uppercase tracking-tighter">
                Recover <span class="text-indigo-500">Access</span>
            </h2>
            <p class="text-slate-400 text-xs mt-2 uppercase tracking-widest">Forgot Password</p>
        </div>

        <div class="px-8 py-10 space-y-6">
            <p class="text-slate-300 text-sm leading-relaxed text-center">
                Enter your registered email address and we will send you a secure link to re-encrypt your access.
            </p>

            @if (session('status'))
                <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-widest">
                    {{ session('status') }}
                </div>
            @endif

            <form id="resetForm" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="IDENTITY EMAIL" 
                            class="w-full px-4 py-3 rounded-lg bg-slate-900/50 border border-slate-700 text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" required autofocus>
                        @error('email')
                            <p class="text-red-500 text-[10px] mt-1 font-black uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-3 rounded-lg transition transform hover:scale-[1.02] shadow-lg text-xs tracking-widest uppercase">
                        Request Reset Link
                    </button>
                </div>
            </form>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-slate-500 hover:text-indigo-400 text-xs font-bold uppercase tracking-widest transition">
                    <- Back
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out forwards; }
    </style>

    <script>
        const form = document.getElementById('resetForm');
        const loader = document.getElementById('loader');

        form.addEventListener('submit', function() {
            loader.classList.remove('hidden');
        });
    </script>
</body>
</html>