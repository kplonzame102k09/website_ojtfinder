<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeenAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $request, Closure $next): Response
{
    // If you see a white screen with this text, the middleware IS working.
    // If you don't see this, the registration in bootstrap/app.php is wrong.
    // dd('Middleware is reached!'); 

    if (auth()->check()) {
        auth()->user()->updateQuietly(['last_seen_at' => now()]);
    }

    return $next($request);
    }
}
