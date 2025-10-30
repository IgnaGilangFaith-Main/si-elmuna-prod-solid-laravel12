<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsBlockedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->blocked_at !== null) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            sweetalert()->error('Akun Anda telah diblokir. Silakan hubungi administrator.');

            return redirect('/login');
        }

        return $next($request);
    }
}
