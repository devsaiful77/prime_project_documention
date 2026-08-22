<?php

namespace App\Http\Middleware;

use Closure;

class SetProtectionHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	$nonce = $request->header('csp-nonce');
	//dd($request->header());
	if(!$nonce){
	    $nonce = base64_encode(random_bytes(16));
	}

	app()->instance('csp_nonce', $nonce);

        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'nonce-{$nonce}'; style-src 'self' 'unsafe-inline'; img-src * data: blob:;");
        $response->headers->set(
            'Strict-Transport-Security', 
            'max-age=31536000; includeSubDomains; preload'
        );
        //$response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        //$response->headers->set('Pragma', 'no-cache');
        $response->headers->remove('X-Powered-By');
        return $response;
    }
}
