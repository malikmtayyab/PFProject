<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;



class ApiAuthenticationController extends Controller
{
    public function checkAPICookie(Request $request){
        $cookie_value = $request->cookie("LogIn_Session");
        $sessionValue = Access_Token_Extractor::getSessionValue('login_session');

        if (!$request->hasCookie('LogIn_Session') || $cookie_value === null || $sessionValue === null || $sessionValue !== $cookie_value) {
            Access_Token_Extractor::destroySession();

            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid Cookies or No Session Found',
            ])->cookie("LogIn_Session", null, -1);
        }
        if($cookie_value === $sessionValue){
            return response()->json([
                'status' => 'successful'
            ], Response::HTTP_OK);
        }
        else{
            return response()->json([
                'status' => 'failure'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
