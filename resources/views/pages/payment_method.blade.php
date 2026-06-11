<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Method | ojtFinder</title>
    @vite('resources/css/app.css')
    <style>
        .bg-main { background: radial-gradient(circle at top right, #070707, #0f172a); background-attachment: fixed; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        
        /* GCash Branded Preview */
        .gcash-preview {
            background: linear-gradient(135deg, #0056eb 0%, #2882ff 100%);
            box-shadow: 0 20px 50px rgba(0, 86, 235, 0.3);
        }

        .loader {
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-main text-slate-200 antialiased min-h-screen flex items-center justify-center p-6">

    <div class="max-w-4xl w-full grid lg:grid-cols-2 gap-12 items-center">
        
        {{-- Left Side: Visual Summary --}}
        <div class="space-y-8">
            <div>
                <h1 class="text-4xl font-black text-white tracking-tighter mb-4">Ready to go <span class="text-blue-500">Pro?</span></h1>
                <p class="text-slate-400">Complete your payment via <span class="text-[#0056eb] font-bold">GCash</span> to instantly unlock your Company Dashboard and start hiring interns.</p>
            </div>

            {{-- Visual GCash Wallet Preview --}}
            <div class="gcash-preview w-full max-w-[350px] aspect-[1.6/1] rounded-lg p-8 flex flex-col justify-between text-white relative overflow-hidden">
                <div class="flex justify-between items-start relative z-10">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/GCash_logo.svg/1200px-GCash_logo.svg.png" class="h-6 brightness-0 invert" alt="GCash">
                    <span class="text-[10px] font-black uppercase tracking-widest opacity-80">E-Wallet</span>
                </div>
                
                <div class="relative z-10">
                    <p class="text-[10px] uppercase opacity-60 font-bold mb-1">Total Amount</p>
                    <p class="text-3xl font-black tracking-tighter">₱ 150.00</p>
                </div>

                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute top-0 left-0 w-20 h-20 bg-blue-400/20 rounded-full blur-2xl"></div>
            </div>

            <div class="flex items-center gap-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full border-2 border-[#0f172a] bg-blue-600 flex items-center justify-center text-[10px]">✓</div>
                </div>
                <span>Secured by PayMongo</span>
            </div>
        </div>

        {{-- Right Side: Checkout Form --}}
        <div class="glass-card rounded-lg p-8 md:p-12 shadow-2xl relative">
            <h2 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
                Order Summary
            </h2>
            
            <div class="space-y-6">
                <div class="flex justify-between items-center p-4 bg-white/5 rounded-lg border border-white/5">
                    <div>
                        <p class="text-sm font-bold text-white">Company Pro Plan</p>
                        <p class="text-[10px] text-slate-500 uppercase font-black">Monthly Subscription</p>
                    </div>
                    <p class="font-black text-blue-500">₱150.00</p>
                </div>

                <div class="space-y-3 px-1">
                    <div class="flex justify-between text-xs tracking-wide">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="text-slate-300">₱150.00</span>
                    </div>
                    <div class="flex justify-between text-xs tracking-wide">
                        <span class="text-slate-500">Processing Fee</span>
                        <span class="text-slate-300">₱0.00</span>
                    </div>
                    <div class="pt-3 border-t border-white/10 flex justify-between items-end">
                        <span class="text-xs font-black text-white uppercase tracking-widest">Total Amount</span>
                        <span class="text-2xl font-black text-white tracking-tighter">₱150.00</span>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="pt-6">
                    <button id="payBtn" class="group w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-5 rounded-lg text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-600/25 active:scale-[0.98] flex items-center justify-center gap-3">
                        <span>Pay with GCash</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <a href="{{ route('home') }}" class="mt-4 block text-center text-sm text-slate-400 hover:text-red-500 transition">
                         Cancel & Return Home
                    </a>
                    
                    <p class="text-center mt-6 text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                        Redirecting to GCash Secure Portal
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('payBtn').addEventListener('click', async function() {
            const btn = this;
            const originalContent = btn.innerHTML;
            
            // Set Loading State
            btn.disabled = true;
            btn.innerHTML = `<div class="w-5 h-5 border-2 border-white loader rounded-full"></div> <span>Processing...</span>`;

            try {
                // Adjust this endpoint to your Laravel route
                const response = await fetch('/gcash/create', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.checkout_url) {
                    window.location.href = data.checkout_url;
                } else {
                    throw new Error('Payment failed to initiate');
                }

            } catch (error) {
                console.error(error);
                alert('Something went wrong. Please try again.');
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });
    </script>
</body>
</html>