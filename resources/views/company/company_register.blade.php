<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ojtFinder | Company Registration</title>
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
        .bg-main { 
            background: radial-gradient(circle at top right, #070707, #0f172a); 
            background-attachment: fixed; 
        }
        .glass-card { 
            background: rgba(255, 255, 255, 0.02); 
            border: 1px solid rgba(255, 255, 255, 0.08); 
            backdrop-filter: blur(12px); 
        }
        .input-dark {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            background: rgba(255, 255, 255, 0.07);
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
        }
        .custom-file-input::-webkit-file-upload-button {
            visibility: hidden;
            display: none;
        }
        .custom-file-input::before {
            content: 'SELECT FILE';
            display: inline-block;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 4px;
            padding: 5px 12px;
            outline: none;
            white-space: nowrap;
            cursor: pointer;
            font-weight: 900;
            font-size: 8px;
            letter-spacing: 0.1em;
            color: #60a5fa;
            margin-right: 10px;
        }
    </style>
</head>
<body class="bg-main text-slate-300 antialiased font-sans min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-3xl glass-card rounded-2xl p-8 md:p-12 shadow-2xl border-t-2 border-t-blue-600">
        <header class="mb-10 text-center md:text-left">
            <h2 class="text-3xl font-black text-white tracking-tighter">
                Company <span class="text-blue-500">Registration</span>
            </h2>
            <p class="text-[10px] uppercase tracking-[0.3em] text-slate-500 font-bold mt-2">
                Secure your industrial partnership presence
            </p>
        </header>
        {{--========================== Company Form Registration ==========================--}}
        <form action="{{ route('company.register.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            @csrf
            {{--========================== Company Name ==========================--}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Company Name</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Your Company Name" class="input-dark w-full rounded-xl px-4 py-3 text-sm" required>
            </div>

            {{--========================== Company Email ==========================--}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Company Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@company.com" class="input-dark w-full rounded-xl px-4 py-3 text-sm" required>
            </div>

            {{--========================== Address ==========================--}}
            <div class="md:col-span-2 space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Company Address</label>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="i.e. Lucena City, Quezon" class="input-dark w-full rounded-xl px-4 py-3 text-sm" required>
            </div>

            {{--========================== Company Logo ==========================--}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Brand Identity (Logo)</label>
                <label for="company_logo" class="relative flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-white/10 rounded-2xl cursor-pointer bg-white/5 hover:bg-white/10 transition-all overflow-hidden group">
                    
                    <div id="placeholder-content" class="flex flex-col items-center">
                        <svg class="w-8 h-8 mb-2 text-slate-500 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Upload PNG/JPG</span>
                    </div>

                    <img id="logo-preview" class="hidden absolute inset-0 w-full h-full object-contain bg-[#0f172a] p-4">

                    <input id="company_logo" type="file" name="company_logo" accept="image/*" class="hidden" onchange="previewImage(this)">
                </label>
            </div>

            {{--========================== Contact & About ==========================--}}
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Company Contact Number</label>
                    <input type="tel" name="contact_number" value="{{ old('contact_number') }}" placeholder="+63 900 000 0000" class="input-dark w-full rounded-xl px-4 py-3 text-sm" required>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">About Company</label>
                    <textarea name="about" rows="4" class="input-dark w-full rounded-xl px-4 py-3 text-sm resize-none" placeholder="Describe company nature..." required>{{ old('about') }}</textarea>
                </div>
            </div>

            {{--========================== Documents ==========================--}}
            <div class="md:col-span-2 mt-4">
                <div class="flex items-center gap-4 mb-6">
                    <h3 class="text-xs font-black text-blue-500 uppercase tracking-[0.2em]">Verification Documents</h3>
                    <h4 class="text-[9px] font-bold text-slate-500 uppercase tracking-widest italic">Uploading or submitting fake documents will result in account deletion.</h4>
                    <div class="h-px flex-1 bg-white/5"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    @foreach(['certificate_of_corporation' => 'Corp. Certificate', 
                              'certificate_of_registration' => 'Tax Registration', 
                              'mayors_permit' => "Mayor's Permit", 
                              'barangay_clearance' => 'Brgy. Clearance'] as $name => $label)
                    <div class="flex flex-col gap-1 p-3 rounded-xl bg-white/5 border border-white/5">
                        <label class="text-[9px] font-black text-slate-500 uppercase tracking-tight">{{ $label }}</label>
                        <input type="file" name="{{ $name }}" class="custom-file-input text-[10px] text-slate-400 file:hidden" required>
                    </div>
                    @endforeach
                </div>
            </div>

            {{--========================== Actions ==========================--}}
            <div class="md:col-span-2 flex flex-col md:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('home') }}" 
                    onclick="return confirmAbandon()"
                    class="order-2 md:order-1 px-8 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white hover:bg-white/5 transition text-center">
                        <- Back & Cancel
                    </a>
                {{--========================== Submit Btn ==========================--}}
                <button type="submit"
                    class="px-10 py-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest">
                    Submit Registration
                </button>
            </div> 
        </form>    
<script>
    //========================== Image Preview Function ==========================//
    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logo-preview');
                const placeholder = document.getElementById('placeholder-content');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
    //========================== Confirmation Before Abandoning Registration ==========================// 
    function handleCancelRegistration(event) {
        const confirmLeave = confirm("Are you sure? Your progress will be lost and your account will remain as a Guest.");
        if (!confirmLeave) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
</body>
</html>