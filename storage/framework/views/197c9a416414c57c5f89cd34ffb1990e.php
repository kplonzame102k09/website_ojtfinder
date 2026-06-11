<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ojtFinder | Settings</title>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZEMJ5KJY75"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZEMJ5KJY75');
</script>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e('public/of_logo.png'); ?>?v=1">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        .bg-main { background: radial-gradient(circle at top right, #070707, #0f172a); background-attachment: fixed; }
        .glass-card { background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(10px); }

        .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.2); 
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.5);
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
            .animate-fadeIn { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
		[x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-main text-slate-300 antialiased font-sans min-h-screen">

    <div class="map-overlay">
        <div class="marker" style="top: 15%; left: 80%;"></div>
        <div class="marker" style="top: 75%; left: 10%; animation-delay: 1.5s;"></div>
    </div>
    <?php echo $__env->make('partial.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="relative z-10 max-w-8xl mx-auto pt-24 pb-20 px-4" x-data="{ tab: 'account' }">
                
        <?php if(session('success')): ?>
            <div class="mb-6 p-4 bg-blue-600/20 border border-blue-500 text-blue-400 rounded text-xs font-bold uppercase tracking-tight">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="mb-6 p-4 bg-red-600/20 border border-red-500 text-red-400 rounded text-xs font-bold uppercase tracking-tight animate-fadeIn">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <aside class="lg:col-span-4">
                <div class="glass-card rounded p-2 sticky top-24">
                    
                    <nav class="flex flex-col gap-1">
                        <button @click="tab = 'account'" :class="tab === 'account' ? 'bg-blue-600/10 text-blue-400 border-blue-500/50' : 'text-slate-500 border-transparent hover:bg-white/5'" class="flex items-center gap-3 px-4 py-3 rounded text-xs font-black uppercase tracking-tight border transition-all">
                            Personal Details
                        </button>
                        <button @click="tab = 'security'" :class="tab === 'security' ? 'bg-blue-600/10 text-blue-400 border-blue-500/50' : 'text-slate-500 border-transparent hover:bg-white/5'" class="flex items-center gap-3 px-4 py-3 rounded text-xs font-black uppercase tracking-tight border transition-all">
                            Change Password
                        </button>
                        <button @click="tab = 'activity'" :class="tab === 'activity' ? 'bg-blue-600/10 text-blue-400 border-blue-500/50' : 'text-slate-500 border-transparent hover:bg-white/5'" class="flex items-center gap-3 px-4 py-3 rounded text-xs font-black uppercase tracking-tight border transition-all">
                            Activity Logs
                        </button>
                        <button @click="tab = 'legal'" :class="tab === 'legal' ? 'bg-indigo-600/10 text-indigo-400 border-indigo-500/50' : 'text-slate-500 border-transparent hover:bg-white/5'" class="flex items-center gap-3 px-4 py-3 rounded text-xs font-black uppercase tracking-tight border transition-all">
                            Legal Policies
                        </button>
                        <button @click="tab = 'about'" :class="tab === 'about' ? 'bg-amber-600/10 text-amber-400 border-amber-500/50' : 'text-slate-500 border-transparent hover:bg-white/5'" class="flex items-center gap-3 px-4 py-3 rounded text-xs font-black uppercase tracking-tight border transition-all">
                            About ojtFinder
                        </button>
                    </nav>
                </div>
            </aside>

            
            <div class="lg:col-span-8">
                
                <div x-show="tab === 'account'" 
                     x-data="{ 
                        photoPreview: null, 
                        isUploading: false,
                        handleFileChange(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            const reader = new FileReader();
                            reader.onload = (e) => { this.photoPreview = e.target.result; };
                            reader.readAsDataURL(file);
                        }
                     }" 
                     class="space-y-6">

                    <div x-show="isUploading" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-slate-950/80 backdrop-blur-sm"
                         x-cloak>
                        <div class="relative">
                            <div class="w-16 h-16 border-4 border-blue-500/20 border-t-blue-600 rounded-full animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-4 text-xs font-black text-white uppercase tracking-widest animate-pulse">Updating Profile...</p>
                    </div>

                    <form action="<?php echo e(route('settings.update')); ?>" 
                          method="POST" 
                          enctype="multipart/form-data"
                          @submit="isUploading = true">
                        <?php echo csrf_field(); ?> 
                        <?php echo method_field('PUT'); ?>

                        <div class="glass-card rounded p-8 border-l-4 border-l-blue-600">
                            <h2 class="text-xl font-black text-white uppercase tracking-tighter mb-6">Profile Settings</h2>

                            <div class="space-y-6">
                                <div class="flex items-center gap-6 pb-6 border-b border-white/5">
                                    <div class="relative">
                                        <img :src="photoPreview ? photoPreview : '<?php echo e($user->profile_picture ? route('image.display', ['path' => $user->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1e293b&color=fff'); ?>'" 
                                             class="w-20 h-20 rounded-full object-cover ring-4 ring-blue-500/20 bg-slate-800 transition-all duration-300">

                                        <template x-if="photoPreview">
                                            <span class="absolute -bottom-1 -right-1 bg-blue-600 text-[8px] font-black text-white px-1.5 py-0.5 rounded uppercase">New</span>
                                        </template>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-blue-500 uppercase mb-2">Upload New Profile Picture</label>

                                        <input type="file" 
                                               name="profile_picture" 
                                               @change="handleFileChange"
                                               class="text-xs text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-500/10 file:text-blue-400 hover:file:bg-blue-500/20 cursor-pointer">

                                        <?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                            <p class="text-red-500 text-[10px] mt-1 font-bold"><?php echo e($message); ?></p> 
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                        <p class="text-[9px] text-slate-500 mt-2 italic">Recommended: Square JPG or PNG, Max 2MB</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase">Display Name</label>
                                        <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white focus:ring-1 focus:ring-blue-600">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase">Email Address</label>
                                        <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white focus:ring-1 focus:ring-blue-600">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase">Contact Number</label>
                                        <input type="text" name="contact_number" value="<?php echo e(old('contact_number', $user->contact_number)); ?>" class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white focus:ring-1 focus:ring-blue-600">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase">Address</label>
                                        <input type="text" name="address" value="<?php echo e(old('address', $user->address)); ?>" class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white focus:ring-1 focus:ring-blue-600">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 uppercase">Bio / Description</label>
                                    <textarea name="bio" rows="4" class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white focus:ring-1 focus:ring-blue-600"><?php echo e(old('bio', $user->bio)); ?></textarea>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded text-xs font-black transition-all">
                                    UPLOAD CHANGES
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div x-show="tab === 'security'" style="display: none;">
                    <form action="<?php echo e(route('settings.password')); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div class="glass-card rounded p-8 border-l-4 border-l-red-500/30">
                            <h2 class="text-xl font-black text-white uppercase tracking-tighter mb-6">Change Password</h2>
                            
                            <div class="space-y-4 max-w-md">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 uppercase">Current Password</label>
                                    <input type="password" name="current_password" class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white">
                                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px]"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 uppercase">New Password</label>
                                    <input type="password" name="password" class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white">
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px]"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 uppercase">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="w-full bg-white/5 border border-white/10 rounded px-4 py-3 text-sm text-white">
                                </div>
                            </div>

                            <div class="mt-8">
                                <button type="submit" class="bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white border border-red-500/50 px-8 py-3 rounded text-xs font-black transition-all">Update Password</button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div x-show="tab === 'activity'" style="display: none;" class="space-y-6">
                    <div class="glass-card rounded p-8 border-l-4 border-l-emerald-600">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter mb-6">Activity Logs</h2>
                        
                        <div class="space-y-4 max-h-[500px] overflow-y-auto pr-4 custom-scrollbar">
                            
                            <?php $__empty_1 = true; $__currentLoopData = $user->activities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center justify-between py-3 border-b border-white/5 last:border-0">
                                    <div>
                                        <p class="text-sm text-white font-bold"><?php echo e($activity->description); ?></p>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-tight"><?php echo e($activity->created_at->format('M d, Y • h:i A')); ?></p>
                                    </div>
                                    <span class="text-[10px] px-2 py-1 rounded bg-white/5 text-slate-400 border border-white/10">
                                        <?php echo e($activity->ip_address ?? '0.0.0.0'); ?>

                                    </span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-10">
                                    <p class="text-slate-500 text-xs uppercase tracking-tight">No recent activity found</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                
                <div x-show="tab === 'about'" style="display: none;" class="space-y-6 animate-fadeIn">
                    <div class="glass-card rounded p-8 border-l-4 border-l-amber-600">
                        <h2 class="text-xl font-black text-white uppercase tracking-tighter mb-2">About the Platform</h2>
                        <p class="text-[10px] font-bold text-amber-500 uppercase tracking-[0.2em] mb-8">Bridging Ambition and Industry</p>
                        
                        <div class="space-y-8">
                            
                            <div class="space-y-3">
                                <h3 class="text-xs font-black text-slate-200 uppercase tracking-widest">Our Mission</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">
                                    ojtFinder is a specialized ecosystem designed to streamline the transition from academic learning to professional excellence. We provide a high-integrity infrastructure where students can discover verified internship opportunities and companies can scout the next generation of industry leaders.
                                </p>
                            </div>

                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-white/5 rounded border border-white/5">
                                    <span class="text-lg mb-2 block">📡</span>
                                    <h4 class="text-[10px] font-black text-white uppercase mb-1">Real-time Matching</h4>
                                    <p class="text-[11px] text-slate-500">Advanced filtering based on location, category, and skill requirements.</p>
                                </div>
                                <div class="p-4 bg-white/5 rounded border border-white/5">
                                    <span class="text-lg mb-2 block">🛡️</span>
                                    <h4 class="text-[10px] font-black text-white uppercase mb-1">Verified Partners</h4>
                                    <p class="text-[11px] text-slate-500">Strict verification protocols for all participating corporate entities.</p>
                                </div>
                            </div>

                            
                            <div class="pt-6 border-t border-white/5">
                                <div class="flex flex-wrap gap-8">
                                    <div>
                                        <p class="text-[9px] font-black text-slate-500 uppercase mb-1">Architecture</p>
                                        <p class="text-xs font-bold text-slate-300">Laravel v11.x</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-500 uppercase mb-1">Environment</p>
                                        <p class="text-xs font-bold text-emerald-500 flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Stable Production
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-500 uppercase mb-1">Developer</p>
                                        <p class="text-xs font-bold text-slate-300">ojtFinder Core Team</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="glass-card rounded p-6 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-amber-500/10 rounded flex items-center justify-center text-amber-500 font-black">
                                v1
                            </div>
                            <div>
                                <p class="text-xs font-black text-white uppercase">Build 2026.01</p>
                                <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Latest stable release</p>
                            </div>
                        </div>
                        <a href="#" class="text-[10px] font-black text-amber-500 hover:text-white transition uppercase border-b border-amber-500/30">Release Notes</a>
                    </div>
                </div>
                
                <div x-show="tab === 'legal'" x-data="{ subtab: 'terms' }" style="display: none;" class="space-y-6">
                    <div class="glass-card rounded border-l-4 border-l-indigo-600 overflow-hidden">
                        
                        <div class="flex border-b border-white/5 bg-white/5">
                            <button @click="subtab = 'terms'" :class="subtab === 'terms' ? 'text-indigo-400 border-b-2 border-indigo-500' : 'text-slate-500'" class="px-6 py-4 text-[10px] font-black uppercase tracking-tight transition-all">Terms</button>
                            <button @click="subtab = 'privacy'" :class="subtab === 'privacy' ? 'text-indigo-400 border-b-2 border-indigo-500' : 'text-slate-500'" class="px-6 py-4 text-[10px] font-black uppercase tracking-tight transition-all">Privacy</button>
                            <button @click="subtab = 'cookies'" :class="subtab === 'cookies' ? 'text-indigo-400 border-b-2 border-indigo-500' : 'text-slate-500'" class="px-6 py-4 text-[10px] font-black uppercase tracking-tight transition-all">Cookies</button>
                        </div>

                        <div class="p-8">
                            
                            <div x-show="subtab === 'terms'" class="space-y-4">
                                <h3 class="text-white font-black uppercase tracking-tighter text-lg">Terms of Service</h3>
                                <div class="prose prose-invert text-xs text-slate-400 leading-relaxed max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                                    <p class="mb-4 font-bold text-slate-300">1. Acceptance of Protocol</p>
                                    <p>By utilizing the ojtFinder infrastructure, you agree to these terms. Unauthorized access or data scraping is strictly prohibited.</p>
                                    <p class="mb-4 font-bold text-slate-300">2. Verification Integrity</p>
                                    <p>Companies must maintain valid legal documentation. Falsified permits result in immediate terminal account suspension.</p>
                                </div>
                            </div>

                            
                            <div x-show="subtab === 'privacy'" style="display: none;" class="space-y-4">
                                <h3 class="text-white font-black uppercase tracking-tighter text-lg">Privacy Policy</h3>
                                <div class="prose prose-invert text-xs text-slate-400 leading-relaxed max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                                    <p class="mb-4 font-bold text-slate-300">Data Encryption</p>
                                    <p>All corporate and personal identifiers are stored using AES-256 encryption standards. We do not transmit data to third-party marketing entities.</p>
                                </div>
                            </div>

                            
                            <div x-show="subtab === 'cookies'" style="display: none;" class="space-y-4">
                                <h3 class="text-white font-black uppercase tracking-tighter text-lg">Cookies Policy</h3>
                                <div class="prose prose-invert text-xs text-slate-400 leading-relaxed max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                                    <p class="mb-4 font-bold text-slate-300">Essential Session Management</p>
                                    <p>We use session cookies to maintain your encrypted connection. Disabling these will result in restricted access to settings and dashboard features.</p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-white/5 p-4 px-8 border-t border-white/5 flex justify-between items-center">
                            <span class="text-[9px] font-bold text-slate-600 uppercase tracking-[0.2em]">ojtFinder Legal v1.0.4</span>
                            <button onclick="window.print()" class="text-[9px] font-black text-indigo-400 hover:text-white transition uppercase tracking-tight">Download Archive</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html><?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/pages/settings.blade.php ENDPATH**/ ?>