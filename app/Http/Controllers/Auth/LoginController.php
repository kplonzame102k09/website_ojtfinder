<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $ip = $request->ip();
        $email = Str::lower($request->input('email'));
        
        $throttleKey = $email . '/' . $ip;
        $penaltyKey = $email . '/' . $ip . '/penalty';

        //==========================THE GATEKEEPER: Check if this specific account/IP is locked.==========================//
        //==========================If they refresh the page (Ctrl+R) and try again, this stops them immediately.==========================//
        //==========================Even if the password is correct, they cannot pass this line.==========================//
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('throttleSeconds', $seconds)->onlyInput('email');
        }

        //========================== USER EXISTENCE CHECK ==========================//
        $userExists = User::where('email', $email)->exists();
        if (!$userExists) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors(['email' => 'No user found with this email address.'])->onlyInput('email');
        }

        //========================== ATTEMPT LOGIN ==========================//
        //========================== This line is only reached if the timer is DONE.==========================//
        if (Auth::attempt($credentials, $request->has('remember'))) {
            RateLimiter::clear($throttleKey);
            Cache::forget($penaltyKey);

            $request->session()->regenerate();
            
            Activity::create([
                'user_id' => Auth::id(),
                'type' => 'Auth',
                'description' => 'User logged into the system.',
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('home')->with('welcome', true);
        }

        //========================== FAILURE: Update Penalty and set the new Lockout Timer ==========================//
        $totalFailures = RateLimiter::hit($penaltyKey, 3600);

        $decaySeconds = 60;
        if ($totalFailures >= 17)      $decaySeconds = 86400; // 24 hours
        elseif ($totalFailures >= 15) $decaySeconds = 3600;  // 1 hour
        elseif ($totalFailures >= 12) $decaySeconds = 1800;  // 30 mins
        elseif ($totalFailures >= 8)  $decaySeconds = 300;   // 5 mins

        RateLimiter::hit($throttleKey, $decaySeconds);

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email', 'remember');
    }
    //========================== LOGOUT ==========================//
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Activity::create([
                'user_id' => Auth::id(),
                'type' => 'Auth',
                'description' => 'User logged out successfully.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}