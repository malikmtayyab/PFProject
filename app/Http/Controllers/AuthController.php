<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UnverifiedUser;


use Hash;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (! $token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized token not generated'], 401);
       

    }
    return $this->respondWithToken($token);
    // $credentials = $request->only('email', 'password');

    // // Attempt to authenticate the user
    // if (!Auth::attempt($credentials)) {
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }

    // // Retrieve the authenticated user
    // $user = Auth::user();

    // // Check if the user's email is verified
    // if (!$user->hasVerifiedEmail()) {
    //     return response()->json(['error' => 'Email not verified'], 401);
    // }

    // // Generate the JWT token
    // $token = $user->createToken('authToken')->plainTextToken;

    // // Return the token and user information
    // return response()->json([
    //     'access_token' => $token,
    //     'token_type' => 'Bearer',
    //     'expires_in' => auth()->factory()->getTTL() * 60,
    //     'user' => $user
    // ]);
}

    
    public function register(Request $request)
    {
        $credentials = $request->only('name','email', 'password', 'usertype');
        $credentials['password']=Hash::make($credentials['password']);
        User::create($credentials);
        //$token = Auth::attempt($credentials);
        

        return response()->json('sucessfull');
    //     $credentials = $request->only('name', 'email', 'password');
    // $credentials['password'] = Hash::make($credentials['password']);
    
    // $user = User::create($credentials);
    // $user->sendEmailVerificationNotification();
    
    // return response()->json(['message' => 'Registration successful. Please check your email to verify your account.']);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user'=>auth()->user()
        ]);
    }
}