<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = $request->user();

        // Jika user belum login
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $userRole = $user->role;

        // 🔹 Super admin bisa mengakses SEMUA role
        if ($userRole === 'super_admin') {
            return $next($request);
        }

        // 🔹 Cek apakah role yang dibutuhkan cocok
        // Bisa juga pakai multiple role (admin_bidang|verifikator1|dst)
        $allowedRoles = explode('|', $role);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
