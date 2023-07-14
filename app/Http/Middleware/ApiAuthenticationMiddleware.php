<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Access_Token_Extractor;
use Closure;
use Illuminate\Http\Request;

class ApiAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
{
    $cookieName = 'LogIn_Session'; // Specify the name of the cookie you want to check

    if ($request->hasCookie($cookieName)) {
        $cookieValue = $request->cookie($cookieName);
        $sessionValue = Access_Token_Extractor::getSessionValue('login_session');

        // Check if the cookie value matches the session value
        if ($cookieValue === $sessionValue) {
            // Proceed to the next middleware or the requested URI
            return $next($request);
        }
    }
    // If the cookie is not found or the values don't match, return a specific response
    Access_Token_Extractor::destroySession();
    return response()->json([
        'status' => 'failed',
        'message' => 'Invalid Cookies or No Session Found',
    ])->cookie($cookieName, null, -1);
}

}
