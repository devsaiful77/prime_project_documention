<?php

namespace App\Http\Middleware;

use App\Enum\AccessApp;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AccessMiddlewareService
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // if (!$user) {
        //     abort(403, 'Unauthorized');
        // }

        if (!$user) {
            return $next($request); // Let login page or guest view load
        }

        if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
            return $next($request);
        }
        $session = Session::get('module');

        if ($session == AccessApp::ServiceComplaint) {
            return $next($request);
        }
	
        abort(403, 'You do not have access to this module.');
    }
}
