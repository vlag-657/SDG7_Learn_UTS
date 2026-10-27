<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $hasAccess = false;

        if ($role === 'admin') {
            $hasAccess = Auth::guard('admin')->check() || ($request->user() && $request->user()->role === 'admin');
        } else {
            $hasAccess = $request->user() && $request->user()->role === $role;
        }

        if (! $hasAccess) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak berhak mengakses endpoint ini',
                    'error'   => ['code' => 'FORBIDDEN', 'details' => []],
                ], 403);
            }

            return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
        }

        return $next($request);
    }
}