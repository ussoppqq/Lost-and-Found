<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cek apakah user memiliki role
        if (!auth()->user()->role) {
            abort(403, 'Unauthorized access. No role assigned.');
        }

        // Cek apakah role_code adalah 'ADMIN'
        if (auth()->user()->role->role_code !== 'ADMIN') {
            abort(403, 'Unauthorized access. Admin only.');
        }

        return $next($request);
    }
}