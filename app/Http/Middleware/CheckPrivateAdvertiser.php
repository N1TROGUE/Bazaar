<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPrivateAdvertiser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('show.login');
        }

        if (Auth::user()->role_id !== 2) {
            // Geen particuliere adverteerder
            return redirect()->route('index');
        }

        return $next($request);
    }
}
