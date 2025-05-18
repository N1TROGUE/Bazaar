<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdvertiser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('show.login');
        }

        $role = Auth::user()->role_id;

        if (!in_array($role, [2, 3])) {
            // Geen adverteerder
            return redirect()->route('index');
        }

        return $next($request);
    }
}
