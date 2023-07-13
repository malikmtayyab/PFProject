<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWTAuth;


class Access_Toekn_Extractor{

    static function tokenExtractor($token):string{
        // Remove the "Bearer " prefix from the token.
        $token = str_replace('Bearer ', '', $token);
        $secret = env('JWT_SECRET');

        $decodedToken = JWTAuth::parseToken($token, $secret);

       return $decodedToken->getPayload()->get('sub');
    }

    static function getSessionValue($key):string{
        $session = session();
        return $session->get($key);
    }
}
