<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrBusiness
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('show.login');
        }

        $role = Auth::user()->role_id;

        if (!in_array($role, [3, 4])) {
            // Geen adverteerder
            return redirect()->route('index');
        }

        return $next($request);
    }
}
