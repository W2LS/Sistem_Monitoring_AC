<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('logged_in')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sesi login telah berakhir. Silakan login kembali.'
                ], 401);
            }

            return redirect()->route('login')->withErrors([
                'login_error' => 'Silakan masuk (login) terlebih dahulu untuk menguji dashboard.'
            ]);
        }

        return $next($request);
    }
}
