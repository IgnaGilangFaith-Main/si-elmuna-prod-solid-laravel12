<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsEmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user() && Auth::user()->email_verified_at === null) {
            Auth::logout();
            sweetalert()->warning('Silahkan Verifikasi email terlebih dahulu! Atau hubungi Admin!');

            return redirect('login');
        }

        return $next($request);
    }
}
