<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ojtFinder | Messages</title>
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
    <style>
        .glass-sidebar { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(10px); }
        .chat-container { background: #0b0f1a; background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.02) 1px, transparent 0); background-size: 32px 32px; }
        .message-in { border-radius: 4px 18px 18px 18px; }
        .message-out { border-radius: 18px 18px 4px 18px; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
                /*===== Animated Pulsing Markers =====*/
        .marker {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #3b82f6;
            border-radius: 50%;
            filter: blur(1px);
        }

        .marker::after {
            content: '';
            position: absolute;
            inset: -8px;
            border: 1px solid #3b82f6;
            border-radius: 50%;
            animation: pulse 3s infinite;
            opacity: 0;
        }

        @keyframes pulse {
            0% { transform: scale(0.5); opacity: 0.8; }
            100% { transform: scale(2.5); opacity: 0; }
        }
    </style>
</head>
<body class="bg-[#0b0f1a] text-slate-200 h-screen flex overflow-hidden antialiased">
    <?php echo $__env->make('partial.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <aside class="w-full md:w-[350px] glass-sidebar border-r border-white/5 flex flex-col mt-16 <?php echo e(isset($chatWith) ? 'hidden md:flex' : 'flex'); ?>">
        <div class="p-6 border-b border-white/5">
            <h1 class="text-xl font-black text-white tracking-tight">Messages</h1>
            <div class="mt-4 relative">
                <input type="text" id="contactSearch" placeholder="Search chats..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <div class="flex-1 overflow-y-auto pt-2" id="contactsContainer">
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('messages.show', $contact->slug)); ?>" 
                class="contact-item load-link flex items-center px-6 py-4 hover:bg-white/[0.03] transition-all gap-4 border-b border-white/[0.02] <?php echo e(isset($chatWith) && $chatWith->id === $contact->id ? 'bg-white/[0.05] border-r-2 border-r-blue-500' : ''); ?>">
                    
                    <div class="relative">
                       <img src="<?php echo e($contact->profile_picture_url); ?>" class="w-12 h-12 rounded-full object-cover grayscale-[0.3]" alt="<?php echo e($contact->name); ?>">
        				<?php if($contact->isOnline()): ?>
                        	<span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#0b0f1a] rounded-full"></span>
        				<?php endif; ?>
                        
                        <?php if($contact->unread_count > 0): ?>
                            <span class="absolute -top-1 -right-1 bg-blue-600 w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-black border-2 border-[#0b0f1a]">
                                <?php echo e($contact->unread_count); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <p class="contact-name font-bold text-sm text-white truncate"><?php echo e($contact->name); ?></p>
                            <span class="text-[10px] text-slate-500 uppercase tracking-tighter"><?php echo e($contact->last_message_time); ?></span>
                        </div>
                        <p class="text-xs <?php echo e($contact->unread_count > 0 ? 'text-blue-400 font-bold' : 'text-slate-500'); ?> truncate mt-0.5">
                            <?php if($contact->last_message): ?>
                                <?php echo e($contact->last_message_sender); ?><?php echo e($contact->last_message); ?>

                            <?php elseif($contact->last_message_image): ?> 
                                <?php echo e($contact->last_message_sender); ?><span>📷 Sent a photo</span>
                            <?php elseif($contact->last_message_file): ?>
                                <?php echo e($contact->last_message_sender); ?><span>📎 Sent a file</span>
                            <?php else: ?>
                                Start a conversation
                            <?php endif; ?>
                        </p>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-10 text-center opacity-40">
                    <div class="text-3xl mb-2">🔭</div>
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-500">Silence...</p>
                    <p class="text-[10px] mt-1 text-slate-400">Follow people to start a conversation.</p>
                </div>
            <?php endif; ?>
        </div>
    </aside>

    
    <main class="flex-1 flex flex-col mt-16 chat-container relative <?php echo e(isset($chatWith) ? 'flex' : 'hidden md:flex'); ?>">

        
        <?php if(isset($chatWith)): ?>
            <header class="flex items-center justify-between px-6 py-4 border-b border-white/5 bg-[#0b0f1a]/80 backdrop-blur-md sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('messages.index')); ?>" class="md:hidden text-slate-400 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                    <div class="relative">
                        <img src="<?php echo e($chatWith->profile_picture_url); ?>" class="w-10 h-10 rounded-full object-cover">
        				<?php if($chatWith->fresh()->isOnline()): ?>
                        	<span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#0b0f1a] rounded-full"></span>
        				<?php endif; ?>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-white"><?php echo e($chatWith->name); ?></h2>
        				<?php if($chatWith->fresh()->isOnline()): ?>
                        	<p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Active Now</p>
        				<?php else: ?>
        					<p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Offline</p>
                    	<?php endif; ?>
        			</div>
                </div>

                <div class="flex gap-2">
                    <button class="detailsBtn p-2 hover:bg-white/5 rounded-lg transition text-slate-400 hover:text-white" title="Details">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </button>
                    <button id="closeChatBtn" class="p-2 hover:bg-red-500/10 rounded-lg transition text-slate-400 hover:text-red-500" title="Close Chat">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </header>
            

            
            <div id="messageScrollArea" class="flex-1 overflow-y-auto p-6 space-y-6">
               <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-col <?php echo e($message->sender_id === auth()->id() ? 'items-end' : 'items-start'); ?> mb-4">
                        <div class="flex items-end gap-3 max-w-[85%] md:max-w-[80%] <?php echo e($message->sender_id === auth()->id() ? 'flex-row-reverse' : ''); ?>">
                            <?php if($message->sender_id !== auth()->id()): ?>
                                <div class="flex-shrink-0 mb-10"> 
                                    <img src="<?php echo e($message->sender->profile_picture_url); ?>" class="w-8 h-8 rounded-full border border-white/10 shadow-sm">
                                </div>
                            <?php endif; ?>

                            
                            <div class="flex flex-col <?php echo e($message->sender_id === auth()->id() ? 'items-end' : 'items-start'); ?>">
                                
                                
                                <div class="px-4 py-2.5 shadow-xl <?php echo e($message->sender_id === auth()->id() ? 'bg-blue-600 text-white rounded-br-none message-out' : 'bg-white/10 text-slate-200 border border-white/5 rounded-bl-none message-in'); ?>">
                                    <?php if($message->content): ?>
                                        <p class="text-sm leading-relaxed"><?php echo e($message->content); ?></p>
                                    <?php endif; ?>

                                    <?php if($message->image): ?>
                                        <img src="<?php echo e(route('image.display', ['path' => $message->image])); ?>" class="mt-2 rounded-lg max-h-60 w-full object-cover">
                                    <?php endif; ?>

                                    <?php if($message->file): ?>
                                        <a href="<?php echo e(route('image.display', ['path' => $message->file])); ?>" class="flex items-center gap-2 mt-2 bg-black/20 p-2 rounded text-xs" target="_blank">
                                            <span>📎</span> <?php echo e(basename($message->file)); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>

                                
                                <div class="flex items-center gap-1 mt-1 px-1">
                                    <span class="text-[9px] font-bold uppercase tracking-tighter text-slate-500">
                                        <?php echo e($message->created_at->format('h:i A')); ?> 
                                    </span>
                                    <?php if($message->sender_id === auth()->id()): ?> 
                                        <span class="text-blue-500 text-[9px] font-black">✔✔</span> 
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>	      
            </div>
        

        
        <footer class="p-4 bg-[#0b0f1a]/80 backdrop-blur-md border-t border-white/5 mb-10">
            <?php if(auth()->user()->following->contains($chatWith->id)): ?>
                <form action="<?php echo e(route('messages.send', $chatWith->slug)); ?>" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto">
                    <?php echo csrf_field(); ?>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-2 transition-all focus-within:border-blue-500/50 focus-within:ring-4 focus-within:ring-blue-500/10">
                        
                        
                        <div id="previewContainer" class="hidden p-3 mb-2 flex gap-3 overflow-x-auto">
                            <div id="imagePreviewWrapper" class="hidden relative group shrink-0">
                                <img id="imagePreview" class="h-20 w-20 rounded-xl object-cover border border-white/10">
                                <button type="button" onclick="clearImage()" class="absolute -top-2 -right-2 bg-red-600 rounded-full w-5 h-5 text-[10px] flex items-center justify-center opacity-100 transition shadow-lg">✕</button>
                            </div>
                            <div id="filePreviewWrapper" class="hidden flex items-center gap-3 bg-white/5 px-4 py-2 rounded-xl border border-white/5 shrink-0">
                                <span class="text-lg">📄</span>
                                <span id="fileName" class="text-xs font-bold truncate max-w-[120px]"></span>
                                <button type="button" onclick="clearFile()" class="text-red-500">✕</button>
                            </div>
                        </div>

                        <div class="flex items-end gap-2 px-2">
                            <div class="flex gap-1 shrink-0">
                                <label class="p-2.5 hover:bg-white/5 rounded-xl cursor-pointer transition text-slate-400 active:scale-95">
                                    📷 <input id="imageInput" type="file" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                                </label>
                                <label class="p-2.5 hover:bg-white/5 rounded-xl cursor-pointer transition text-slate-400 active:scale-95">
                                    📎 <input id="fileInput" type="file" name="file" class="hidden" onchange="previewFile(event)">
                                </label>
                            </div>
                            
                            <textarea name="content" rows="1" id="messageInput" 
                                placeholder="Message <?php echo e($chatWith->name); ?>..." 
                                oninput="autoResize(this); checkInput()" 
                                class="flex-1 bg-transparent border-none focus:ring-0 text-[15px] py-2 px-1 max-h-32 hide-scrollbar text-white placeholder-slate-500"></textarea>

                            <button type="submit" id="sendButton" disabled 
                                class="bg-blue-600 hover:bg-blue-500 disabled:opacity-20 disabled:grayscale p-2.5 rounded-xl transition-all shrink-0 flex items-center justify-center">

                                
                                <span class="hidden md:block px-2 text-xs font-black uppercase tracking-widest text-white">
                                    Send
                                </span>

                                
                                <span class="block md:hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                
                <div class="max-w-4xl mx-auto">
                    <div class="bg-blue-600/5 border border-blue-500/20 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
                        <p class="text-[13px] font-semibold text-slate-400 text-center md:text-left">
                            ⚠️ Follow <span class="text-white font-bold"><?php echo e($chatWith->name); ?></span> to unlock messaging.
                        </p>
                        <form action="<?php echo e(route('follow.toggle', $chatWith->slug)); ?>" method="POST" class="w-full md:w-auto">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-600/20">
                                Follow to unlock
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </footer>
        

        <?php else: ?>
            <div class="flex-1 flex flex-col items-center justify-center text-center p-12">
                <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center text-4xl mb-6 grayscale opacity-50">💬</div>
                <h3 class="text-xl font-bold text-white">Your Inbox</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-xs">Select a contact to start messaging.</p>
            </div>
        <?php endif; ?>
    </main>

    

    <?php if(isset($chatWith)): ?>
    <div id="modalOverlay" class="fixed inset-0 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
        <div class="bg-[#0f172a] border border-white/10 rounded-lg w-full max-w-sm overflow-hidden shadow-2xl">
            <div class="p-8 text-center">
                <img src="<?php echo e($chatWith->profile_picture_url); ?>" class="w-24 h-24 rounded-full mx-auto border-4 border-blue-600/20 p-1 mb-4">
                <h2 class="text-xl font-black text-white"><?php echo e($chatWith->name); ?></h2>
                <p class="text-xs text-blue-500 font-bold uppercase tracking-widest mt-1"><?php echo e($chatWith->company->company_name ?? 'Student Applicant'); ?></p>
                
                <div class="mt-8 space-y-4 text-left border-t border-white/5 pt-6 text-sm">
                    <p><strong class="text-slate-500 uppercase text-[10px]">Email:</strong><br><?php echo e($chatWith->email); ?></p>
                    <?php if($chatWith->address): ?>
                        <p><strong class="text-slate-500 uppercase text-[10px]">Address:</strong><br><?php echo e($chatWith->address); ?></p>
                    <?php endif; ?>
                </div>

                <button id="modalCloseBtn" class="mt-10 w-full py-3 rounded-xl bg-white/5 hover:bg-white/10 font-bold text-xs uppercase tracking-widest transition">Close</button>
            </div>
        </div>
    </div>
    <?php endif; ?>



    <div id="global-loader" style="display: none;" class="fixed inset-0 w-screen h-screen z-[9999] flex flex-col items-center justify-center bg-[#0f172a]/80 backdrop-blur-md">
        <div class="flex flex-col items-center gap-4">
            <div class="h-14 w-14 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-blue-500 font-black tracking-[0.2em] text-sm uppercase animate-pulse">Processing</span>
        </div>
    </div>


<script>
    //========================== DOMContentLoaded: Initialize Search, Scroll, and Link Loader ==========================//
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('contactSearch');
        const contacts = document.querySelectorAll('.contact-item');
        const loader = document.getElementById('global-loader');

        //========================== Search Functionality ==========================//
        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                contacts.forEach(contact => {
                    const name = contact.querySelector('.contact-name').textContent.toLowerCase();
                    contact.style.display = name.includes(searchTerm) ? 'flex' : 'none';
                });
            });
        }

        //========================== Scroll to Bottom ==========================//
        const messageArea = document.getElementById('messageScrollArea');
        if (messageArea) messageArea.scrollTop = messageArea.scrollHeight;

        //========================== Global Loader for links ==========================//
        document.querySelectorAll('.load-link').forEach(link => {
            link.addEventListener('click', () => {
                loader.style.display = 'flex';
            });
        });
    });

    document.addEventListener('click', function (e) {
    //========================== OPEN DETAILS MODAL ==========================//
    if (e.target.closest('.detailsBtn')) {
        const modal = document.getElementById('modalOverlay');
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        }
    //========================== CLOSE MODAL ==========================//
    if (e.target.closest('#modalCloseBtn')) {
        const modal = document.getElementById('modalOverlay');
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        }
    //========================== CLOSE CHAT ==========================//
    if (e.target.closest('#closeChatBtn')) {
        window.location.href = "<?php echo e(route('messages.index')); ?>";
        }
    });
    //========================== Auto-resize textarea ==========================//
    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }
    //========================== Enable/Disable Send Button ==========================//
    function checkInput() {
        const text = document.getElementById('messageInput').value.trim();
        const hasImg = document.getElementById('imageInput').files.length > 0;
        const hasFile = document.getElementById('fileInput').files.length > 0;
        document.getElementById('sendButton').disabled = !(text || hasImg || hasFile);
    }
    //========================== Preview Functions: Image ==========================//
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        document.getElementById('imagePreview').src = URL.createObjectURL(file);
        document.getElementById('imagePreviewWrapper').classList.remove('hidden');
        document.getElementById('previewContainer').classList.remove('hidden');
        clearFile(false);
        checkInput();
    }
    //========================== Preview Functions: File ==========================//
    function previewFile(event) {
        const file = event.target.files[0];
        if (!file) return;
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('filePreviewWrapper').classList.remove('hidden');
        document.getElementById('previewContainer').classList.remove('hidden');
        clearImage(false);
        checkInput();
    }
    //========================== Clear Previews: Image ==========================//
    function clearImage(reset = true) {
        document.getElementById('imagePreviewWrapper').classList.add('hidden');
        if (reset) document.getElementById('imageInput').value = '';
        hidePreviewIfEmpty();
        checkInput();
    }
    //========================== Clear Previews: File ==========================//
    function clearFile(reset = true) {
        document.getElementById('filePreviewWrapper').classList.add('hidden');
        if (reset) document.getElementById('fileInput').value = '';
        hidePreviewIfEmpty();
        checkInput();
    }
    //========================== Hide Preview Container if both previews are empty ==========================//
    function hidePreviewIfEmpty() {
        const imgHidden = document.getElementById('imagePreviewWrapper').classList.contains('hidden');
        const fileHidden = document.getElementById('filePreviewWrapper').classList.contains('hidden');
        if (imgHidden && fileHidden) document.getElementById('previewContainer').classList.add('hidden');
    }
   
    </script>
</body>
</html><?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/pages/messages.blade.php ENDPATH**/ ?>