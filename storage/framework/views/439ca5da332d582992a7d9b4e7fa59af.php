<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ojtFinder | Newsfeed</title>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZEMJ5KJY75"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZEMJ5KJY75');
</script>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e('public/of_logo.png'); ?>?v=1">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .bg-main { background: radial-gradient(circle at top right, #070707, #0f172a); background-attachment: fixed; }
        .map-overlay { position: fixed; inset: 0; background-image: radial-gradient(rgba(59, 130, 246, 0.08) 1px, transparent 1px); background-size: 50px 50px; pointer-events: none; z-index: 0; }
        .marker { position: absolute; width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; filter: blur(1px); opacity: 0.4; }
        .marker::after { content: ''; position: absolute; inset: -8px; border: 1px solid #3b82f6; border-radius: 50%; animation: pulse 4s infinite; }
        @keyframes pulse { 0% { transform: scale(0.5); opacity: 0.5; } 100% { transform: scale(3); opacity: 0; } }
        .glass-panel { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        @keyframes shimmer { 100% { transform: translateX(100%); }}
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ 
    applyModalOpen: false, 
    activePostId: null, 
    activeCompanyName: '' 
    }" class="bg-main text-slate-200 antialiased relative min-h-screen">

    
    <div id="global-loader" style="display: none;" class="fixed inset-0 w-screen h-screen z-[9999] flex flex-col items-center justify-center bg-[#0f172a]/80 backdrop-blur-md">
        <div class="flex flex-col items-center gap-4">
            <div class="h-14 w-14 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-blue-500 font-black tracking-[0.2em] text-sm uppercase animate-pulse">Loading...</span>
        </div>
    </div>
    

    <div class="map-overlay">
        <div class="marker" style="top: 15%; left: 10%;"></div>
        <div class="marker" style="top: 45%; left: 85%; animation-delay: 1.5s;"></div>
        <div class="marker" style="top: 80%; left: 25%; animation-delay: 3s;"></div>
    </div>

    <?php echo $__env->make('partial.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="relative z-10 max-w-9xl mx-auto px-7 pt-24 pb-12">
        
        <div x-data="{ open: false }" class="lg:hidden" x-cloak>
            
            <button @click="open = true" 
                    class="fixed bottom-6 right-6 z-[60] bg-blue-600 text-white p-4 rounded-full shadow-[0_0_20px_rgba(37,99,235,0.5)] border border-white/20 active:scale-90 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            

            
            <div x-show="open" 
                x-transition:opacity
                @click="open = false" 
                class="fixed inset-0 bg-black/80 z-[70]"></div>
            

            
            <div x-show="open" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed inset-y-0 left-0 w-[280px] bg-[#0f172a] z-[80] p-6 shadow-2xl overflow-y-auto">
                
                <div class="flex items-center justify-between mb-8">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-500">Menu</span>
                    <button @click="open = false" class="text-slate-500 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                    
                    <div class="glass-panel rounded p-5 bg-white/5 border border-white/10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-500">Filter Training</h3>
                        </div>
                        
                        <div class="space-y-5 max-h-[180px] overflow-y-auto scrollbar">
                            <a href="<?php echo e(route('newsfeed')); ?>" 
                                class="flex items-center gap-3 px-3 py-2 rounded text-[11px] font-bold transition <?php echo e(!request('category') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400'); ?>">
                                # All Trainings
                            </a>
                            
                            <?php $__currentLoopData = $trainingCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('newsfeed', ['category' => $category])); ?>" 
                                class="flex items-center gap-3 px-3 py-2 rounded text-[11px] font-bold transition <?php echo e(request('category') == $category ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-white/5'); ?>">
                                <span class="text-blue-500">#</span> <?php echo e($category); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    

                    
                    <div class="glass-panel rounded p-5 bg-white/5 border border-white/10 mt-5">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-500">Notifications</h3>
                            <span class="flex h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                        </div>
                        <div class="space-y-5 max-h-[180px] overflow-y-auto hide-scrollbar">
                            <?php $__empty_1 = true; $__currentLoopData = auth()->user()->notifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $type = $notification->data['type'] ?? '';
                                    $postId = $notification->data['post_id'] ?? null;
                                    $senderId = $notification->data['sender_id'] ?? null;
                                    $sender = $senderId ? \App\Models\User::find($senderId) : null;
                                    $targetUrl = ($type === 'follow' && $sender) 
                                        ? route('profile.show', $sender->slug) 
                                        : route('newsfeed', ['open_comment' => $postId]);
                                ?>
                                <a href="<?php echo e($targetUrl); ?>" class="block group">
                                    <div class="flex gap-3 <?php echo e($notification->read_at ? 'opacity-40' : ''); ?>">
                                        <img src="<?php echo e($sender ? $sender->profile_picture_url : 'https://ui-avatars.com/api/?name=U'); ?>" class="h-8 w-8 rounded border border-white/10 object-cover">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] leading-tight text-slate-300">
                                                <span class="font-bold text-white"><?php echo e($notification->data['sender_name'] ?? 'From System:'); ?></span> 
                                                <span class="font-light text-white"><?php echo e($notification->data['message'] ?? 'interacted.'); ?></span>
                                            </p>
                                            <p class="text-[8px] text-slate-500 uppercase font-black mt-1"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                                        </div>
                                    </div>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-center text-[9px] text-slate-600 uppercase font-bold py-2">All Quiet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    
                    
                    <div class="glass-panel rounded p-5 bg-white/5 border border-white/10 mt-5">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-500 mb-4">Discover People</h3>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $suggestedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <img src="<?php echo e($sUser->profile_picture_url); ?>" class="h-8 w-8 rounded-full object-cover border border-white/10">
                                        <div class="flex flex-col min-w-0">
                                            <a href="<?php echo e(route('profile.show', $sUser->slug)); ?>" class="text-[10px] font-bold text-white truncate"><?php echo e($sUser->name); ?></a>
                                            <p class="text-[8px] text-slate-500 truncate italic"><?php echo e($sUser->address ?? 'Potential Match'); ?></p>
                                        </div>
                                    </div>
                                    <form action="<?php echo e(route('follow.toggle', $sUser->slug)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="bg-blue-600 text-white px-2.5 py-1 rounded text-[8px] font-black uppercase transition">Follow</button>
                                    </form>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
            <aside class="hidden lg:block lg:col-span-3 sticky top-24 h-fit space-y-4">
                <div class="glass-panel rounded-lg p-6 text-center shadow-xl bg-white/5 border border-white/10">
                    <div class="relative inline-block">
                      <img src="<?php echo e(auth()->user()->profile_picture_url); ?>" 
                                     alt="<?php echo e(auth()->user()->name); ?>"
                                     class="h-20 w-20 rounded-full object-cover border border-white/10 shadow-sm">
                        <?php if($user->isOnline()): ?>
                            <div class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 border-2 border-[#161e2d] rounded-full shadow-[0_0_8px_rgba(34,197,94,0.6)]">
                                <span class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-75"></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h2 class="mt-4 font-bold text-white text-lg tracking-tight"><?php echo e(auth()->user()->name); ?></h2>
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.15em] mt-1 opacity-80">
                        <?php echo e(auth()->user()->company ? auth()->user()->company->company_name : 'Student Talent'); ?>

                    </p>

                    <div class="mt-6 pt-6 border-t border-white/5 grid grid-cols-3 gap-2 text-center">
                        <div>
                            <p class="text-sm font-bold text-white"><?php echo e(auth()->user()->followers->count()); ?></p>
                            <p class="text-[9px] text-slate-500 uppercase font-bold tracking-tighter">Followers</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white"><?php echo e(auth()->user()->following->count()); ?></p>
                            <p class="text-[9px] text-slate-500 uppercase font-bold tracking-tighter">Following</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white"><?php echo e(auth()->user()->posts->count()); ?></p>
                            <p class="text-[9px] text-slate-500 uppercase font-bold tracking-tighter">Posts</p>
                        </div>
                    </div>
                </div>

                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-500">Filter Training</h3>
                        <?php if(request('category')): ?>
                            <a href="<?php echo e(route('newsfeed')); ?>" class="text-[9px] text-slate-500 hover:text-red-400 font-bold transition">CLEAR</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="space-y-1 max-h-[40vh] overflow-y-auto hide-scrollbar">
                        <a href="<?php echo e(route('newsfeed')); ?>" 
                            class="flex items-center gap-3 px-3 py-2 rounded text-[11px] font-bold transition <?php echo e(!request('category') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'); ?>">
                            <span class="opacity-50">#</span> All Trainings
                        </a>
                        
                        <?php $__currentLoopData = $trainingCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('newsfeed', ['category' => $category])); ?>" 
                            class="flex items-center gap-3 px-3 py-2 rounded text-[11px] font-bold transition <?php echo e(request('category') == $category ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'); ?>">
                            <span class="text-blue-500">#</span> <?php echo e($category); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </aside>
            
            
            
            <div class="lg:col-span-6 space-y-6">
                <?php if(auth()->user()->company): ?>
                <div class="glass-panel rounded p-5 shadow-2xl">
                    <form action="<?php echo e(route('newsfeed.store')); ?>" method="POST" enctype="multipart/form-data" class="w-full">
                        <?php echo csrf_field(); ?>
                        <textarea name="content" rows="1" placeholder="Post an internship opening..." oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'" class="w-full bg-white/5 rounded p-4 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500/50 resize-none border border-white/5 transition-all"></textarea>
                        <div class="mt-3">
                            <input type="text" name="training_category" placeholder="Type of Training (e.g. Web Development, HR, Accounting)" 
                                class="w-full bg-white/5 rounded px-4 py-2.5 text-xs text-slate-300 border border-white/5 focus:outline-none focus:ring-1 focus:ring-blue-500/50 transition-all" required>
                        </div>
                        
                        <div id="imagePreviewContainer" class="hidden mt-3 relative w-full max-w-full">
                            <img id="imagePreview" src="" class="max-h-64 w-auto rounded-lg border border-white/10 shadow-xl object-contain">
                            <button type="button" id="removeImage" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">✕</button>
                        </div>

                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-white/5">
                            <div class="flex gap-2">
                                <label class="flex items-center gap-2 bg-white/5 hover:bg-white/10 px-4 py-2 rounded cursor-pointer transition text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    <span>🖼️ Photo</span>
                                    <input type="file" name="image" id="postImageInput" accept="image/*" class="hidden">
                                </label>
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-8 py-2.5 rounded text-xs font-black transition-all active:scale-95 shadow-lg shadow-blue-500/20 text-white">POST</button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                
                <?php
                    $viewerAddress = auth()->user()->address ?? null;
                ?>

                <?php if(!auth()->user()->company && $viewerAddress): ?>
                    <div id="geo-recommendation" class="hidden mb-6">
                        <a id="geo-link" href="#" 
                           class="block group relative overflow-hidden rounded-xl border border-blue-500/30 bg-blue-500/5 p-5 transition-all hover:bg-blue-500/10 active:scale-[0.98] shadow-lg shadow-blue-900/10">
                            <div class="flex items-center gap-5 relative z-10">
                                <div class="flex-1">
                                    <p class="mt-1 text-sm font-bold text-white leading-tight">
                                        Show all nearby opportunities in <span id="geo-city-name" class="underline decoration-blue-500/50 text-blue-400 italic">Detecting location...</span>
                                    </p>
                                    <p class="mt-1 text-[11px] text-slate-400"> <span id="geo-accuracy">--</span>m</p>
                                </div>
                                <div class="text-blue-500 transition-transform group-hover:translate-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                </div>
                            </div>
                            
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-blue-500/10 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="space-y-6">
                    <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article id="post-<?php echo e($post->id); ?>" class="load-link glass-panel rounded p-6 space-y-4 hover:border-blue-500/20 transition-all shadow-lg">
                        <div class="flex justify-between items-start">
                            <div class="flex gap-3">
                                
                                <img src="<?php echo e($post->user->profile_picture_url); ?>" 
                                     alt="<?php echo e($post->user->name); ?>"
                                     class="h-11 w-11 rounded-full object-cover border border-white/10 shadow-sm">
                                     <div class="relative">
                                        
                                        <?php if($post->user->isOnline()): ?>
                                            <div class="absolute bottom-1 right-2 w-3 h-3 bg-green-500 border-2 border-[#161e2d] rounded-full shadow-[0_0_8px_rgba(34,197,94,0.6)]">
                                                <span class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-75"></span>
                                            </div>
                                        
                                        
                                        <?php elseif($post->user->last_seen && $post->user->last_seen->diffInMinutes() < 10): ?>
                                            <div class="absolute bottom-1 right-2 w-3 h-3 bg-yellow-500 border-2 rounded-full">
                                                <span class="absolute inset-0 rounded-full bg-yellow-500 opacity-75"></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="absolute bottom-1 right-2 w-3 h-3 bg-gray-500 rounded-full ">
                                                <span class="absolute inset-0 rounded-full bg-gray-500 opacity-75"></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <div>
                                    <a href="<?php echo e(route('profile.show', $post->user->slug)); ?>" class="font-bold text-white hover:text-blue-400 transition block text-sm"><?php echo e($post->user->name); ?></a>
                                    <p class="text-[9px] text-slate-500 uppercase tracking-widest font-black opacity-70"><?php echo e($post->created_at->diffForHumans()); ?></p>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <?php if($post->training_category): ?>
                                <span class="bg-blue-500/10 text-blue-400 text-[8px] font-black uppercase tracking-[0.2em] px-3 py-1.5 rounded-full border border-blue-500/20">
                                    <?php echo e($post->training_category); ?>

                                </span>
                                <?php endif; ?>
                                
                                <?php if(auth()->id() === $post->user_id): ?>
                                    <form action="<?php echo e(route('newsfeed.destroy', $post->id)); ?>" method="POST" onsubmit="return confirm('Delete permanently?');">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-slate-600 hover:text-red-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <p class="text-sm text-slate-300 leading-relaxed whitespace-pre-line"><?php echo e($post->content); ?></p>
                        
                        <?php if($post->image): ?>
                            
                            <img src="<?php echo e(route('image.display', ['path' => $post->image])); ?>" class="rounded w-full border border-white/5">
                        <?php endif; ?>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-white/5">
                            <div class="flex gap-6">
                                <form action="<?php echo e(route('posts.like', $post->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button class="flex items-center gap-2 text-xs font-bold <?php echo e($post->likes->where('user_id', auth()->id())->count() ? 'text-blue-500' : 'text-slate-500 hover:text-white'); ?> transition">
                                        <span class="text-sm">👍</span> <?php echo e($post->likes->count()); ?>

                                    </button>
                                </form>

                                <button onclick="openCommentModal(<?php echo e($post->id); ?>)" class="flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-white transition">
                                    <span class="text-sm">💬</span> <?php echo e($post->comments->count()); ?>

                                </button>

                                <button onclick="copyPostLink('<?php echo e(route('profile.show', $post->user->slug) . '#post-' . $post->id); ?>', this)" 
                                        class="flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-white transition">
                                    <span class="text-sm">🔗</span> Share
                                </button>
                            </div>
                            
                           <?php if(!auth()->user()->company): ?>
                            <div>
                                <?php
                                    $application = $post->applications->where('student_id', auth()->id())->first();
                                ?>

                                <?php if($application): ?>
                                    
                                    <div class="flex flex-col items-start gap-1">
                                        <button disabled class="flex items-center gap-2 px-2 py-1.5 rounded bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-black uppercase tracking-widest cursor-not-allowed">
                                            <span class="text-xs">✓</span> Applied
                                        </button>
                                        
                                        
                                        <span class="text-[8px] font-bold uppercase tracking-widest px-1 
                                            <?php echo e($application->status == 'accepted' ? 'text-emerald-400' : 'text-red-400'); ?>">
                                            Status: <?php echo e($application->status); ?>

                                        </span>
                                    </div>
                                <?php else: ?>
                                    
                                    <button 
                                        @click="applyModalOpen = true; activePostId = <?php echo e($post->id); ?>; activeCompanyName = '<?php echo e(addslashes($post->user->company->company_name ?? 'this company')); ?>'"
                                        class="group relative flex items-center gap-2 px-2 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black uppercase tracking-[0.15em] rounded transition-all shadow-[0_0_15px_rgba(37,99,235,0.3)]">
                                        Apply Now
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        </div>

                        
                        <div id="commentModal-<?php echo e($post->id); ?>" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
                            <div class="absolute inset-0 bg-[#070707]/80 backdrop-blur-md" onclick="closeCommentModal(<?php echo e($post->id); ?>)"></div>
                            <div class="glass-panel w-full max-w-lg rounded p-8 relative shadow-2xl border border-white/10 flex flex-col max-h-[85vh]">
                                <div class="flex justify-between items-center mb-6 shrink-0">
                                    <h3 class="text-xl font-black text-white uppercase tracking-tighter">Comments</h3>
                                    <button onclick="closeCommentModal(<?php echo e($post->id); ?>)" class="text-slate-500 hover:text-white">✕</button>
                                </div>

                                <div class="flex-1 overflow-y-auto hide-scrollbar space-y-6 mb-6">
                                    <?php $__empty_2 = true; $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <div class="flex gap-3">
                                            <img src="<?php echo e($comment->user->profile_picture ? route('image.display', ['path' => $comment->user->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&background=1e293b&color=3b82f6'); ?>" 
                                                class="h-8 w-8 rounded-full object-cover border border-white/10">
                                            <div class="bg-white/5 rounded p-3 flex-1 border border-white/5">
                                                <div class="flex justify-between">
                                                    <a href="<?php echo e(route('profile.show', $comment->user->slug)); ?>" class="text-[12px] font-bold text-blue-400"><?php echo e($comment->user->name); ?></a>
                                                    <span class="text-[8px] text-slate-500"><?php echo e($comment->created_at->diffForHumans()); ?></span>
                                                </div>
                                                <p class="text-xs text-slate-300 mt-1"><?php echo e($comment->content); ?></p>
                                                <button onclick="toggleReplyForm(<?php echo e($comment->id); ?>)" class="mt-2 text-[9px] font-black uppercase text-slate-500 hover:text-blue-400">
                                                    Reply
                                                </button>

                                                <div id="reply-form-<?php echo e($comment->id); ?>" class="hidden ml-10 mt-2">
                                                    <form action="<?php echo e(route('newsfeed.comment', $post->id)); ?>" method="POST" class="flex gap-2">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="parent_id" value="<?php echo e($comment->id); ?>">
                                                        <input type="text" name="content" required placeholder="Reply..." class="bg-white/10 border border-white/10 rounded px-2 py-1 text-[10px] text-white outline-none">
                                                        <button class="bg-blue-600 px-2 py-1 rounded text-[9px] font-black">REPLY</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                        <p class="text-center text-slate-600 text-xs py-4 italic">No comments yet.</p>
                                    <?php endif; ?>
                                </div>

                                <form action="<?php echo e(route('newsfeed.comment', $post->id)); ?>" method="POST" class="flex gap-2 shrink-0">
                                    <?php echo csrf_field(); ?>
                                    <input type="text" name="content" required placeholder="Add Comment..." class="w-full bg-white/5 border border-white/5 rounded px-4 py-2 text-xs text-white focus:ring-1 focus:ring-blue-500/50 outline-none">
                                    <button class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded text-[10px] font-black text-white">SEND</button>
                                </form>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-20 opacity-30 italic text-sm">No signals detected.</div>
                    <?php endif; ?>
                </div>
            </div>

            
            <aside class="hidden lg:block lg:col-span-3 sticky top-24 h-fit space-y-6">
                <div class="glass-panel rounded p-6 shadow-xl">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-500">Notifications</h3>
                        <span class="flex h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                    </div>

                    <div class="space-y-3 max-h-[40vh] overflow-y-auto hide-scrollbar">
                        <?php $__empty_1 = true; $__currentLoopData = auth()->user()->notifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $notifData = $notification->data;
                                $type = $notifData['type'] ?? 'system';
                                $postId = $notifData['post_id'] ?? null;
                                $senderId = $notifData['sender_id'] ?? null;
                                $sender = $senderId ? \App\Models\User::find($senderId) : null;

                                //======================== Check if post actually exists in DB ========================//
                                $postExists = $postId ? \App\Models\Post::where('id', $postId)->exists() : true;

                                //======================== Determine URL or Action ========================//
                                $targetUrl = "#";
                                if ($type === 'follow' && $sender) {
                                    $targetUrl = route('profile.show', $sender->slug);
                                } elseif ($postExists) {
                                    $targetUrl = route('newsfeed', ['open_comment' => $postId]);
                                }
                            ?>

                            <?php if(!$postExists && ($type === 'like' || $type === 'comment')): ?>
                                
                                <div onclick="alert('This post has been deleted by the author.')" class="block group cursor-pointer">
                            <?php else: ?>
                                <a href="<?php echo e($targetUrl); ?>" class="block group">
                            <?php endif; ?>
                            <div class="flex gap-3 transition <?php echo e($notification->read_at ? 'opacity-50' : 'hover:bg-white/5'); ?> p-2 rounded-lg">
                                <?php if($sender): ?>
                                    <img src="<?php echo e($sender->profile_picture_url); ?>" class="h-8 w-8 rounded border border-white/10 object-cover">
                                <?php else: ?>
                                    <div class="h-8 w-8 bg-slate-800 flex items-center justify-center text-2xl">🛡️</div>
                                <?php endif; ?>

                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] leading-tight text-slate-300">
                                        <span class="font-bold text-white group-hover:text-blue-400">
                                            <?php echo e($notifData['sender_name'] ?? 'From System:'); ?>

                                        </span>
                                            <?php echo e($notifData['message'] ?? 'interacted.'); ?>

                                        <?php if(!$postExists): ?>
                                            <span class="text-red-400 text-[8px] block">(Post Deleted)</span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="text-[8px] text-slate-500 uppercase font-black mt-1">
                                        <?php echo e($notification->created_at->diffForHumans()); ?>

                                    </p>
                                </div>
                            </div>
                            <?php if(!$postExists && ($type === 'like' || $type === 'comment')): ?>
                                </div>  
                            <?php else: ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-center text-[10px] text-slate-600 uppercase font-bold py-4">All Quiet</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                
                <div class="glass-panel rounded p-6 shadow-xl">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-500 mb-6">Discover People</h3>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $suggestedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <img src="<?php echo e($sUser->profile_picture_url); ?>" class="h-9 w-9 rounded-full object-cover border border-white/10">
                                    <div class="flex flex-col min-w-0">
                                        <a href="<?php echo e(route('profile.show', $sUser->slug)); ?>" class="text-[11px] font-bold text-white truncate hover:text-blue-400"><?php echo e($sUser->name); ?></a>
                                        <p class="text-[9px] text-slate-500 truncate italic"><?php echo e($sUser->address ?? 'Potential Match'); ?></p>
                                    </div>
                                </div>
                                <form action="<?php echo e(route('follow.toggle', $sUser->slug)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button class="bg-blue-600/10 hover:bg-blue-600 text-blue-500 hover:text-white px-3 py-1.5 rounded text-[9px] font-black uppercase border border-blue-500/20 transition">Follow</button>
                                </form>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </aside>
        </div>
    </main>
    
    <?php if(!auth()->user()->company): ?> 
        <div 
            x-show="applyModalOpen" 
            class="fixed inset-0 z-[60] overflow-y-auto" 
            style="display: none;"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                
                <div 
                    @click.away="applyModalOpen = false" 
                    x-show="applyModalOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="relative bg-gray-900 border border-white/10 text-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl"
                >
                    
                    
                    <div class="p-6 border-b border-white/5 bg-gray-800/50 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-black uppercase tracking-tighter">OJT Application Form</h2>
                            <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mt-1">
                                Applying to: <span x-text="activeCompanyName" class="text-white"></span>
                            </p>
                        </div>
                        <button @click="applyModalOpen = false" class="text-gray-500 hover:text-white transition">✕</button>
                    </div>

                    
                    <form action="<?php echo e(route('applications.store')); ?>" method="POST" class="p-6 space-y-4">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="post_id" :value="activePostId">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Full Name</label>
                                <input type="text" value="<?php echo e(auth()->user()->name); ?>" readonly class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-gray-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Email Address</label>
                                <input type="email" value="<?php echo e(auth()->user()->email ?? 'N/A'); ?>" readonly class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-gray-400 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Address</label>
                            <input type="address" value="<?php echo e(auth()->user()->address ?? 'N/A'); ?>" readonly class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-gray-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1 block">Why should we hire you?</label>
                            <textarea name="message" rows="4" required placeholder="Briefly introduce yourself and your skills..." 
                                class="w-full bg-gray-800 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition resize-none text-white"></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" @click="applyModalOpen = false" class="px-6 py-2 text-xs font-bold text-gray-400 hover:text-white transition">Cancel</button>
                            <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-500/20 transition-all">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<script>
//======================== Geolocation & Reverse Geocoding Logic ========================//
    document.addEventListener('DOMContentLoaded', function() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(async (position) => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            const accuracy = Math.round(position.coords.accuracy);
            //======================== Reverse Geocoding ========================//
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=10`);
                const data = await response.json();
                //======================== Get the most relevant city/town name ========================//
                const city = data.address.city || data.address.town || data.address.village || data.address.province;
                if (city) {
                    const geoPanel = document.getElementById('geo-recommendation');
                    const geoLink = document.getElementById('geo-link');
                    const geoText = document.getElementById('geo-city-name');
                    const geoAcc = document.getElementById('geo-accuracy');
					if(geoText) {
                        geoText.innerText = city;
                        geoAcc.innerText = accuracy;
                        //======================== Properly encode the URL for Laravel's Request ========================//
                        geoLink.href = `<?php echo e(route('newsfeed')); ?>?location=${encodeURIComponent(city)}`;
                        //======================== Show the panel ========================//
                        geoPanel.classList.remove('hidden');
                        }
                    }
                } catch (error) {
                    console.error("Location resolution failed:", error);
                }
            }, (error) => {
                console.warn("Location access denied by user.");
            });
        }
    });
//======================== Modal Logic ========================//
    function openCommentModal(postId) {
        document.getElementById('commentModal-' + postId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
//======================== Close Modal Logic ========================//
    function closeCommentModal(postId) {
        document.getElementById('commentModal-' + postId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
//======================== Toggle Reply Form Logic ========================//
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        form.classList.toggle('hidden');
    }
//======================== Notification Auto-Open Logic ========================//
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const openId = urlParams.get('open_comment');
        if (openId) {
            openCommentModal(openId);
        }
    };
//======================== Image Preview ========================//
    const imageInput = document.getElementById('postImageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImg = document.getElementById('imagePreview');
    const removeBtn = document.getElementById('removeImage');

    if (imageInput && previewContainer && previewImg) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
    if (removeBtn && imageInput && previewContainer) {
        removeBtn.addEventListener('click', () => {
            imageInput.value = '';
            previewContainer.classList.add('hidden');
        });
    }
//======================== Share Link Logic ========================//
    function copyPostLink(url, btn) {
        navigator.clipboard.writeText(url);
        const original = btn.innerHTML;
        btn.innerHTML = '<span class="text-emerald-500">✅ Copied!</span>';
        setTimeout(() => btn.innerHTML = original, 2000);
    }
    </script>
</body>
</html><?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/pages/newsfeed.blade.php ENDPATH**/ ?>