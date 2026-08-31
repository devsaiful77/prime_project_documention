<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CITokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authSessionToken = session()->get('auth_token');
        $userToken = "";

        if ($request->get('CIToken')){
            $userToken = $request->get('CIToken');
        }elseif ($request->get('ci_token')){
            $userToken = $request->get('ci_token');
        }
        if (!$request->ajax()){
            if($userToken !== $authSessionToken){
                Log::error('CITokenAuth Middleware Error', ['message' => 'invalid access token.']);
                return abort(404);
            }
        }

        return $next($request);
    }
}
