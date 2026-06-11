<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ojtFinder | Admin Panel</title>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZEMJ5KJY75"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-ZEMJ5KJY75');
    </script>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ 'public/of_logo.png' }}?v=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
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
<body class="bg-slate-950 text-slate-200">

@include('partial.navigation')

<div class="max-w-7xl mx-auto px-6 pt-28 pb-16">
    <div class="mb-10">
        <h1 class="text-4xl font-black tracking-tight text-red-500">
            Admin Control Panel
        </h1>
        <p class="text-slate-400 mt-2">
            Manage users, companies, and posts across the platform.
        </p>
    </div>
    <div class="flex gap-3 mb-8">
        <button onclick="showTab('users')"
            class="admin-tab px-6 py-3 rounded-xl font-bold text-sm uppercase tracking-widest transition
                   bg-white/5 hover:bg-blue-600/30 border border-white/10">
            👤 Users
        </button>

        <button onclick="showTab('companies')"
            class="admin-tab px-6 py-3 rounded-xl font-bold text-sm uppercase tracking-widest transition
                   bg-white/5 hover:bg-blue-600/30 border border-white/10">
            🏢 Companies
        </button>

        <button onclick="showTab('posts')"
            class="admin-tab px-6 py-3 rounded-xl font-bold text-sm uppercase tracking-widest transition
                   bg-white/5 hover:bg-blue-600/30 border border-white/10">
            📰 Posts
        </button>
    </div>
    <div class="glass-card rounded-2xl p-6 min-h-[400px]">
        <div id="users" class="admin-section">
            @include('admin.partials.users')
        </div>
        <div id="companies" class="admin-section hidden">
            @include('admin.partials.companies')
        </div>
        <div id="posts" class="admin-section hidden">
            @include('admin.partials.posts')
        </div>
    </div>
</div>

<script>
    function showTab(id) {
        document.querySelectorAll('.admin-section').forEach(e => e.classList.add('hidden'));
        document.querySelectorAll('.admin-tab').forEach(e =>
            e.classList.remove('bg-blue-600/40', 'text-white')
        );

        document.getElementById(id).classList.remove('hidden');
        event.target.classList.add('bg-blue-600/40', 'text-white');
    }
</script>

</body>
</html>