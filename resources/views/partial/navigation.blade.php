<style>
    #searchResults {
        width: calc(100vw - 2rem);
    }
    @media (min-width: 768px) {
        #searchResults {
            width: 100%;
        }
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 10px;
    }
</style>

<header class="fixed top-0 left-0 right-0 h-16 bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/5 z-50 shadow-2xl">
    <div class="h-full flex items-center justify-between px-4 sm:px-6">
        <h2 class="text-white text-2xl sm:text-3xl font-black tracking-tighter">
        <span class="text-blue-500">ojt</span>Finder<img src="/of_logo.png" class="w-8 h-8 inline animate-pulse">
        </h2>

    <div class="flex-1 max-w-sm md:max-w-md ml-4 md:ml-6 relative">
        <div class="relative group w-full">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg class="h-4 w-4 md:h-5 md:w-5 text-slate-500 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.75 3.75a7.5 7.5 0 0012.9 12.9z"/>
                </svg>
            </div>

            <input id="userSearchInput" type="text" placeholder="Search..."
                class="bg-white/5 border border-white/10 text-slate-200 text-sm rounded-xl pl-9 pr-3 py-2 w-full
                    focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:bg-white/10
                    transition-all duration-300 placeholder:text-slate-600">

            <div id="searchResults"
                class="absolute left-0 right-0 mt-2 bg-[#1e293b] border border-white/10 rounded-xl shadow-2xl
                    hidden z-[60] overflow-hidden backdrop-blur-xl max-h-[70vh] overflow-y-auto custom-scrollbar">
            </div>
        </div>
    </div>

        <button id="hamburgerBtn" class="cursor-pointer md:hidden bg-white/5 text-slate-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-xl p-2 transition border border-white/10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="hidden md:flex items-center gap-2">
            @auth
                @if(auth()->id() === 1)
                    <abbr title="Admin Panel">
                        <a href="{{ route('admin.index') }}"
                        class="load-link inline-flex items-center p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 2l9 4v6c0 5-3.8 9.7-9 12-5.2-2.3-9-7-9-12V6l9-4z"/>
                            </svg>
                        </a>
                    </abbr>
                @endif
            @endauth

            <abbr title="Home">
                <a href="{{ route('home') }}" class="load-link relative inline-flex items-center p-2 text-slate-400 hover:text-blue-400 hover:bg-white/5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0H7v6h6v6h6v-6h-6V5z"/>
                </svg>
                </a>
            </abbr>

            <abbr title="Messages">
                <a href="{{ route('messages.index') }}"
                class="load-link relative flex items-center justify-center w-10 h-10 rounded-xl text-slate-400 hover:text-blue-400 hover:bg-white/5 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4.39-1.02L3 20l1.38-3.45A7.4 7.4 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>

                @auth
                    @php
                        $msgCount = Auth::user()->receivedMessages()->whereNull('read_at')->count();
                    @endphp

                    <span id="message-badge"
                            class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-black min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full border-2 border-[#0f172a] animate-pulse {{ $msgCount > 0 ? '' : 'hidden' }}">
                        {{ $msgCount > 9 ? '9+' : $msgCount }}
                    </span>
                @endauth
                </a>
            </abbr>

            <abbr title="Newsfeed">
                <a href="{{ route('newsfeed') }}" class="load-link relative inline-flex items-center p-2 text-slate-400 hover:text-blue-400 hover:bg-white/5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
                </svg>
                </a>
            </abbr>

            <abbr title="Notifications">
                <a href="{{ route('notifications.index') }}" class="load-link relative inline-flex items-center p-2 text-slate-400 hover:text-blue-400 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @auth
                        @php
                            $count = Auth::user()->unreadNotifications->count();
                        @endphp

                        <span id="notification-badge"
                                class="notif-badge-count absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-black min-w-[18px] h-[18px] flex items-center justify-center rounded-full animate-pulse border-2 border-[#0f172a] {{ $count > 0 ? '' : 'hidden' }}">
                            {{ $count > 9 ? '9+' : $count }}
                        </span>
                    @endauth
                </a>
            </abbr>

            <abbr title="Account Settings">
                <a href="{{ route('settings.edit') }}" class="load-link text-slate-400 hover:text-blue-400 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
            </abbr>

            <div class="flex items-center gap-3 ml-2 border-l border-white/10 pl-4">

                <abbr title="Profile">
                    <a href="{{ route('profile') }}" class="load-link">
                        <img src="{{ auth()->user()->profile_picture_url }}"
                                            alt="{{ auth()->user()->name }}"
                                            class="h-9 w-9 rounded object-cover border border-white/10 shadow-sm">
                    </a>
                </abbr>

                <abbr title="Logout">
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                        <button class="load-link text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-red-400 transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
                </abbr>
            </div>
        </div>
    </div>

    <div id="global-loader" style="display: none;"
        class="fixed inset-0 w-screen h-screen z-[9999] flex flex-col items-center justify-center bg-[#0f172a]/80 backdrop-blur-md">

        <div class="flex flex-col items-center gap-4">
            <div class="h-14 w-14 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>

            <span class="text-blue-500 font-black tracking-[0.2em] text-sm uppercase animate-pulse">
                Loading...
            </span>
        </div>
    </div>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px);} to { opacity: 1; transform: translateY(0);} }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
    </style>
</header>

    <div id="mobileMenu" class="load-link hidden fixed top-20 right-8 bg-[#1e293b]/95 backdrop-blur-2xl text-white z-40 shadow-2xl rounded border border-white/10 p-2">
        <nav class="flex flex-col gap-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 p-2 hover:bg-white/5 rounded-2xl transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0H7v6h6v6h6v-6h-6V5z"/>
                </svg>
            </a>

            <a href="{{ route('messages.index') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-2xl transition">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4.39-1.02L3 20l1.38-3.45A7.4 7.4 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    @auth
                        @php $mCount = Auth::user()->receivedMessages()->whereNull('read_at')->count(); @endphp
                        @if($mCount > 0)
                            <span class="message-badge-count absolute -top-1 -right-1 bg-blue-600 text-white text-[8px] font-black min-w-[15px] h-[15px] flex items-center justify-center rounded-full border border-[#0f172a] animate-pulse">
                                {{ $mCount > 9 ? '9+' : $mCount }}
                            </span>
                        @endif
                    @endauth
                </div>
            </a>

            <a href="{{ route('newsfeed') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-2xl transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
                </svg>
            </a>

            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-2xl transition group">
                <div class="relative">
                    <svg class="h-6 w-6 text-slate-300 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @php $unreadNotifs = Auth::user()->unreadNotifications->count(); @endphp
                    @if($unreadNotifs > 0)
                        <span class="notif-badge-count absolute -top-2 -right-2 bg-red-600 text-white text-[8px] font-black min-w-[15px] h-[15px] flex items-center justify-center rounded-full animate-pulse border-2 border-[#0f172a] shadow-lg">
                            {{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}
                        </span>
                    @endif
                </div>
            </a>

            <a href="{{ route('settings.edit') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-2xl transition">
                <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                </svg>
            </a>

            <a href="{{ route('profile') }}" class="flex items-center gap-3 p-3 hover:bg-white/5 rounded-2xl transition">
                <img src="{{ auth()->user()->profile_picture_url }}"
                                            alt="{{ auth()->user()->name }}"
                                            class="h-9 w-9 rounded object-cover border border-white/10 shadow-sm">
                    <span class="text-sm font-bold text-slate-300 md:hidden"></span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="group flex items-center justify-center w-full p-3 text-slate-500 hover:text-red-400 transition-colors" title="Logout">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </button>
            </form>
        </nav>
        <div id="global-loader" style="display: none;"
            class="fixed inset-0 w-screen h-screen z-[9999] flex flex-col items-center justify-center bg-[#0f172a]/80 backdrop-blur-md">

            <div class="flex flex-col items-center gap-4">
                <div class="h-14 w-14 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>

                <span class="text-blue-500 font-black tracking-[0.2em] text-sm uppercase animate-pulse">
                    Loading...
                </span>
            </div>
        </div>
        <style>
            @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px);} to { opacity: 1; transform: translateY(0);} }
            .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
        </style>
    </div>

<script>
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const searchInput = document.getElementById('userSearchInput');
    const resultsDiv = document.getElementById('searchResults');
    const globalLoader = document.getElementById('global-loader');
    const userId = "{{ auth()->id() }}";
    let debounceTimer;

    hamburgerBtn?.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        mobileMenu.classList.toggle('animate-fadeIn');
    });

    document.querySelectorAll('.load-link').forEach(link => {
        link.addEventListener('click', () => {
            if (globalLoader) globalLoader.style.display = 'flex';
        });
    });

    searchInput?.addEventListener('input', function(e) {
        const query = e.target.value;
        clearTimeout(debounceTimer);

        if (query.length < 2) {
            resultsDiv.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ url('users/search') }}?query=${query}`)
                .then(res => res.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(user => {
                            const avatarUrl = user.profile_picture
                            const companyBadge = user.company_name
                                ? `<span class="bg-blue-500/10 text-blue-400 text-[9px] px-1.5 py-0.5 rounded border border-blue-500/20 font-bold uppercase ml-2 italic leading-none inline-flex items-center">${user.company_name}</span>`
                                : '';

                            resultsDiv.innerHTML += `
                                <a href="/profile/${user.slug || user.id}" class="load-link flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition-colors border-b border-white/5 last:border-0 group">
                                    <div class="h-10 w-10 shrink-0 relative">
                                        <img src="${avatarUrl}" class="h-full w-full rounded-full object-cover border border-white/10 group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                    <div class="flex flex-col text-left overflow-hidden">
                                        <div class="flex items-center flex-wrap gap-y-1">
                                            <span class="text-slate-200 text-sm font-semibold truncate">${user.name}</span>
                                            ${companyBadge}
                                        </div>
                                        <span class="text-slate-500 text-[11px] truncate group-hover:text-slate-300">${user.email}</span>
                                    </div>
                                </a>`;
                        });
                        resultsDiv.classList.remove('hidden');
                    } else {
                        resultsDiv.innerHTML = '<div class="p-4 text-center text-xs text-slate-500 italic">No matches found</div>';
                        resultsDiv.classList.remove('hidden');
                    }
                });
        }, 300);
    });
    function updateBadges(data) {
        console.log("Badge Data:", data);
        const notifBadges = document.querySelectorAll('#notification-badge, .notif-badge-count');

        notifBadges.forEach(badge => {
            if (data.unreadNotifications > 0) {
                badge.innerText = data.unreadNotifications > 9 ? '9+' : data.unreadNotifications;
                badge.style.display = 'flex';
                badge.classList.remove('hidden');
            } else {
                badge.style.display = 'none';
            }
        });
        const msgBadges = document.querySelectorAll('#message-badge, .message-badge-count');
        msgBadges.forEach(badge => {
            if (data.unreadMessages > 0) {
                badge.innerText = data.unreadMessages > 9 ? '9+' : data.unreadMessages;
                badge.style.display = 'flex';
                badge.classList.remove('hidden');
            } else {
                badge.style.display = 'none';
            }
        });
    }
        if (userId && window.Echo) {
            window.Echo.private(`App.Models.User.${userId}`)
                .notification((notification) => {
                    console.log('Real-time event:', notification);
                    fetch("{{ route('notifications.counts') }}")
                        .then(res => res.json())
                        .then(data => updateBadges(data));
                });
            window.Echo.private(`users.${userId}`)
                .listen('MessageSent', (e) => {
                    fetch("{{ route('notifications.counts') }}")
                    .then(res => res.json())
                    .then(data => updateBadges(data));
            });
        }
        if (userId) {
            setInterval(() => {
                fetch("{{ route('notifications.counts') }}")
                    .then(res => res.json())
                    .then(data => updateBadges(data));
            }, 5000);
        }
	document.addEventListener('click', (e) => {
            if (searchInput && !searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
                resultsDiv.classList.add('hidden');
            }
        });
</script>
