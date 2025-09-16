<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // Jika request mengharapkan JSON, kirim respons JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            // Jika bukan JSON, redirect ke halaman tertentu
            return redirect()->route('accepted.events')->with('error', 'Unauthorized action.');
        }

        return $next($request);
    }
}
