<?php

namespace App\Http\Middleware;

use App\Enum\AccessApp;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AccessMiddlewareRequisition
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
            return $next($request);
        }
        $session = Session::get('module');
        if ($session == AccessApp::Requisition) {
            return $next($request);
        }
        abort(403, 'You do not have access to this module.');

    }
}
