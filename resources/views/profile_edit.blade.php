<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | ojtFinder</title>
    @vite(['resources/css/app.css'])
    <style>
        .glass-panel { 
            background: rgba(255, 255, 255, 0.02); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.08); 
        }
        input, textarea {
            transition: all 0.2s ease-in-out;
        }
    </style>
</head>
<body class="bg-[#0b0f1a] text-slate-200 antialiased font-sans min-h-screen">

@include('partial.navigation')

<main class="max-w-2xl mx-auto py-20 px-6">
    {{-- Header Section --}}
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-black text-white tracking-tight">Edit Profile</h1>
        <p class="text-slate-500 text-sm mt-2 font-medium uppercase tracking-widest">Update your personal information</p>
    </div>

    <div class="glass-panel rounded p-8 shadow-2xl">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Full Name --}}
            <div class="space-y-1.5">
                <label class="text-[11px] font-black uppercase tracking-widest text-slate-500 ml-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full bg-white/5 border {{ $errors->has('name') ? 'border-red-500' : 'border-white/10' }} rounded px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-600/50 focus:border-blue-500 transition shadow-inner">
                @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div class="space-y-1.5">
                <label class="text-[11px] font-black uppercase tracking-widest text-slate-500 ml-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full bg-white/5 border {{ $errors->has('email') ? 'border-red-500' : 'border-white/10' }} rounded px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-600/50 focus:border-blue-500 transition shadow-inner">
                @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            {{-- Grid for Contact & Address --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-500 ml-1">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}"
                        class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-600/50 transition">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-500 ml-1">Address</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                        class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-600/50 transition">
                </div>
            </div>

            {{-- Bio --}}
            <div class="space-y-1.5">
                <label class="text-[11px] font-black uppercase tracking-widest text-slate-500 ml-1">Bio</label>
                <textarea name="bio" rows="4"
                    class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-600/50 transition resize-none leading-relaxed">{{ old('bio', $user->bio) }}</textarea>
                <p class="text-[10px] text-slate-600 text-right uppercase tracking-tighter">Keep it short and descriptive</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded transition-all shadow-lg shadow-blue-900/20 active:scale-[0.98]">
                    Save Changes
                </button>
                <a href="{{ route('profile') }}"
                   class="px-8 py-3 rounded font-bold text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 transition">
                    Cancel
                </a>
            </div>
                <a href="{{ route('settings.edit') }}" class="flex items-center gap-3 p-3 bg-red-700 hover:bg-white/5 rounded transition">
                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                    <span class="text-sm font-medium text-slate-200">Go to settings-></span>
                    </a>
        </form>
    </div>
</main>

</body>
</html>