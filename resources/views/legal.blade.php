<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="WAfQ8Ukar-cZVK8eQBJ2MjheJGQuAveD79Ny6ctEXtQ" />
    <meta name="description" content="Find OJT and internship opportunities near you. Browse hundreds of companies offering on-the-job training for Filipino students.">
    <meta name="keywords" content="OJT, internship, on-the-job training, Philippines, student jobs">
    <meta property="og:title" content="ojtFinder | Find OJT Internships">
    <meta property="og:description" content="Browse OJT opportunities near you">
    <meta property="og:image" content="https://ojtfinder.42web.io/public/of_logo.png">
    <meta property="og:url" content="https://ojtfinder.42web.io">
    <meta property="og:type" content="website">
    <title>ojtFinder | {{ $doc['title'] }}</title>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZEMJ5KJY75"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-ZEMJ5KJY75');
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-main { background: radial-gradient(circle at top right, #0f172a, #070707); background-attachment: fixed; }
        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
        }
        /* Custom scrollbar for legal text */
        .legal-scroll::-webkit-scrollbar { width: 4px; }
        .legal-scroll::-webkit-scrollbar-track { background: transparent; }
        .legal-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .legal-scroll::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
    </style>
</head>
<body class="bg-main text-slate-300 font-sans antialiased min-h-screen flex flex-col items-center justify-center p-4">

    <div class="w-full max-w-3xl mb-4 flex justify-between items-end px-2">
        <div class="flex flex-col">
            <h1 class="text-3xl font-black text-white uppercase tracking-tighter">{{ $doc['title'] }}</h1>
        </div>
        <div class="text-right">
            <span class="text-[9px] font-bold text-slate-600 uppercase tracking-widest block">Last Revision</span>
            <span class="text-[10px] font-mono text-slate-400">FEB_2026_v1.0</span>
        </div>
    </div>

    <div class="w-full max-w-3xl glass-card rounded-2xl shadow-2xl overflow-hidden border-t-4 border-{{ $doc['color'] }}">
        <div class="p-8 md:p-12 max-h-[70vh] overflow-y-auto legal-scroll">

            <div class="prose prose-invert prose-sm max-w-none">
                {{-- This parses the HTML content sent from the Controller --}}
                {!! $doc['content'] !!}

                <hr class="border-white/5 my-8">

                <section class="bg-white/5 rounded-xl p-6 border border-white/5">
                    <h4 class="text-white text-xs font-black uppercase tracking-widest mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-{{ $doc['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Legal Contact
                    </h4>
                    <p class="text-[11px] leading-relaxed text-slate-500 m-0">
                        Questions regarding this document should be directed to <span class="text-{{ $doc['color'] }}">legal@ojtfinder.ph</span>.
                        Unauthorized reproduction of this document is prohibited under digital property laws.
                    </p>
                </section>
            </div>

        </div>

        <div class="bg-white/5 p-6 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-white/5">
            <button onclick="window.print()" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Hardcopy Archive
            </button>

            <a href="javascript:history.back()" class="bg-{{ $doc['color'] }} hover:opacity-90 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg">
                Acknowledge & Return
            </a>
        </div>
    </div>

    <p class="mt-8 text-[9px] text-slate-600 font-bold uppercase tracking-[0.4em]">ojtFinder Digital Security Infrastructure</p>

</body>
</html>
