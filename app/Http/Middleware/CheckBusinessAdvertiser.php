<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBusinessAdvertiser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('show.login');
        }

        if (Auth::user()->role_id !== 3) {
            // Geen zakelijke adverteerder
            return redirect()->route('index');
        }

        return $next($request);
    }
}
