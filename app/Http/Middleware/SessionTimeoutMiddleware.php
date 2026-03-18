<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeoutMiddleware
{
    /**
     * Session timeout in seconds (30 minutes).
     */
    protected int $timeout = 1800;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('last_activity')) {
            $lastActivity = $request->session()->get('last_activity');

            if (time() - $lastActivity > $this->timeout) {
                $request->session()->flush();

                return redirect('/login')
                    ->with('error', 'Sesi Anda telah berakhir karena tidak aktif');
            }
        }

        $request->session()->put('last_activity', time());

        return $next($request);
    }
}
