<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
  <title>ojtFinder | Sign Up</title>
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
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen font-sans relative overflow-auto">
      <div class="absolute inset-0 backdrop-blur-sm z-0"></div>
  <div class="relative mx-auto max-w-md w-full bg-slate-800 rounded-xl shadow-2xl overflow-hidden animate-fadeIn">

    <div class="px-8 py-6 text-center bg-slate-900">
      <h2 class="font-extrabold text-3xl text-white">
        Create Your Account
      </h2>
      <p class="text-indigo-200 mt-2 text-sm">Sign up to find the best OJT opportunities.</p>
    </div>

    <div class="px-8 py-6 space-y-5">
      <form method="POST" action="<?php echo e(route('signup')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>
    
        <div class="flex space-x-3">
          <input type="text" name="first_name" placeholder="First Name" 
                 class="flex-1 py-3 rounded-lg bg-slate-700 border border-slate-600 text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
          <input type="text" name="surname" placeholder="Last Name" 
                 class="flex-1 py-3 rounded-lg bg-slate-700 border border-slate-600 text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
        </div>
        <input type="email" name="email" placeholder="Email" 
               class="w-full px-4 py-3 rounded-lg bg-slate-700 border border-slate-600 text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>

        <input type="tel" name="contact_number" placeholder="Contact Number"
               class="w-full px-4 py-3 rounded-lg bg-slate-700 border border-slate-600 text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>

    <div class="grid grid-cols-2 gap-2">
        <input type="text" name="barangay" placeholder="Barangay" 
               class="px-4 py-3 rounded-xl bg-slate-700 border border-white/5 text-sm text-slate-300 outline-none focus:ring-1 focus:ring-indigo-500 transition">
        <input type="text" name="city" placeholder="City" 
               class="px-4 py-3 rounded-xl bg-slate-700 border border-white/5 text-sm text-slate-300 outline-none focus:ring-1 focus:ring-indigo-500 transition">
        <input type="text" name="province" placeholder="Province" 
               class="px-4 py-3 rounded-xl bg-slate-700 border border-white/5 text-sm text-slate-300 outline-none focus:ring-1 focus:ring-indigo-500 transition">
        <input type="text" name="region" placeholder="Postal Code" 
               class="px-4 py-3 rounded-xl bg-slate-700 border border-white/5 text-sm text-slate-300 outline-none focus:ring-1 focus:ring-indigo-500 transition">
    </div>
    
    <div class="mt-4 relative">
        <input id="password" type="password" name="password" placeholder="Password"
               class="w-full px-4 py-3 rounded-lg bg-slate-700 border border-slate-600 text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
    <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-400 transition focus:outline-none px-2 cursor-pointer">
              <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12.083a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              </svg>
          </button>
    	</div>
        <div>
          <label class="text-slate-200 text-sm mb-1 inline-block">Date of Birth</label>
          <div class="flex space-x-3 mt-1">
            <select name="day" class="flex-1 px-2 py-2 rounded-lg border border-slate-600 bg-slate-700 text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
              <?php for($i = 1; $i <= 31; $i++): ?>
                <option><?php echo e($i); ?></option>
              <?php endfor; ?>
            </select>
            <select name="month" class="flex-1 px-2 py-2 rounded-lg border border-slate-600 bg-slate-700 text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
              <?php
                $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
              ?>
              <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option><?php echo e($month); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="year" class="flex-1 px-2 py-2 rounded-lg border border-slate-600 bg-slate-700 text-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
              <?php for($y = 1990; $y <= date('Y'); $y++): ?>
                <option><?php echo e($y); ?></option>
              <?php endfor; ?>
            </select>
          </div>
        </div>

        <div>
          <label class="text-slate-200 text-sm mb-1 inline-block">Gender</label>
          <div class="flex space-x-3 mt-1">
            <label class="flex-1 flex justify-between items-center px-2 py-2 border border-slate-600 rounded-lg hover:bg-indigo-900 transition">
              <span class="text-slate-200">Female</span>
              <input type="radio" name="gender" value="female" required>
            </label>
            <label class="flex-1 flex justify-between items-center px-2 py-2 border border-slate-600 rounded-lg hover:bg-indigo-900 transition">
              <span class="text-slate-200">Male</span>
              <input type="radio" name="gender" value="male">
            </label>
            <label class="flex-1 flex justify-between items-center px-2 py-2 border border-slate-600 rounded-lg hover:bg-indigo-900 transition">
              <span class="text-slate-200">Other</span>
              <input type="radio" name="gender" value="other">
            </label>
          </div>
        </div>

        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-8 border-t border-white/5 pt-6">
            By signing up, you agree to our 
            <a href="<?php echo e(route('legal.show', 'terms')); ?>" class="text-indigo-500 hover:text-indigo-400 transition-colors underline decoration-indigo-500/30 underline-offset-4">Terms</a>,
            <a href="<?php echo e(route('legal.show', 'privacy')); ?>" class="text-indigo-500 hover:text-indigo-400 transition-colors underline decoration-indigo-500/30 underline-offset-4">Privacy Policy</a>,
            and <a href="<?php echo e(route('legal.show', 'cookies')); ?>" class="text-indigo-500 hover:text-indigo-400 transition-colors underline decoration-indigo-500/30 underline-offset-4">Cookies Policy</a>.
        </div>

        <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-lg transition transform hover:scale-105 shadow-lg">
          Sign Up
        </button>
      </form>

      <div class="text-center mt-4 text-sm text-slate-200">
        Already have an account?
        <a href="<?php echo e(route('login')); ?>" class="font-semibold text-indigo-500 hover:text-indigo-400 hover:underline transition">Log In</a>
      </div>
    </div>
  </div>

  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
  </style>
      
<script> 
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

</script>
</body>
</html>
<?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/auth/signup.blade.php ENDPATH**/ ?>