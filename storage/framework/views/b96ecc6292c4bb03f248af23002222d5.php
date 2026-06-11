<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e('public/of_logo.png'); ?>?v=1">
  <meta name="google-site-verification" content="WAfQ8Ukar-cZVK8eQBJ2MjheJGQuAveD79Ny6ctEXtQ" />
  <meta name="description" content="Find OJT and internship opportunities
  near you. Browse hundreds of companies offering on-the-job training
  for Filipino students.">
  <meta name="keywords" content="OJT, internship, on-the-job training,
  Philippines, student jobs">
  <meta property="og:title" content="ojtFinder | Find OJT Internships">
  <meta property="og:description" content="Browse OJT opportunities near you">
  <meta property="og:image" content="https://ojtfinder.42web.io/public/of_logo.png">
  <meta property="og:url" content="https://ojtfinder.42web.io">
  <meta property="og:type" content="website">
  <title>ojtFinder | Login</title>

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
    @keyframes pulse {
            0% { transform: scale(0.5); opacity: 0.8; }
            100% { transform: scale(2.5); opacity: 0; }
        }
	</style>
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen font-sans relative overflow-hidden">
  <div id="login-loader" style="display: none;" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-slate-950/80 backdrop-blur-md">
      <div class="flex flex-col items-center gap-4">
          <div class="h-16 w-16 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
          <p class="text-indigo-400 font-black tracking-widest text-sm uppercase animate-pulse">Authenticating...</p>
      </div>
  </div>

    <div class="absolute inset-0 backdrop-blur-sm z-0"></div>

  <div class="relative mx-auto max-w-md w-full bg-slate-800 rounded-xl shadow-2xl overflow-hidden animate-fadeIn">
    <div class="px-8 py-6 text-center bg-slate-900">
      <h2 class="font-extrabold text-4xl text-white flex items-center justify-center gap-1">
    		<span class="text-blue-500">ojt</span>Finder<img src="of_logo.png" class="w-10 h-10 inline animate-pulse">
	  </h2>
      <h4 class="text-indigo-200 text-sm italic mt-2">
        Find your path. Build your Future.
      </h4>
    </div>

    <div class="px-8 py-6 space-y-5">
      <?php if($errors->any()): ?>
        <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-lg text-sm font-medium">
            <strong>Whoops!</strong> Something went wrong.
        </div>
      <?php endif; ?>

      <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        <div>
          <input type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="Your Email"
            class="w-full px-4 py-3 rounded-lg bg-slate-700 border <?php echo e($errors->has('email') ? 'border-red-500' : 'border-slate-600'); ?> text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" required autofocus>

          <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-500 text-xs mt-1 font-semibold"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mt-4 relative">
          <input id="password" type="password" name="password" placeholder="Password"
            class="w-full px-4 py-3 rounded-lg bg-slate-700 border <?php echo e($errors->has('password') ? 'border-red-500' : 'border-slate-600'); ?> text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition" required>

    	<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-500 text-xs mt-1 font-semibold"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-400 transition focus:outline-none px-2">
              <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12.083a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              </svg>
          </button>
        </div>

        <div class="flex items-center space-x-2 mt-3 text-slate-200">
          <input type="checkbox" name="remember" id="remember"
            <?php echo e(old('remember') ? 'checked' : ''); ?>

            class="rounded border-slate-600 text-indigo-500 focus:ring-indigo-500 cursor-pointer">
          <label for="remember" class="text-slate-200 text-sm cursor-pointer">Remember Me</label>
        </div>

        <div class="mt-5">
          <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-lg transition transform hover:scale-105 shadow-lg">
            Log In
          </button>
        </div>

        <div class="text-center mt-3">
          <a href="<?php echo e(route('password.request')); ?>" class="text-indigo-400 hover:text-indigo-300 hover:underline text-sm transition">Forgot Password?</a>
        </div>
      </form>

      <div class="text-center mt-4">
        <p class="text-slate-300 text-sm">
          Don’t have an account?
          <a href="<?php echo e(route('signup')); ?>" class="font-semibold text-indigo-500 hover:text-indigo-400 hover:underline transition">Sign Up</a>
        </p>
      </div>
    </div>
  </div>

  <?php if(session('throttleSeconds')): ?>
    <div id="throttleModal" class="fixed inset-0 flex items-center justify-center bg-slate-950/90 backdrop-blur-sm z-[110]">
        <div class="bg-slate-800 border border-red-500/50 rounded-xl shadow-2xl max-w-sm w-full p-8 text-center animate-scaleUp">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-500/10 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2zm-10 0V7a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3h12a3 3 0 003-3v-8a3 3 0 00-3-3H9z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Security Lockout</h3>
            <p class="text-slate-300 mb-6">Too many failed attempts. For your security, login is disabled for:</p>

            <div class="text-5xl font-black text-indigo-500 mb-6 tracking-widest" id="countdownTimer">
                <?php echo e(session('throttleSeconds')); ?>s
            </div>

            <p class="text-xs text-slate-500 uppercase tracking-tighter">Please wait until the timer hits zero</p>
        </div>
    </div>
  <?php endif; ?>

  <?php if(session('success')): ?>
    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
      <div class="bg-slate-800 rounded-xl shadow-xl max-w-sm w-full p-6 text-center animate-scaleUp">
        <h3 class="text-2xl font-bold text-white mb-2">Success 🎉</h3>
        <p class="text-slate-300 mb-4"><?php echo e(session('success')); ?></p>
        <button onclick="closeModal()" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg w-full transition transform hover:scale-105">
          OK
        </button>
      </div>
    </div>
  <?php endif; ?>

<script>
    function closeModal(){
      document.getElementById('successModal').style.display = 'none';
    }

    const loginForm = document.querySelector('form');
    const loader = document.getElementById('login-loader');

    loginForm.addEventListener('submit', function() {
        loader.style.display = 'flex';
        const btn = this.querySelector('button[type="submit"]');
        if(btn) btn.disabled = true;
    });

    function closeModal(){
        document.getElementById('successModal').style.display = 'none';
    }

    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />`;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12.083a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />`;
        }
    }
    <?php if(session('throttleSeconds')): ?>
        (function() {
            let timeLeft = <?php echo e(session('throttleSeconds')); ?>;
            const timerDisplay = document.getElementById('countdownTimer');
            const countdown = setInterval(() => {
                timeLeft--;
                timerDisplay.textContent = timeLeft + "s";

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    document.getElementById('throttleModal').style.display = 'none';
                    window.location.reload();
                }
            }, 1000);
        })();
    <?php endif; ?>
  </script>

  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }

    @keyframes scaleUp {
      from { transform: scale(0.8); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    .animate-scaleUp { animation: scaleUp 0.4s ease-out forwards; }
  </style>
</body>
</html>
<?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/auth/login.blade.php ENDPATH**/ ?>