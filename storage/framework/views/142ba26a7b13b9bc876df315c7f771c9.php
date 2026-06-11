<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ojtFinder | <?php echo e($user->name); ?></title>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZEMJ5KJY75"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZEMJ5KJY75');
</script>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e('/public/of_logo.png'); ?>?v=1">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        .bg-main { background: radial-gradient(circle at top right, #070707, #0f172a); background-attachment: fixed; }
        .map-overlay { position: fixed; inset: 0; background-image: radial-gradient(rgba(59, 130, 246, 0.08) 1px, transparent 1px); background-size: 50px 50px; pointer-events: none; z-index: 0; }
        .marker { position: absolute; width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; filter: blur(1px); opacity: 0.4; }
        .marker::after { content: ''; position: absolute; inset: -8px; border: 1px solid #3b82f6; border-radius: 50%; animation: pulse 4s infinite; }
        @keyframes pulse { 0% { transform: scale(0.5); opacity: 0.5; } 100% { transform: scale(3); opacity: 0; } }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
          @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px);} to { opacity: 1; transform: translateY(0);} }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-main text-slate-200 antialiased font-sans relative min-h-screen">
    <div class="map-overlay">
        <div class="marker" style="top: 25%; left: 15%;"></div>
        <div class="marker" style="top: 65%; left: 85%; animation-delay: 2s;"></div>
    </div>

    <?php echo $__env->make('partial.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="load-link relative z-10 pb-12">
        
        <div class="relative h-80 bg-slate-900/50 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" 
                 class="h-full w-full object-cover opacity-60" alt="Cover Photo">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] to-transparent"></div>
        </div>
        
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative -mt-24 flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-white/10">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6 text-center md:text-left">
                    <div class="relative group">
                        <img src="<?php echo e($user->profile_picture ? route('image.display', ['path' => $user->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1e293b&color=3b82f6'); ?>" 
                             class="h-40 w-40 rounded-full border-4 border-[#0f172a] object-cover shadow-2xl">
                        
                        <?php if($user->last_seen_at && $user->last_seen_at->diffInMinutes() <= 2): ?>
                            <span class="absolute bottom-2 right-2 w-8 h-8 bg-green-500 border-2 border-[#0b0f1a] rounded-full shadow-[0_0_10px_rgba(34,197,94,0.5)]">
                                <span class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-75"></span>
                            </span>
                        
                        <?php elseif($user->last_seen_at && $user->last_seen_at->diffInMinutes() <= 10): ?>
                            <span class="absolute bottom-2 right-2 w-8 h-8 bg-yellow-500 border-2 border-[#0b0f1a] rounded-full"></span>
                        
                        <?php else: ?>
                            <span class="absolute bottom-2 right-2 w-8 h-8 bg-gray-500 border-2 border-[#0b0f1a] rounded-full"></span>
                        <?php endif; ?>
                    </div>

                    <div class="pb-2 space-y-2">
                    
                        <h1 class="text-4xl font-black text-white tracking-tight"><?php echo e($user->name); ?></h1>
                        <div class="flex items-center justify-center md:justify-start gap-4 text-sm text-slate-400">
                            <button onclick="openModal('followersModal')" class="hover:text-blue-400 transition">
                                <strong class="text-white"><?php echo e($user->followers?->count() ?? 0); ?></strong> Followers
                            </button>
                            <span class="text-slate-700">•</span>
                            <button onclick="openModal('followingModal')" class="hover:text-blue-400 transition">
                                <strong class="text-white"><?php echo e($user->following?->count() ?? 0); ?></strong> Following
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 pb-2">
                    <?php if(auth()->id() !== $user->id): ?>
                        <a href="<?php echo e(route('messages.show', $user->slug)); ?>" class="glass-card px-6 py-2.5 rounded font-bold hover:bg-white/10 transition text-sm">Message</a>
                        <form action="<?php echo e(route('follow.toggle', $user->slug)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="cursor-pointer px-8 py-2.5 rounded font-bold transition-all text-sm <?php echo e(auth()->user()->isFollowing($user) ? 'bg-slate-800 text-slate-400' : 'bg-blue-600 text-white hover:bg-blue-500 shadow-lg shadow-blue-900/20'); ?>">
                                <?php echo e(auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow'); ?>

                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('edit')); ?>" class="glass-card px-6 py-2.5 rounded font-bold hover:bg-white/10 transition text-sm">Edit Profile</a>
                    <?php endif; ?>
                    
                    <div class="lg:hidden">
                        <button onclick="toggleMobileSidebar()" class="w-full glass-card p-1 rounded flex items-center justify-between border border-white/10 active:scale-[0.98] transition-all">
                            <div class="flex items-center gap-3">
                                <div class="h-6 w-6 bg-blue-600/20 flex items-center justify-center text-blue-500">
                                    👤
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-500">See Details</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8 items-start">
                
                <div class="hidden lg:block lg:col-span-4 space-y-6 sticky top-24">
                    
                    <div class="glass-card rounded p-6 shadow-xl">
                        <h3 class="text-[10px] uppercase tracking-[0.2em] text-blue-500 font-black mb-4">Intro</h3>
                        <div class="space-y-4 text-sm">
                            <p class="text-slate-300 leading-relaxed italic">"<?php echo e($user->bio ?? 'Looking for opportunities...'); ?>"</p>
                            <div class="space-y-3 border-t border-white/5 pt-4">
                                <div class="flex items-center gap-3 text-slate-400 text-xs">
                                    <span class="opacity-50">📧</span> <?php echo e($user->email); ?>

                                </div>
                                <?php if($user->contact_number): ?>
                                <div class="flex items-center gap-3 text-slate-400 text-xs">
                                    <span class="opacity-50">📞</span> <?php echo e($user->contact_number); ?>

                                </div>
                                <?php endif; ?>
                                <?php if($user->address): ?>
                                <div class="flex items-center gap-3 text-slate-400 text-xs">
                                    <span class="opacity-50">📍</span> <?php echo e($user->address); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($user->company): ?>
                        <div class="glass-card rounded p-6 border-l-2 border-l-blue-600 shadow-xl group">
                            
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-[10px] uppercase tracking-[0.2em] text-blue-500 font-black">Verified</h3>
                                <span class="bg-blue-500/10 text-blue-400 text-[8px] px-2 py-0.5 rounded-full font-black uppercase tracking-tighter">Official Partner</span>
                            </div>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <div class="h-12 w-12 rounded bg-white/5 border border-white/10 flex items-center justify-center p-2">
                                    <?php if($user->company && $user->company->company_logo): ?>
                                        <img src="<?php echo e(route('image.display', ['path' => $user->company->company_logo])); ?>" 
                                            alt="<?php echo e($user->company->company_name); ?> Logo" 
                                            class="h-full w-full object-contain">
                                    <?php else: ?>
                                        <div class="h-full w-full flex items-center justify-center bg-slate-800 text-blue-500 font-black text-xl">
                                            <?php echo e(substr($user->company->company_name ?? 'C', 0, 1)); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="text-white font-bold leading-tight"><?php echo e($user->company->company_name); ?></h4>
                                    <p class="text-[10px] text-slate-500 font-medium tracking-tight"><?php echo e($user->company->industry ?? 'Industrial Partner'); ?></p>
                                </div>
                            </div>

                            
                            <div class="space-y-3 text-[11px] text-slate-400 border-t border-white/5 pt-5 mb-5">
                                <div class="flex items-center gap-3">
                                    <span class="opacity-40 text-sm">🌐</span> 
                                    <span class="truncate hover:text-white transition cursor-default" title="<?php echo e($user->company->email); ?>">
                                        <?php echo e($user->company->email); ?>

                                    </span>
                                </div>
        
                                <div class="flex items-center gap-3">
                                    <span class="opacity-40 text-sm">🏢</span> 
                                    <span class="leading-tight text-slate-300">
                                        <?php echo e($user->company->address ?? 'Headquarters not specified'); ?>

                                    </span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="opacity-40 text-sm">📞</span>
                                    <span class="font-mono tracking-wider">
                                        <?php echo e($user->company->contact_number ?? 'No contact registered'); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>                    
                
                <div class="lg:col-span-8 space-y-6">
                    <?php $__empty_1 = true; $__currentLoopData = $user->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    
                        <article id="post-<?php echo e($post->id); ?>" class="glass-card rounded p-6 space-y-4 hover:border-blue-500/20 transition-all duration-300">
                            
                            
                            <div class="flex justify-between items-start">
                                
                                <div class="flex gap-3">
                                    <img src="<?php echo e($user->profile_picture ? route('image.display', ['path' => $user->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1e293b&color=fff'); ?>" 
                                        class="h-10 w-10 rounded-full object-cover border border-white/10 bg-slate-800" 
                                        alt="<?php echo e($user->name); ?>">
                                    
                                    <div>
                                        <h4 class="font-bold text-white text-sm"><?php echo e($user->name); ?></h4>
                                        <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">
                                            <?php echo e($post->created_at->diffForHumans()); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-sm text-slate-300 leading-relaxed"><?php echo e($post->content); ?></p>
                            <?php if($post->image): ?>
                                <div class="mt-3 overflow-hidden rounded-xl border border-white/5 shadow-inner bg-slate-900/40">
                                    <img src="<?php echo e(route('image.display', ['path' => $post->image])); ?>" 
                                        class="w-full h-auto object-cover max-h-[500px] hover:scale-[1.01] transition-transform duration-500"
                                        alt="Post Content">
                                </div>
                            <?php endif; ?>
                            
                            <?php if(auth()->id() === $post->user_id && $post->applications->count() > 0): ?>
                                <div class="mt-6 pt-6 border-t border-white/5">
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="text-[9px] bg-blue-600 text-white px-2 py-0.5 rounded font-black uppercase tracking-widest">
                                            Applicants (<?php echo e($post->applications->count()); ?>)
                                        </span>
                                        <div class="h-px flex-1 bg-white/5"></div>
                                    </div>

                                    <div class="space-y-2">
                                        <?php $__currentLoopData = $post->applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center justify-between p-3 rounded-lg bg-white/[0.02] border border-white/5 group hover:bg-white/[0.04] transition-all">
                                                <div class="flex items-center gap-3">
                                                    <img src="<?php echo e($app->student->profile_picture ? route('image.display', ['path' => $app->student->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($app->student->name) . '&background=random'); ?>" 
                                                        class="h-8 w-8 rounded-full border border-white/10 object-cover">
                                                    <div>
                                                        <p class="text-[11px] font-bold text-white group-hover:text-blue-400 transition-colors">
                                                            <?php echo e($app->student->name); ?>

                                                        </p>
                                                        <p class="text-[8px] text-slate-500 uppercase tracking-tighter">
                                                            Applied <?php echo e($app->created_at->diffForHumans()); ?>

                                                        </p>
                                                    </div>
                                                </div>
                                                <a href="<?php echo e(route('profile.show', $app->student->slug)); ?>" 
                                                class="text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-white bg-white/5 px-3 py-1.5 rounded border border-white/5 transition">
                                                    Profile
                                                </a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex items-center gap-6 pt-4 border-t border-white/5">
                                <form action="<?php echo e(route('posts.like', $post->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button class="flex items-center gap-2 text-xs font-bold <?php echo e($post->likes->where('user_id', auth()->id())->count() ? 'text-blue-500' : 'text-slate-500 hover:text-white'); ?> transition">
                                        👍 <?php echo e($post->likes->count()); ?>

                                    </button>
                                </form>

                                <button onclick="openCommentModal(<?php echo e($post->id); ?>)" class="flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-white transition">
                                    💬 <?php echo e($post->comments->count()); ?>

                                </button>

                                <button onclick="copyPostLink('<?php echo e(route('profile.show', $post->user->slug) . '#post-' . $post->id); ?>', this)" 
                                        class="flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-white transition">
                                    🔗 Share
                                </button>
                            </div>
                            
                            <div id="commentModal-<?php echo e($post->id); ?>" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
                                <div class="absolute inset-0 bg-[#070707]/90 backdrop-blur-md" onclick="closeCommentModal(<?php echo e($post->id); ?>)"></div>
                                <div class="glass-card w-full max-w-lg rounded p-8 relative shadow-2xl">
                                    <div class="flex justify-between items-center mb-6">
                                        <h3 class="text-xl font-black text-white uppercase tracking-tighter">Feedback</h3>
                                        <button onclick="closeCommentModal(<?php echo e($post->id); ?>)" class="text-slate-500 hover:text-white transition">✕</button>
                                    </div>
                                    
                                    <div class="max-h-[300px] overflow-y-auto hide-scrollbar space-y-4 mb-6">
                                        <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex gap-3">
                                                <img src="<?php echo e($comment->user->profile_picture ? route('image.display', ['path' => $comment->user->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&background=1e293b&color=3b82f6'); ?>" 
                                                    class="h-8 w-8 rounded-full object-cover bg-slate-800 border border-white/10" 
                                                    alt="<?php echo e($comment->user->name); ?>">
                                                
                                                <div class="bg-white/5 rounded p-3 flex-1 border border-white/5">
                                                    <a href="<?php echo e(route('profile.show', $comment->user->slug)); ?>" class="text-[11px] font-bold text-blue-400 hover:underline">
                                                        <?php echo e($comment->user->name); ?>

                                                    </a>
                                                    <p class="text-xs text-slate-300 mt-1 leading-relaxed"><?php echo e($comment->content); ?></p>
                                                    <span class="text-[8px] text-slate-500 uppercase font-black mt-2 block">
                                                        <?php echo e($comment->created_at->diffForHumans()); ?>

                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <form action="<?php echo e(route('newsfeed.comment', $post->id)); ?>" method="POST" class="flex gap-2">
                                        <?php echo csrf_field(); ?>
                                        <input type="text" name="content" required placeholder="Write a comment..." 
                                            class="w-full bg-white/5 border border-white/5 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:ring-1 focus:ring-blue-500/50">
                                        <button class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded text-[10px] font-black transition">SEND</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center border border-dashed border-white/10 rounded">
                            <?php if($user->requirements->count()): ?>
                                <div class="glass-card rounded p-6 shadow-xl">
                                    <h3 class="text-[10px] uppercase tracking-[0.2em] text-blue-500 font-black mb-4">
                                        Uploaded Requirements
                                    </h3>

                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $user->requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requirement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $ext = strtolower(pathinfo($requirement->original_name, PATHINFO_EXTENSION));
                                            ?>

                                            <div class="bg-white/5 px-4 py-3 rounded border border-white/10">
                                                <p class="text-xs font-semibold text-white">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $requirement->type))); ?>

                                                </p>
                                                <p class="text-[10px] text-slate-400 mb-2"><?php echo e($requirement->original_name); ?></p>

                                                
                                                <?php if(in_array($ext, ['jpg','jpeg','png','gif','webp'])): ?>
                                                    <img src="<?php echo e(route('student.requirements.view', $requirement)); ?>" 
                                                        alt="<?php echo e($requirement->original_name); ?>" 
                                                        class="w-full max-w-[400px] rounded shadow-sm border border-white/10 mb-3"
                                                        onerror="this.onerror=null;this.src='<?php echo e(asset('images/file-fallback.png')); ?>';">

                                                
                                                <?php elseif($ext === 'pdf'): ?>
                                                    <iframe src="<?php echo e(route('student.requirements.view', $requirement)); ?>" 
                                                            class="w-full h-[550px] rounded border border-white/10 mb-3">
                                                        <p class="text-red-500 text-sm">
                                                            PDF cannot be displayed. <a href="<?php echo e(route('student.requirements.download', $requirement)); ?>">Download instead</a>.
                                                        </p>
                                                    </iframe>

                                                
                                                <?php else: ?>
                                                    <p class="text-slate-400 italic text-sm">
                                                        Cannot preview this file type. <a href="<?php echo e(route('student.requirements.download', $requirement)); ?>">Download</a>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                
                                <div class="flex flex-col items-center justify-center text-center py-10 border border-white/10 rounded bg-white/5">
                                    <img src="<?php echo e(asset('images/no-file-placeholder.png')); ?>" class="w-24 mb-4" alt="No files uploaded">
                                    <p class="text-slate-400 text-sm">No requirements uploaded yet.</p>
                                </div>
                            <?php endif; ?>

                            <p class="text-slate-500 font-medium italic">No posts found in this feed.</p>
                        </div>
                    <?php endif; ?>
                </div>                                        
            </div>        
        </div>
    </main>
    
    <div id="mobileSidebarModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-[#070707]/90 backdrop-blur-sm" onclick="toggleMobileSidebar()"></div>
        <div class="absolute right-0 top-0 bottom-0 w-[85%] max-w-sm glass-card border-l border-white/10 p-6 overflow-y-auto animate-fadeInRight">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-black text-white tracking-tighter">DETAILS</h2>
                <button onclick="toggleMobileSidebar()" class="text-slate-500 text-2xl">✕</button>
            </div>
            
            <div class="glass-card rounded p-6 shadow-xl border border-white/5">
                <h3 class="text-[10px] uppercase tracking-[0.2em] text-blue-500 font-black mb-4">Intro</h3>
                    <div class="space-y-4 text-sm">
                        <p class="text-slate-300 leading-relaxed italic">"<?php echo e($user->bio ?? 'Ready for the next big opportunity.'); ?>"</p>
                        <div class="space-y-3 border-t border-white/5 pt-4">
                            <div class="flex items-center gap-3 text-slate-400">
                                <span class="opacity-50 text-base">📧</span> <?php echo e($user->email); ?>

                            </div>
                            <div class="flex items-center gap-3 text-slate-400">
                                <span class="opacity-50 text-base">📞</span> <?php echo e($user->contact_number ?? 'Not provided'); ?>

                            </div>
                            <?php if($user->address): ?>
                                <div class="flex items-center gap-3 text-slate-400">
                                    <span class="opacity-50 text-base">📍</span> <?php echo e($user->address); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php if($user->company): ?>
                    <div class="glass-card mt-6 rounded p-6 border-l-2 border-l-blue-600 shadow-xl relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-600/10 blur-3xl rounded-full group-hover:bg-blue-600/20 transition-all duration-700"></div>
                        
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-[10px] uppercase tracking-[0.2em] text-blue-500 font-black">Official Partner</h3>
                            <span class="bg-blue-500/10 text-blue-400 text-[8px] px-2 py-0.5 rounded-full font-black uppercase tracking-tighter">Verified</span>
                        </div>

                        <div class="flex items-center gap-4 mb-4">
                            <div class="h-12 w-12 rounded bg-white/5 border border-white/10 flex items-center justify-center p-2">
                               
                               <?php if($user->company && $user->company->company_logo): ?>
                                    <img src="<?php echo e(route('image.display', ['path' => $user->company->company_logo])); ?>" 
                                         alt="<?php echo e($user->company->company_name); ?> Logo" 
                                         class="h-full w-full object-contain">
                                <?php else: ?>
                                    <div class="h-full w-full flex items-center justify-center bg-slate-800 text-blue-500 font-black text-xl">
                                        <?php echo e(substr($user->company->company_name ?? 'C', 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="text-white font-bold leading-tight"><?php echo e($user->company->company_name); ?></h4>
                                <p class="text-[10px] text-slate-500 font-medium tracking-tight"><?php echo e($user->company->industry ?? 'Industry Partner'); ?></p>
                            </div>
                        </div>

                        <div class="space-y-3 text-[12px] text-slate-400 border-t border-white/5 pt-4">
                            <div>
                                <span class="block text-[10px] text-gray-500 uppercase font-black tracking-widest mb-2">About</span>
                                <p class="text-slate-200 text-xs leading-relaxed"><?php echo e($user->company->about); ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>📧</span> <span class="truncate"><?php echo e($user->company->email); ?></span> 
                            </div>
                            <div class="flex items-center gap-2">
                                <span>📞</span> <span class="truncate"><?php echo e($user->company->contact_number ?? 'None'); ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>📍</span> <span class="truncate"><?php echo e($user->company->address ?? 'Main Office'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div id="followersModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closeModal('followersModal')"></div>
        <div class="glass-card w-full max-w-md rounded p-8 relative border border-white/10 shadow-2xl">
            <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-6 flex justify-between items-center">
                Followers 
                <span class="text-xs text-slate-500 font-medium"><?php echo e($user->followers->count()); ?></span>
            </h3>
            
            <div class="space-y-4 max-h-96 overflow-y-auto hide-scrollbar">
                <?php $__empty_1 = true; $__currentLoopData = $user->followers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $follower): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-2 hover:bg-white/5 rounded transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <img src="<?php echo e($follower->profile_picture ? route('image.display', ['path' => $follower->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($follower->name) . '&background=0f172a&color=fff'); ?>" 
                                 class="h-10 w-10 rounded-full object-cover border border-white/10 bg-slate-800"
                                 alt="<?php echo e($follower->name); ?>">
                            
                            <span class="font-bold text-sm text-slate-200"><?php echo e($follower->name); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8">
                        <p class="text-slate-500 italic text-sm">No followers yet in the system.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div id="followingModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closeModal('followingModal')"></div>
        <div class="glass-card w-full max-w-md rounded p-8 relative border border-white/10 shadow-2xl">
            <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-6 flex justify-between items-center">
                Following 
                <span class="text-xs text-slate-500 font-medium"><?php echo e($user->following->count()); ?></span>
            </h3>
          
            <div class="space-y-4 max-h-96 overflow-y-auto hide-scrollbar">
                <?php $__empty_1 = true; $__currentLoopData = $user->following; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $following): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-2 hover:bg-white/5 rounded transition-all duration-200">
                        <div class="flex items-center gap-3">
                           <img src="<?php echo e($following->profile_picture ? route('image.display', ['path' => $following->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($following->name) . '&background=0f172a&color=fff'); ?>" 
                                 class="h-10 w-10 rounded-full object-cover border border-white/10 bg-slate-800" alt="<?php echo e($following->name); ?>">
                            <span class="font-bold text-sm text-slate-200"><?php echo e($following->name); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8">
                        <p class="text-slate-500 italic text-sm">No following yet in the system.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<style>
    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(100%); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-fadeInRight { animation: fadeInRight 0.3s ease-out forwards; }
</style>

<script>
    //======================= Modal Logic =======================//
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = 'auto'; }
    function openCommentModal(id) { document.getElementById(`commentModal-${id}`).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeCommentModal(id) { document.getElementById(`commentModal-${id}`).classList.add('hidden'); document.body.style.overflow = 'auto'; }
    //======================= Mobile Sidebar Logic =======================//
    function toggleMobileSidebar() {
        const modal = document.getElementById('mobileSidebarModal');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; 
        }
    }
    //========================== Share Logic ==========================//
    function copyPostLink(url, button) {
        const tempInput = document.createElement("input");
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);

        const originalContent = button.innerHTML;
        button.innerHTML = '<span class="text-sm">✅</span> Copied!';
        button.classList.replace('text-slate-500', 'text-green-400');
        //========================== Revert button state after 2 seconds ==========================//
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.replace('text-green-400', 'text-slate-500');
        }, 2000);
    }

    </script>
</body>
</html><?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/profile/show.blade.php ENDPATH**/ ?>