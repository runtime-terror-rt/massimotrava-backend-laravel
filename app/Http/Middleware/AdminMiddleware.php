<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Only allow authenticated users with role = 'admin' or 'super_admin'
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Uncomment when you have a roles column on users table:
        // if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
        //     abort(403, 'Unauthorized');
        // }

        return $next($request);
    }
}
