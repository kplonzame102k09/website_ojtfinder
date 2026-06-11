<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictGuestAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // If not logged in, let Laravel handle it
        if (!$user) {
            return redirect()->route('login');
        }

        // 🚫 Guest users can ONLY access home
        if ($user->role === 'guest') {
            if (!$request->routeIs('home')) {
                return redirect()->route('home')
                    ->with('error', 'Guests can only access the home page.');
            }
        }

        return $next($request);
    }
}
