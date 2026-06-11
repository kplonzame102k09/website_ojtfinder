<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasCompany
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->company) {
            return redirect()->route('company.register')
                ->with('error', 'Only registered companies can create posts.');
        }

        return $next($request);
    }
}
