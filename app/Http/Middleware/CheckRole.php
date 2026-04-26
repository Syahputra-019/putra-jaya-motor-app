<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Kalau belum login, tendang ke login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Kalau dia Admin, persilakan masuk ke mana aja (God Mode)
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Kalau bukan admin, cek apakah rolenya sesuai dengan izin rute
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Akses Ditolak! Lu gak punya izin buat masuk ke halaman ini bro.');
        }
        return $next($request);
    }
}
