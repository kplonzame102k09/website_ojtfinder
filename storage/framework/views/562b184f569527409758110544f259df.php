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
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        .bg-main { background: radial-gradient(circle at top right, #070707, #0f172a); background-attachment: fixed; }
        .map-overlay { position: fixed; inset: 0; background-image: radial-gradient(rgba(59, 130, 246, 0.08) 1px, transparent 1px); background-size: 50px 50px; pointer-events: none; z-index: 0; }
        .marker { position: absolute; width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; filter: blur(1px); opacity: 0.4; }
        .marker::after { content: ''; position: absolute; inset: -8px; border: 1px solid #3b82f6; border-radius: 50%; animation: pulse 4s infinite; }
        @keyframes pulse { 0% { transform: scale(0.5); opacity: 0.5; } 100% { transform: scale(3); opacity: 0; } }
        .glass-card { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); }
        .tab-active { border-bottom: 2px solid #2563eb; color: white; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e('public/of_logo.png'); ?>?v=1">
</head>
<body class="bg-main text-slate-200 antialiased font-sans relative min-h-screen">

    <div class="map-overlay">
        <div class="marker" style="top: 25%; left: 15%;"></div>
        <div class="marker" style="top: 65%; left: 85%; animation-delay: 2s;"></div>
    </div>

    <?php echo $__env->make('partial.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="relative z-10 pb-12">
        
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
                        <?php if($user->fresh()->isOnline()): ?>
                            <span class="absolute bottom-2 right-2 h-8 w-8 bg-green-500 border-2 border-[#0f172a] rounded-full"></span>
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
                        <a href="<?php echo e(route('messages.show', $user->slug)); ?>" class="glass-card px-6 py-2.5 rounded-xl font-bold hover:bg-white/10 transition">Message</a>
                        <form action="<?php echo e(route('follow.toggle', $user->slug)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-8 py-2.5 rounded font-bold transition-all <?php echo e(auth()->user()->isFollowing($user) ? 'bg-slate-800 text-white' : 'bg-blue-600 text-white hover:bg-blue-500 shadow-lg shadow-blue-900/20'); ?>">
                                <?php echo e(auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow'); ?>

                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('settings.edit')); ?>" class="px-6 py-2.5 rounded-full font-bold hover:bg-white/10 transition">
                        	<svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                          </svg>
        
                        </a>
                    <?php endif; ?>
                    
                    <div class="lg:hidden">
                        <button onclick="toggleMobileSidebar()" class="w-full glass-card p-2 rounded flex items-center justify-between border border-white/10 active:scale-[0.98] transition-all">
                            <div class="flex items-center gap-3">
                                <div class="h-6 w-6 bg-blue-600/20 flex items-center justify-center text-blue-500">
                                    👤
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-500">Profile Details</p>
                                    <p class="text-xs text-slate-400">View Bio & Company Info</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8 items-start">
                                                            
                
                <div class="hidden lg:block lg:col-span-4 relative lg:sticky lg:top-24 h-fit">
                    <?php if(auth()->user()->role === 'student' && auth()->id() === $user->id): ?>
                        
                        <div class="glass-card mt-6 rounded p-6 border border-white/10 mb-5">
                            <h3 class="text-[10px] uppercase tracking-widest text-blue-500 font-black mb-4">
                                Requirements Upload
                            </h3>

                            
                            <?php if($errors->any()): ?>
                                <div class="mb-4 bg-red-600/20 border border-red-500 rounded p-3 text-[10px] text-red-200">
                                    <ul class="list-disc list-inside">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            
                            <?php if(session('success')): ?>
                                <div class="mb-4 bg-green-600/20 border border-green-500 rounded p-3 text-[10px] text-green-200">
                                    <?php echo e(session('success')); ?>

                                </div>
                            <?php endif; ?>

                            <form action="<?php echo e(route('student.requirements.upload')); ?>" 
                                method="POST" 
                                enctype="multipart/form-data"
                                class="space-y-4">
                                <?php echo csrf_field(); ?>

                                <select name="type" required
                                        class="w-full border border-white/10 text-slate-500 rounded px-3 py-2 text-xs">
                                    <option value="">Select Requirement</option>
                                    <option value="resume" <?php echo e(old('type') == 'resume' ? 'selected' : ''); ?>>Resume / CV</option>
                                    <option value="school_id" <?php echo e(old('type') == 'school_id' ? 'selected' : ''); ?>>School Id</option>
                                    <option value="endorsement" <?php echo e(old('type') == 'endorsement' ? 'selected' : ''); ?>>Endorsement Letter</option>
                                    <option value="request_letter" <?php echo e(old('type') == 'request_letter' ? 'selected' : ''); ?>>Request Letter</option>
                                    <option value="application_letter" <?php echo e(old('type') == 'application_letter' ? 'selected' : ''); ?>>Application Letter</option>
                                </select>

                                <input type="file" 
                                    name="file" 
                                    required
                                    accept=".pdf, .jpg, .jpeg, .png"
                                    class="w-full text-xs text-slate-300
                                            file:bg-blue-600/20 file:border-0
                                            file:text-blue-400 file:px-4 file:py-2
                                            file:rounded">

                                <button type="submit" 
                                        class="w-full bg-blue-600 hover:bg-blue-500 py-2 rounded text-[10px] font-black uppercase">
                                    Upload Requirement
                                </button>
                            </form>
                        </div>

                        
                        <?php if($user->requirements->count()): ?>
                            <div class="glass-card mt-4 rounded p-5 border border-white/10 mb-5">
                                <h3 class="text-[10px] uppercase tracking-widest text-green-400 font-black mb-4">
                                    Uploaded Requirements
                                </h3>

                                <div class="space-y-3">
                                    <?php $__currentLoopData = $user->requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requirement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between bg-white/5 px-4 py-3 rounded border border-white/10">
                                            <div>
                                                <p class="text-xs font-semibold text-white">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $requirement->type))); ?>

                                                </p>
                                                <p class="text-[10px] text-slate-400 truncate max-w-[200px]">
                                                    <?php echo e($requirement->original_name); ?>

                                                </p>
                                            </div>

                                            <a href="<?php echo e(route('student.requirements.download', $requirement)); ?>"
                                            class="text-[10px] uppercase font-black px-3 py-1 rounded
                                                    bg-blue-600/20 text-blue-400 hover:bg-blue-600/30">
                                                Download
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-xs text-slate-400 mt-4">
                                No requirements uploaded yet.
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>

                    
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
                                     class="h-full w-full object-contain" 
                                     alt="<?php echo e($user->company->company_name); ?> Logo">
                                <?php else: ?>
                                    <span class="text-xl font-black text-blue-500">
                                        <?php echo e(substr($user->company->company_name ?? 'C', 0, 1)); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="text-white font-bold leading-tight"><?php echo e($user->company->company_name ?? 'No Company Linked'); ?></h4>
                                <p class="text-[10px] text-slate-500 font-medium tracking-tight">
                                    <?php echo e($user->company->industry ?? 'Industry Partner'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="space-y-3 text-[12px] text-slate-400 border-t border-white/5 pt-4">
                            <div>
                              	<span class="block text-[10px] text-gray-500 uppercase font-black tracking-widest mb-2">About</span>
                               	<p class="text-slate-200 text-xs leading-relaxed"><?php echo e($company->about); ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>🌐</span> <span class="truncate"><?php echo e($user->company->email); ?></span> 
                            </div>
                            <div class="flex items-center gap-2">
                                <span>🏢</span> <span class="truncate"><?php echo e($user->company->address ?? 'Main Office'); ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>📞</span> <span class="truncate"><?php echo e($user->company->contact_number ?? 'None'); ?></span>
                            </div>
                        </div>

                        <a href="<?php echo e(route('company_dashboard')); ?>" class="mt-5 block w-full py-2.5 rounded bg-white/5 hover:bg-white/10 text-center text-[9px] font-black uppercase tracking-widest transition border border-white/5 text-slate-400 hover:text-white">
                            View Company Deck
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="lg:col-span-8 space-y-6">
                    <?php if(!auth()->user()->company && $appliedPosts->count() > 0): ?>
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-4">
                                <h3 class="text-[10px] uppercase tracking-[0.2em] text-blue-500 font-black whitespace-nowrap">My OJT Applications</h3>
                                <div class="h-px w-full bg-gradient-to-r from-blue-500/50 to-transparent"></div>
                            </div>

                            <div class="space-y-4">
                                <?php $__currentLoopData = $appliedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <article class="glass-card rounded border border-white/5 overflow-hidden transition-all hover:border-blue-500/30">
                                        <div class="p-5">
                                            <div class="flex justify-between items-start mb-4">
                                                <div class="flex items-center gap-3">
                                                    
                                                    <a href="<?php echo e(route('profile.show', $app->post->user->slug)); ?>">
                                                        <img src="<?php echo e($app->post->user->profile_picture ? route('image.display', ['path' => $app->post->user->profile_picture]) : 'https://ui-avatars.com/api/?name='.urlencode($app->post->user->name)); ?>" 
                                                            class="h-10 w-10 rounded-xl object-cover border border-white/10 hover:opacity-80 transition">
                                                    </a>
                                                    <div>
                                                        <h4 class="text-sm font-bold text-white uppercase tracking-tight">
                                                            
                                                            <a href="<?php echo e(route('profile.show', $app->post->user->slug)); ?>" class="hover:text-blue-400 transition">
                                                                <?php echo e($app->post->user->company->company_name ?? $app->post->user->name); ?>

                                                            </a>
                                                        </h4>
                                                        <p class="text-[10px] text-slate-500 font-medium">Applied <?php echo e($app->created_at->diffForHumans()); ?></p>
                                                    </div>
                                                </div>

                                                
                                                <div class="flex flex-col items-end">
                                                    <span class="text-[9px] font-black uppercase px-3 py-1 rounded-full tracking-widest
                                                        <?php echo e($app->status === 'accepted' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/20' : 'bg-100-500/20 text-red-400 border border-blue-500/20'); ?>">
                                                        <?php echo e($app->status); ?>

                                                    </span>
                                                </div>
                                            </div>

                                            
                                            <div class="bg-white/[0.02] rounded-xl p-4 border border-white/5 mb-4">
                                                <p class="text-xs text-slate-300 leading-relaxed italic">
                                                    "<?php echo e($app->post->content); ?>"
                                                </p>
                                            </div>

                                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-white/5">
                                                <div class="flex gap-4">
                                                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">
                                                        <?php echo e($app->post->likes->count()); ?> Likes
                                                    </span>
                                                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">
                                                        <?php echo e($app->post->comments->count()); ?> Comments
                                                    </span>
                                                </div>

                                                
                                                <a href="<?php echo e(route('newsfeed', $app->post_id)); ?>" class="text-[10px] font-black text-blue-400 hover:text-white transition uppercase tracking-widest">
                                                    View Full Post →
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article id="post-<?php echo e($post->id); ?>" class="glass-card rounded p-6 space-y-4 hover:border-blue-500/20 transition-all">
                        <div class="flex justify-between items-start">
                            <div class="flex gap-3">
                            <img src="<?php echo e($post->user->profile_picture ? route('image.display', ['path' => $post->user->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&background=random'); ?>" 
    							 class="h-10 w-10 rounded-full object-cover bg-slate-800">
                                
                                <div>
                                    <h4 class="font-bold text-white text-sm"><?php echo e($post->user->name); ?></h4>
                                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-wider">
                                        <?php echo e($post->created_at->diffForHumans()); ?>

                                    </p>
                                </div>
                            </div>
                                 <?php if(auth()->id() === $post->user_id): ?>
                                    <form action="<?php echo e(route('newsfeed.destroy', $post->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-slate-500 hover:text-red-500 transition-colors p-1" title="Delete Post">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                        </div>

                        <p class="text-sm text-slate-300 leading-relaxed"><?php echo e($post->content); ?></p>
                        <?php if($post->image): ?>
                            <div class="mt-4 overflow-hidden rounded-xl border border-white/5 shadow-inner bg-slate-900/40">
                            <img src="<?php echo e(route('image.display', ['path' => $post->image])); ?>" 
                                 class="w-full h-auto object-cover max-h-[500px] hover:scale-[1.02] transition-transform duration-500"
                                 loading="lazy">
                            </div>
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

                                <div class="relative">
                                    <button onclick="copyPostLink('<?php echo e(route('profile.show', $post->user->slug) . '#post-' . $post->id); ?>', this)" 
                                            class="flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-white transition">
                                        <span class="text-sm">🔗</span> Share
                                    </button>
                                </div>
                            </div>
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
                                       <img src="<?php echo e($comment->user->profile_picture ? route('image.display', ['path' => $comment->user->profile_picture]) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&background=random'); ?>" 
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
                                    <input type="text" name="content" required placeholder="Write a comment..." class="w-full bg-white/5 border border-white/5 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:ring-1 focus:ring-blue-500/50">
                                    <button class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded text-[10px] font-black transition">SEND</button>
                                </form>
                            </div>
                            
                        </div>
                    </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-20 border border-dashed border-white/10 rounded">
                        <p class="text-slate-500 font-medium italic">Only Users with Registered Company can Post.</p>
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
                                    <span class="text-xl font-black text-blue-500">
                                        <?php echo e(substr($user->company->company_name ?? 'C', 0, 1)); ?>

                                    </span>
                                <?php endif; ?>
                              </div>
                            <div>
                                <h4 class="text-white font-bold leading-tight">
                                    <?php echo e($user->company->company_name ?? 'Freelancer'); ?>

                                </h4>
                                <p class="text-[10px] text-slate-500 font-medium tracking-tight">
                                    <?php echo e($user->company->industry ?? 'Industry Partner'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="space-y-3 text-[12px] text-slate-400 border-t border-white/5 pt-4">
                            <div>
                                <span class="block text-[10px] text-gray-500 uppercase font-black tracking-widest mb-2">About</span>
                              	<p class="text-slate-200 text-xs leading-relaxed"><?php echo e($company->about); ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>🌐</span> <span class="truncate"><?php echo e($user->company->email); ?></span> 
                            </div>
                            <div class="flex items-center gap-2">
                                <span>🏢</span> <span class="truncate"><?php echo e($user->company->address ?? 'Main Office'); ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>📞</span> <span class="truncate"><?php echo e($user->company->contact_number ?? 'None'); ?></span>
                            </div>
                        </div>

                        <a href="<?php echo e(route('company_dashboard')); ?>" class="mt-5 block w-full py-2.5 rounded bg-white/5 hover:bg-white/10 text-center text-[9px] font-black uppercase tracking-widest transition border border-white/5 text-slate-400 hover:text-white">
                            View Company Deck
                        </a>
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
                        
                        
                        <a href="<?php echo e(route('profile.show', $follower->slug ?? $follower->id)); ?>" 
                        class="text-[10px] font-black text-blue-500 hover:text-blue-400 uppercase tracking-widest border border-blue-500/20 px-3 py-1 rounded">
                            View
                        </a>
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
                        
                        
                        <a href="<?php echo e(route('profile.show', $following->slug ?? $following->id)); ?>" 
                        class="text-[10px] font-black text-blue-500 hover:text-blue-400 uppercase tracking-widest border border-blue-500/20 px-3 py-1 rounded">
                            View
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8">
                        <p class="text-slate-500 italic text-sm">No following yet in the system.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black/80 backdrop-blur-sm z-50">
        <div class="bg-slate-900 border border-white/10 rounded max-w-sm w-full p-8 text-center shadow-2xl">
            <div class="w-16 h-16 bg-blue-600/20 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">✓</div>
            <h3 class="text-xl font-bold text-white mb-2">Success</h3>
            <p class="text-slate-400 mb-6 text-sm"><?php echo e(session('success')); ?></p>
            <button onclick="document.getElementById('successModal').remove()" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 w-full transition">Got it</button>
        </div>
    </div>
    <?php endif; ?>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = 'auto'; }
    function openCommentModal(id) { document.getElementById(`commentModal-${id}`).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeCommentModal(id) { document.getElementById(`commentModal-${id}`).classList.add('hidden'); document.body.style.overflow = 'auto'; }
    //======================= Global Escape Key Listener for Modals =======================    
    document.addEventListener('keydown', (e) => {
        if (e.key === "Escape") {
            document.querySelectorAll('[id^="commentModal-"], [id$="Modal"]').forEach(m => m.classList.add('hidden'));
            document.body.style.overflow = 'auto';
        }
    });
    //======================= Mobile Sidebar Toggle =======================//
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
    //======================= Copy Post Link Function with Visual Feedback =======================//
    function copyPostLink(url, button) {
        const tempInput = document.createElement("input");
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
    //======================= Visual Feedback =======================//
        const originalContent = button.innerHTML;
        button.innerHTML = '<span class="text-sm">✅</span> Copied!';
        button.classList.remove('text-slate-500');
        button.classList.add('text-green-400');
    //======================= Reset after 2 seconds =======================//
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('text-green-400');
            button.classList.add('text-slate-500');
        }, 2000);
    }
        
    </script>
</body>
</html><?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/pages/profile.blade.php ENDPATH**/ ?>