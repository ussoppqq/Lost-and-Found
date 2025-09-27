<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Cek apakah user memiliki role admin
        // Sesuaikan dengan struktur role_code di model Role Anda
        if (!$user->role || !in_array($user->role->role_code, ['ADMIN', 'MODERATOR'])) {
            abort(403, 'Access denied. Admin access required.');
        }

        return $next($request);
    }
}