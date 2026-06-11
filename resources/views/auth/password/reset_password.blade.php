<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Credentials | ojtFinder</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen font-sans">
    <div class="relative mx-auto max-w-md w-full bg-slate-800 rounded-xl shadow-2xl overflow-hidden">
        <div class="px-8 py-6 text-center bg-slate-900 border-b border-slate-700">
            <h2 class="font-extrabold text-2xl text-white uppercase tracking-tighter">
                Reset <span class="text-indigo-500">Protocol</span>
            </h2>
        </div>

        <div class="px-8 py-10">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="space-y-5">
                    <div>
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 block">Account Email</label>
                        <input type="email" name="email" value="{{ $email ?? old('email') }}" 
                            class="w-full px-4 py-3 rounded-lg bg-slate-900/50 border border-slate-700 text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500" required readonly>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 block">New Password</label>
                        <input type="password" name="password" placeholder="••••••••" 
                            class="w-full px-4 py-3 rounded-lg bg-slate-900/50 border border-slate-700 text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500" required autofocus>
                        @error('password')
                            <p class="text-red-500 text-[10px] mt-1 font-black uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 block">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" 
                            class="w-full px-4 py-3 rounded-lg bg-slate-900/50 border border-slate-700 text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500" required>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-4 rounded-lg transition shadow-xl text-xs tracking-widest uppercase">
                        Update Credentials
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>