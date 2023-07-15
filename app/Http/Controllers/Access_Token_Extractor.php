<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Payload;

class Access_Token_Extractor{

    static function tokenExtractor($token):string{
        // Remove the "Bearer " prefix from the token.
        $token = str_replace('Bearer ', '', $token);
        $secret = env('JWT_SECRET');

        $decodedToken = JWTAuth::parseToken($token, $secret);

       return $decodedToken->getPayload()->get('sub');
    }

    static function getSessionValue($key): string|null {
        $session = session();
        return $session->get($key, null);
    }
    static function destroySession(){
        $session = session();
        $session->forget("login_session");
    }
}
