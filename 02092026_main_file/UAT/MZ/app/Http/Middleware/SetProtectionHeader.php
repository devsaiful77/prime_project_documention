<?php



namespace App\Http\Middleware;

use Closure;


class SetProtectionHeader{

	 public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'DENY');
        // $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: blob:; object-src 'none';");
        $response->headers->set('Content-Security-Policy', "default-src *; script-src * 'unsafe-inline'; style-src * 'unsafe-inline'; img-src * data: blob:;");
        // $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: blob:; object-src 'none';");
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        //$response->headers->remove('X-Powered-By');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // Only include security headers if needed in PHP
        if (!app()->runningInConsole()) {
             $response->headers->remove('X-Powered-By');
        }
        return $response;
    }

}
