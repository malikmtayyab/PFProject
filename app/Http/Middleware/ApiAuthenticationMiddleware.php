<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Access_Token_Extractor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;


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
    $token = $request->header('Authorization');
    $parts = explode(' ', $token);
    $token = Redis::get($parts[1]."@access_token");

    if ($token===$parts[2]) {
        $iv = str_repeat("0", openssl_cipher_iv_length("AES-256-CBC"));
        $decryptedID = openssl_decrypt($parts[1], "AES-256-CBC", env("AES_SECRET_ACCESS_KEY"), 0, $iv);
        $request->merge(['userID' => $decryptedID]);
        // Proceed to the next middleware or the requested URI
        return $next($request);

    }
    return response()->json([
        'status' => 'failed',
        'message' => 'Invalid Credentials',
    ], Response::HTTP_BAD_REQUEST);
}

}
