<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('show.login');
        }

        if (Auth::user()->role_id !== 4) {
            // De gebruiker is geen admin, toegang geweigerd
            return redirect()->route('index');
        }

        // Als de gebruiker wel een admin is, doorgaan met het verzoek
        return $next($request);
    }
}
