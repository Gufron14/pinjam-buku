<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoanHistory;
use Symfony\Component\HttpFoundation\Response;

class DendaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika User dengan role user yang sedang login mempunyai loan history terlambat, maka tampilkan halaman denda
        if (Auth::check() && Auth::user()->hasRole('user')) {
            // Cek apakah user memiliki pinjaman yang terlambat dan belum bayar denda
            $hasUnpaidFine = LoanHistory::where('id_user', Auth::id())->where('status', 'terlambat')->where('denda_dibayar', false)->where('denda', '>', 0)->exists();

            // Jika mengakses halaman denda
            if ($request->routeIs('denda')) {
                // Jika tidak ada denda, redirect ke home
                if (!$hasUnpaidFine) {
                    return redirect()->route('/');
                }
                // Jika ada denda, izinkan akses
                return $next($request);
            }

            if ($hasUnpaidFine) {
                // Jika sedang mengakses logout, izinkan
                if ($request->routeIs('logout')) {
                    return $next($request);
                }

                // Jika sedang mengakses profil, izinkan
                if ($request->routeIs('profil')) {
                    return $next($request);
                }

                // Redirect ke halaman denda untuk route lainnya
                return redirect()->route('denda');
            }
        }

        return $next($request);
    }
}
