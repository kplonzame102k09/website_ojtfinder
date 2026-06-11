<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ojtFinder | Notifications</title>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZEMJ5KJY75"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZEMJ5KJY75');
</script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e('public/of_logo.png'); ?>?v=1">
    <style>
        .bg-main { background: radial-gradient(circle at top right, #070707, #0f172a); background-attachment: fixed; }
        .map-overlay { position: fixed; inset: 0; background-image: radial-gradient(rgba(59, 130, 246, 0.08) 1px, transparent 1px); background-size: 50px 50px; pointer-events: none; z-index: 0; }
        .marker { position: absolute; width: 6px; height: 6px; background: #3b82f6; border-radius: 50%; filter: blur(1px); opacity: 0.4; }
        .marker::after { content: ''; position: absolute; inset: -8px; border: 1px solid #3b82f6; border-radius: 50%; animation: pulse 4s infinite; }
        @keyframes pulse { 0% { transform: scale(0.5); opacity: 0.5; } 100% { transform: scale(3); opacity: 0; } }
        .glass-card { background: rgba(20, 14, 14, 0.747); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-main text-slate-200 antialiased font-sans relative">

    <div class="map-overlay">
        <div class="marker" style="top: 15%; left: 80%;"></div>
        <div class="marker" style="top: 75%; left: 10%; animation-delay: 1.5s;"></div>
    </div>

    <?php echo $__env->make('partial.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="relative z-10 max-w-2xl mx-auto mt-24 px-4 pb-20">
        
        <div class="flex items-end justify-between mb-10 pb-6 border-b border-white/10">
            <div>
                <h1 class="text-4xl font-black text-white tracking-tighter uppercase">Notifications</h1>
            </div>
            
            <?php if($notifications->where('read_at', null)->count() > 0): ?>
                <form action="<?php echo e(route('notifications.markAllAsRead')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-blue-400 transition">
                        Clear All Unread
                    </button>
                </form>
            <?php endif; ?>
        </div>
        
        <div class="space-y-3">
        
          <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $notifData = $notification->data;
                $type = $notifData['type'] ?? 'system';
                $postId = $notifData['post_id'] ?? null;
                $isReply = str_contains($notifData['message'] ?? '', 'replied');
                
                //======================= Resolve Sender =======================//
                $senderId = $notifData['sender_id'] ?? null;
                $sender = $senderId ? \App\Models\User::find($senderId) : null;
                $isSystem = !$senderId || ($notifData['sender_name'] ?? '') === 'From System:';

                //======================= Check if post exists (only for post-related notifications) =======================//
                $isPostRelated = in_array($type, ['like', 'comment']) || $isReply;
                $postExists = true;
                if ($isPostRelated && $postId) {
                    $postExists = \App\Models\Post::where('id', $postId)->exists();
                }
            ?>

            
            <?php if(!$postExists): ?>
                <div onclick="alert('This post has been deleted and is no longer available.')" class="block no-underline group cursor-pointer">
            <?php else: ?>
                <a href="<?php echo e(route('notifications.readAndRedirect', $notification->id)); ?>" class="block no-underline group">
            <?php endif; ?>
                
                <div class="glass-card p-4 rounded flex gap-5 transition-all duration-300 animate-fadeIn <?php echo e($notification->read_at ? 'opacity-50 grayscale-[0.3]' : 'ring-1 ring-blue-500/30 bg-blue-500/5'); ?> hover:bg-white/5 hover:scale-[1.01] active:scale-95">
                    
                    
                    <div class="flex-shrink-0 relative">
                        <div class="h-16 w-16 rounded overflow-hidden border border-white/10 glass-card">
                            <?php if($sender): ?>
                                <img src="<?php echo e($sender->profile_picture_url); ?>" class="h-full w-full object-cover" alt="Avatar">
                            <?php else: ?>
                                <div class="h-full w-full bg-slate-800 flex items-center justify-center text-2xl">🛡️</div>
                            <?php endif; ?>
                        </div>

                        
                        <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-slate-900 border border-white/10 flex items-center justify-center text-[12px] shadow-xl z-10">
                            <?php if($isReply): ?> ↪️ 
                            <?php elseif($type === 'follow'): ?> 👤 
                            <?php elseif($type === 'like'): ?> 👍 
                            <?php elseif($type === 'comment'): ?> 💬 
                            <?php elseif($type === 'message'): ?> 📩 
                            <?php else: ?> 📢 <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between items-start mt-1">
                            <div>
                                <p class="text-sm font-medium text-slate-300">
                                    <span class="font-black text-white group-hover:text-blue-400 transition">
                                        <?php echo e($notifData['sender_name'] ?? 'From System:'); ?>

                                    </span>
                                    <?php echo e($notifData['message'] ?? 'sent an update.'); ?>

                                    
                                    
                                    <?php if(!$postExists): ?>
                                        <span class="text-red-500 text-[10px] font-bold block mt-1 underline">POST DELETED</span>
                                    <?php endif; ?>
                                </p>
                                
                                <?php if(isset($notifData['comment_body'])): ?>
                                    <p class="text-[11px] text-slate-500 mt-1 italic border-l border-white/20 pl-2">
                                        "<?php echo e(Str::limit($notifData['comment_body'], 50)); ?>"
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <span class="text-[9px] font-bold uppercase tracking-tighter text-slate-600 whitespace-nowrap ml-2">
                                <?php echo e($notification->created_at->diffForHumans(null, true)); ?>

                            </span>
                        </div>
                        
                        <?php if (! ($notification->read_at)): ?>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="h-1 w-1 rounded-full bg-blue-500 animate-pulse"></span>
                                <span class="text-[8px] font-black uppercase tracking-widest text-blue-500">Unread Activity</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php if(!$postExists): ?>
                </div>
            <?php else: ?>
                </a>
            <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="glass-card rounded text-center py-32 border-dashed flex flex-col items-center">
                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4 text-2xl opacity-20">🛡️</div>
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest">No notifications yet.</p>
            </div>
        <?php endif; ?>
        </div>

        
        <?php if(session('success')): ?>
        <div id="notifSuccessModal" class="fixed inset-0 flex items-center justify-center bg-[#0f172a]/90 backdrop-blur-md z-[100] p-6">
            <div class="glass-card rounded-2xl max-w-sm w-full p-8 text-center shadow-2xl border-white/10">
                <div class="w-12 h-12 bg-blue-600/20 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">✓</div>
                <h3 class="text-lg font-black text-white uppercase tracking-tighter mb-2">Read All Notification</h3>
                <p class="text-slate-400 text-xs mb-6"><?php echo e(session('success')); ?></p>
                <button onclick="document.getElementById('notifSuccessModal').remove()" class="bg-blue-600 hover:bg-blue-500 text-white font-black py-3 rounded-xl w-full transition tracking-widest uppercase text-[10px]">
                   <- Back
                </button>
            </div>
        </div>
        <?php endif; ?>
    </main>
</body>
</html><?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/pages/notification.blade.php ENDPATH**/ ?>