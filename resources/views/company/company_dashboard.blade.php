<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ojtFinder | Company</title>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZEMJ5KJY75"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZEMJ5KJY75');
</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" sizes="32x32" href="{{ 'public/of_logo.png' }}?v=1">
</head>
    <style>
        /* Synced Global Background System */
        .bg-main {
            background: radial-gradient(circle at top right, #070707, #043096);
            background-attachment: fixed;
        }
        .map-overlay {
            position: fixed; inset: 0;
            background-image: radial-gradient(rgba(59, 130, 246, 0.08) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none; z-index: 0;
        }
        .marker {
            position: absolute; width: 6px; height: 6px; background: #3b82f6;
            border-radius: 50%; filter: blur(1px); opacity: 0.4;
        }
        .marker::after {
            content: ''; position: absolute; inset: -8px;
            border: 1px solid #3b82f6; border-radius: 50%;
            animation: pulse 4s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.5); opacity: 0.5; }
            100% { transform: scale(3); opacity: 0; }
        }

        /* Glassmorphism Logic */
        .glass-card { 
            background: rgba(20, 14, 14, 0.747); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.08); 
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        [x-cloak] { display: none !important; }
    </style>

<body x-data="{ 
    successModalOpen: {{ session('success') ? 'true' : 'false' }},
    applyModalOpen: false,
    analyticsOpen: false,
    editOpen: false,
    detailModalOpen: false,
    selectedApp: {}
    }" 
	class="bg-gray-900 text-white">
       <div class="map-overlay">
        <div class="marker" style="top: 15%; left: 80%;"></div>
        <div class="marker" style="top: 75%; left: 10%; animation-delay: 1.5s;"></div>
    </div>
    @include('partial.navigation')

    <section class="w-full flex justify-center items-start py-10 px-4 mt-10 ">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{--==================================================== COMPANY DASHBOARD ====================================================--}}
            @if(!$company)
                <div class="lg:col-span-12">
                    <p class="text-center text-red-400">No company registered yet.</p>
                </div>
            @else
            
                {{--==================================================== LEFT SIDEBAR: Activity Log ====================================================--}}
                <aside class="hidden lg:block lg:col-span-3 sticky top-24 h-fit space-y-4">
                    <div class="rounded bg-gray-800 p-5 border border-white/5 shadow-xl">
                        <div class="flex items-center justify-between mb-6 px-1">
                            <h3 class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em]">Activity Logs</h3>
                            <span class="flex h-1.5 w-1.5 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span>
                        </div>

                        <div class="space-y-6 max-h-[450px] overflow-y-auto hide-scrollbar relative pl-2">
                            <div class="absolute left-[11px] top-2 bottom-2 w-[1px] bg-white/5"></div>

                            @php
                                $user = auth()->user();
                                $history = collect();
                                
                                if ($user->posts) {
                                    foreach($user->posts->sortByDesc('created_at')->take(3) as $post) {
                                        $history->push(['type' => 'post', 'time' => $post->created_at, 'label' => 'Activity', 'desc' => Str::limit($post->content, 30)]);
                                    }
                                }
                                if ($user->comments) {
                                    foreach($user->comments->sortByDesc('created_at')->take(3) as $comment) {
                                        $history->push(['type' => 'comment', 'time' => $comment->created_at, 'label' => 'Feedback', 'desc' => 'Replied to ' . ($comment->post->user->name ?? 'User')]);
                                    }
                                }
                                if ($user->likes) {
                                    foreach($user->likes->sortByDesc('created_at')->take(3) as $like) {
                                        if($like->post) {
                                            $history->push(['type' => 'like', 'time' => $like->created_at, 'label' => 'Endorsed', 'desc' => 'Liked ' . ($like->post->user->name ?? 'a post')]);
                                        }
                                    }
                                }
                                $sortedHistory = $history->sortByDesc('time')->take(6);
                            @endphp

                            @forelse($sortedHistory as $item)
                                <div class="relative pl-6 group">
                                    <div class="absolute left-0 top-1.5 h-2 w-2 rounded-full border border-gray-900 z-10 {{ $item['type'] == 'post' ? 'bg-blue-500' : ($item['type'] == 'comment' ? 'bg-emerald-500' : 'bg-pink-500') }}"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-0.5">{{ $item['label'] }}</span>
                                        <p class="text-[11px] text-slate-300 leading-tight group-hover:text-white transition">{{ $item['desc'] }}</p>
                                        <span class="text-[9px] text-slate-600 font-bold uppercase mt-1 tracking-tighter">{{ $item['time']->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-[10px] text-slate-600 uppercase text-center py-4">No recent activity</p>
                            @endforelse
                        </div>
                    </div>
                </aside>

                {{--==================================================== MAIN CONTENT ====================================================--}}
                <div class="lg:col-span-9 space-y-5">
                    
                    {{--==================================================== Company Header Card ====================================================--}}
                    <div class="rounded p-8 flex flex-col md:flex-row items-center gap-6 border border-white/10 shadow-xl">
                        {{--==================================================== Logo ====================================================--}}
                        <form action="{{ route('company.logo.update') }}" method="POST" enctype="multipart/form-data" class="relative group flex-shrink-0">
                            @csrf
                            @method('PUT')

                            <label class="cursor-pointer block relative">
                                {{--==================================================== Company Logo Image ====================================================--}}
                                <img src="{{ $company->company_logo ? route('image.display', ['path' => $company->company_logo]) : 'https://ui-avatars.com/api/?name=' . urlencode($company->company_name) . '&background=1e293b&color=3b82f6' }}"
                                     class="h-32 w-32 rounded object-cover border-2 border-white/10 shadow-2xl transition duration-300 group-hover:border-blue-500/50"
                                     alt="{{ $company->company_name }} Logo">

                                {{--==================================================== Hover Overlay ====================================================--}}
                                <div class="absolute inset-0 bg-black/60 rounded opacity-0 group-hover:opacity-100 flex items-center justify-center text-[10px] font-bold uppercase tracking-widest transition-opacity text-white">
                                    Change Logo
                                </div>

                                {{--========================== The Hidden Input (NAME UPDATED TO MATCH CONTROLLER) ==========================--}}
                                <input type="file" name="company_logo" class="hidden" onchange="this.form.submit()">
                            </label>
                        </form>
                        <div class="text-center md:text-left">
                            <h1 class="text-3xl font-black tracking-tight text-white">{{ $company->company_name }}</h1>
                            <p class="text-blue-400 text-xs font-black uppercase tracking-[0.2em] mt-2">
                                Registered Company Partner
                            </p>
                            <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                                <div class="text-center md:text-left">
                                    <p class="text-lg font-bold">{{ $user->followers->count() }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-tighter">Network Reach</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {{--==================================================== Company Info ====================================================--}}
                        <div class="rounded p-6 border border-white/10 space-y-5">
                            <h3 class="font-bold text-xs uppercase tracking-widest text-blue-400 border-b border-white/5 pb-3">
                                Business Credentials
                            </h3>
                            <div class="text-sm space-y-4">
                                <div>
                                    <span class="block text-[10px] text-gray-500 uppercase font-black tracking-widest">Email Address</span>
                                    <span class="text-slate-200">{{ $company->email }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-gray-500 uppercase font-black tracking-widest">Contact Signal</span>
                                    <span class="text-slate-200">{{ $company->contact_number }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-gray-500 uppercase font-black tracking-widest">Address</span>
                                    <p class="text-slate-200 text-xs leading-relaxed">{{ $company->address }}</p>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-gray-500 uppercase font-black tracking-widest">Bio</span>
                                    <p class="text-slate-200 text-xs leading-relaxed">{{ $company->about }}</p>
                                </div>
                            </div>
                        </div>

                        {{--==================================================== RIGHT SIDE: Actions & Documents ====================================================--}}
                        <div class="lg:col-span-2 space-y-6">
                            <div class="rounded p-6 border border-white/10">
                                <h3 class="font-bold text-xs uppercase tracking-widest text-blue-400 mb-6">Operations</h3>
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ url('newsfeed') }}" class="px-6 py-2.5 bg-blue-600/20 text-blue-400 border border-blue-500/30 hover:bg-blue-600 hover:text-white rounded text-xs font-black tracking-widest transition">
                                        Post Opportunity
                                    </a>
                                    <button @click="analyticsOpen = true" class="px-6 py-2.5 bg-blue-600/20 text-blue-400 border border-blue-500/30 hover:bg-blue-600 hover:text-white rounded text-xs font-black  tracking-widest transition">
                                        View Analytics
                                    </button>
                                      <button id="editBtn" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 rounded text-xs font-black tracking-widest transition">
                                        Edit Details 
                                    </button>
                                </div>
                            </div>

                            <div class="rounded p-6 border border-white/10">
                                <h3 class="font-bold text-xs uppercase tracking-widest text-blue-400 mb-4">Verification Vault</h3>
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @php
                                        $docs = [
                                            ['label' => 'Corporation Cert', 'file' => $company->certificate_of_corporation],
                                            ['label' => 'Registration Cert', 'file' => $company->certificate_of_registration],
                                            ['label' => 'Mayor\'s Permit', 'file' => $company->mayors_permit],
                                            ['label' => 'Barangay Clearance', 'file' => $company->barangay_clearance],
                                        ];
                                    @endphp
                                    @foreach($docs as $doc)
                                        @if($doc['file'])
                                            <li class="p-3 bg-gray-900/50 rounded border border-white/5 flex items-center gap-3">
                                                <span class="text-blue-500">📄</span>
                                                <a href="{{ asset('storage/app/public/' . $doc['file']) }}" target="_blank" class="text-[11px] font-bold text-slate-300 hover:text-blue-400 truncate">
                                                    {{ $doc['label'] }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{--==================================================== MODAL SECTION ====================================================--}}
    <div id="editModal" class="fixed inset-0 hidden items-center justify-center backdrop-blur-sm z-50 p-4">
        <div class="bg-gray-800 border border-white/10 text-white rounded-2xl w-full max-w-md p-8 relative animate-fadeIn shadow-2xl">
            <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-white transition">✕</button>
            <h2 class="text-xl font-black uppercase tracking-widest mb-6">Update Profile</h2>
            <form method="POST" action="{{ route('company.details.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $company->company_name) }}" class="w-full bg-gray-900 border border-white/5 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Business Email</label>
                    <input type="email" name="email" value="{{ old('email', $company->email) }}" class="w-full bg-gray-900 border border-white/5 rounded-xl px-4 py-3 text-sm"> 
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Company Address</label>
                    <input type="text" name="address" value="{{ old('address', $company->address) }}" class="w-full bg-gray-900 border border-white/5 rounded-xl px-4 py-3 text-sm"> 
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">About</label>
                    <textarea name="about" rows="3" class="w-full bg-gray-900 border border-white/5 rounded-xl px-4 py-3 text-sm resize-none">{{ old('about', $company->about) }}</textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelBtn" class="px-6 py-2 text-xs font-bold text-gray-400">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-500/20">Save</button>
                </div>
            </form>
        </div>
    </div>
    <div x-show="analyticsOpen" 
        x-cloak
        class="fixed inset-0 z-[70] flex items-center justify-center p-4 md:p-10" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">
        {{--==================================================== Backdrop ====================================================--}}
        <div class="absolute inset-0 backdrop-blur-sm" @click="analyticsOpen = false"></div>
        {{--==================================================== Modal Content ====================================================--}}
        <div @click.away="analyticsOpen = false" 
            class="relative bg-gray-900 border border-white/10 text-white rounded-xl w-full max-w-4xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
            {{--==================================================== Header ====================================================--}}
            <div class="p-8 border-b border-white/5 bg-gray-800/50 flex justify-between items-center shrink-0">
                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tighter italic text-white">Applicant Pipeline</h2>
                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-[0.2em] mt-1">Manage your incoming OJT talent</p>
                </div>
                <button @click="analyticsOpen = false" class="h-10 w-10 flex items-center justify-center rounded bg-white/5 hover:bg-red-500/20 hover:text-red-500 transition">✕</button>
            </div>

            {{--==================================================== Stats Bar ====================================================--}}
            <div class="flex flex-wrap border-b border-white/5 bg-gray-800/20 shrink-0">
                {{--==================================================== Total ====================================================--}}
                <div class="flex-1 min-w-[100px] p-6 border-r border-white/5">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block">Total</span>
                    <span class="text-2xl font-black text-white">{{ $applications->count() }}</span>
                </div>
                {{--==================================================== Accepted ====================================================--}}
                <div class="flex-1 min-w-[100px] p-6 border-r border-white/5">
                    <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest block">Accepted</span>
                    <span class="text-2xl font-black text-white">{{ $applications->where('status', 'accepted')->count() }}</span>
                </div>
                {{--==================================================== Pending ====================================================--}}
                <div class="flex-1 min-w-[100px] p-6 border-r border-white/5">
                    <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest block">Pending</span>
                    <span class="text-2xl font-black text-white">{{ $applications->where('status', 'pending')->count() }}</span>
                </div>
                {{--==================================================== Rejected ====================================================--}}
                <div class="flex-1 min-w-[100px] p-6">
                    <span class="text-[9px] font-black text-red uppercase tracking-widest block">Rejected</span>
                    <span class="text-2xl font-black text-white">{{ $applications->where('status', 'rejected')->count() }}</span>
                </div>
            </div>
            {{--==================================================== Scrollable List Area ====================================================--}}
            <div class="flex-1 overflow-y-auto p-6 bg-gray-950/50 custom-scrollbar">
                <div class="space-y-4">
                    @forelse($applications as $app)
                        <div class="group bg-white/5 hover:bg-white/[0.08] border border-white/5 rounded-xl p-5 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                            {{--==================================================== Student Info ====================================================--}}
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded overflow-hidden shadow-lg shrink-0 border border-white/10">
                                    <img 
                                        src="{{ $app->student->profile_picture ? route('image.display', ['path' => $app->student->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($app->student->name) . '&background=0284c7&color=fff' }}" 
                                        alt="{{ $app->student->name }}"
                                        class="h-full w-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-white group-hover:text-blue-400 transition text-base">{{ $app->student->name }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">{{ $app->post->title ?? 'OJT Position' }}</span>
                                        <span class="h-1 w-1 rounded-full bg-gray-700"></span>
                                        <span class="text-[10px] font-medium text-blue-400/80">{{ $app->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            {{--==================================================== Status & Actions ====================================================--}}
                            <div class="flex items-center justify-between md:justify-end gap-6 border-t md:border-t-0 pt-4 md:pt-0 border-white/5">
                                <div class="flex flex-col items-start md:items-end">
                                    <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest mb-1">Status</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="h-2 w-2 rounded-full {{ $app->status == 'accepted' ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-red-500' }}"></span>
                                        <span class="text-[10px] font-black uppercase tracking-widest {{ $app->status == 'accepted' ? 'text-emerald-500' : 'text-blue-400' }}">
                                            {{ $app->status }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button @click="selectedApp = { 
                                            slug: '{{ $app->student->slug }}',
                                            name: '{{ $app->student->name }}', 
                                            email: '{{ $app->student->email }}',
                                            contact: '{{ $app->student->contact_number ?? 'Not Provided' }}',
                                            profile_photo: '{{ $app->student->profile_picture ? route('image.display', ['path' => $app->student->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($app->student->name) . '&background=1e293b&color=3b82f6' }}',
                                            message: '{{ addslashes($app->message) }}', 
                                            date: '{{ $app->created_at->format('M d, Y') }}',
                                            status: '{{ $app->status }}',
                                            post: '{{ $app->post->title ?? 'OJT Position' }}'
                                        }; detailModalOpen = true"
                                        class="h-10 px-4 bg-white/5 hover:bg-blue-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                        Details
                                    </button>
                                    
                                    @if($app->status == 'pending')
                                        <form action="{{ route('applications.update-status', $app->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" onclick="return confirm('Accept this applicant?')"
                                                    class="h-10 w-10 bg-emerald-600/20 text-emerald-500 hover:bg-emerald-600 hover:text-white rounded-lg flex items-center justify-center transition-all">
                                                ✔
                                            </button>
                                        </form>
                                        <form action="{{ route('applications.update-status', $app->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                                <button type="submit" onclick="return confirm('Reject this applicant?')"
                                                        class="h-10 w-10 bg-red-200/20 text-white hover:bg-red-600 hover:text-white rounded-lg flex items-center justify-center transition-all">
                                                    X
                                                </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center bg-white/5 rounded-xl border border-dashed border-white/10">
                            <div class="text-4xl mb-4">📥</div>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em]">No applications in the pipeline</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{--==================================================== Footer ====================================================--}}
            <div class="p-6 bg-gray-800/50 border-t border-white/5 text-center shrink-0">
                <p class="text-[9px] text-gray-600 font-bold uppercase tracking-[0.3em]">ojtFinder Recruitment System v2.0</p>
            </div>
        </div>
    </div>
    <div x-show="detailModalOpen" 
        x-cloak 
        class="fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md" @click="detailModalOpen = false"></div>
        <div class="relative bg-gray-900 border border-white/10 w-full max-w-lg rounded overflow-hidden shadow-2xl animate-fadeIn"
            @click.away="detailModalOpen = false">
            
            <div class="p-6 border-b border-white/5 bg-gray-800/50 flex justify-between items-center">
                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-blue-400">Applicant Profile</h3>
                <button @click="detailModalOpen = false" class="text-gray-500 hover:text-white transition">✕</button>
            </div>

            <div class="p-8 space-y-6">
                <div class="flex items-center gap-5">
                    <img :src="selectedApp.profile_photo" 
                        class="h-20 w-20 rounded object-cover border-2 border-white/10 shadow-lg"
                        :alt="selectedApp.name">
                    <div>
                        <h4 class="text-2xl font-black text-white tracking-tight" x-text="selectedApp.name"></h4>
                        <p class="text-xs text-blue-400 font-bold uppercase tracking-widest mt-1" x-text="selectedApp.post"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4 border-y border-white/5">
                    <div>
                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block mb-1">Email Address</span>
                        <p class="text-sm font-medium text-slate-200" x-text="selectedApp.email"></p>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block mb-1">Contact Number</span>
                        <p class="text-sm font-medium text-slate-200" x-text="selectedApp.contact"></p>
                    </div>
                </div>

                <div>
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block mb-2">Message to Employer</span>
                    <div class="bg-gray-800/50 rounded p-4 border border-white/5 text-sm text-slate-300 leading-relaxed max-h-40 overflow-y-auto italic" 
                        x-text="selectedApp.message">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/5 p-3 rounded border border-white/5 text-center">
                        <span class="block text-[8px] text-gray-500 uppercase font-black tracking-widest">Submission Date</span>
                        <span class="text-xs font-bold" x-text="selectedApp.date"></span>
                    </div>
                    <div class="bg-white/5 p-3 rounded border border-white/5 text-center">
                        <span class="block text-[8px] text-gray-500 uppercase font-black tracking-widest">Pipeline Status</span>
                        <span class="text-xs font-bold uppercase" 
                            :class="selectedApp.status === 'accepted' ? 'text-emerald-400' : 'text-blue-400'"
                            x-text="selectedApp.status"></span>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-800/30 border-t border-white/5 flex gap-3">
                <button @click="detailModalOpen = false" 
                        class="flex-1 py-3 bg-white/5 hover:bg-white/10 rounded text-[10px] font-black uppercase tracking-widest transition">
                    Back to List
                </button>
                <a :href="'/messages/' + selectedApp.slug" 
                class="flex-1 py-3 bg-blue-600 hover:bg-blue-500 text-center rounded text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-blue-600/20">
                    Message Applicant
                </a>
            </div>
        </div>
    </div>
    {{--==================================================== Success/Accepted Modal ====================================================--}}
    @if(session('success'))
        <div x-data="{ successModalOpen: true }" 
            x-show="successModalOpen" 
            x-cloak 
            class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="successModalOpen = false"></div>

            <div class="relative bg-gray-900 border border-emerald-500/30 w-full max-w-sm rounded-2xl p-8 text-center shadow-2xl shadow-emerald-900/20">
                
                <div class="mx-auto h-20 w-20 bg-emerald-500/10 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-10 w-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-2"></h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    {{ session('success') }}
                </p>

                <button @click="successModalOpen = false" 
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-emerald-600/20">
                    Continue Working
                </button>
            </div>
        </div>
    @endif

    {{--==================================================== Rejected/Error Modal ====================================================--}}
    @if(session('error'))
        <div x-data="{ errorModalOpen: true }" 
            x-show="errorModalOpen" 
            x-cloak 
            class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="errorModalOpen = false"></div>

            <div class="relative bg-gray-900 border border-rose-500/30 w-full max-w-sm rounded-2xl p-8 text-center shadow-2xl shadow-rose-900/20">
                
                <div class="mx-auto h-20 w-20 bg-rose-500/10 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-10 w-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-2">Application Rejected</h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    {{ session('error') }}
                </p>

                <button @click="errorModalOpen = false" 
                        class="w-full py-3 bg-rose-600 hover:bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-rose-600/20">
                    Close Panel
                </button>
            </div>
        </div>
    @endif
<script>
    //========================== MODAL LOGIC ==========================// 
    const editBtn = document.getElementById('editBtn');
    const editModal = document.getElementById('editModal');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    //========================== Open Modal ==========================//
    editBtn.addEventListener('click', () => { editModal.classList.remove('hidden'); editModal.classList.add('flex'); });
    closeModal.addEventListener('click', () => { editModal.classList.replace('flex', 'hidden'); });
    cancelBtn.addEventListener('click', () => { editModal.classList.replace('flex', 'hidden'); });
    window.addEventListener('click', (e) => { if(e.target === editModal) editModal.classList.replace('flex', 'hidden'); });
</script>
<style>
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-fadeIn { animation: fadeIn 0.2s ease-out forwards; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</body>
</html>