<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UnverifiedUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


use Hash;
use Tymon\JWTAuth\JWTGuard;
use Tymon\JWTAuth\Token as JWTToken;
use Tymon\JWTAuth\Payload;
use Tymon\JWTAuth\Facades\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;





class AuthController extends Controller
{

    // public $globalVariable;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized token not generated'], 401);
        }

        $user = auth()->user();
        $sessionId = $user->getKey();
        $session = session();
        $session->put('jwt_session', $sessionId);
        return $this->respondWithToken($token);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = new User;
        $user->id = Str::uuid()->toString();
        $credentials = $request->only('name', 'email', 'password');
        $credentials['password'] = Hash::make($credentials['password']);
        $user->fill($credentials);
        $user->save();

        return response()->json('successful');
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
            'access_token'=>$token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
            'sessionKey'=> Session::get("jwt_session"),
        ]);
    }

    /*
        To access the below method first remove the middleware present in constructor of
        this class. This code will be used throughout different APIs.
    */

  /**
 * This function gets the session ID from the JWT token.
 *
 * @param Request $request The request object.
 *
 * @return array An array with the session ID and the session from the session store.
 */
function authorizeJWT_Session(Request $request)
{
    // Get the token from the request.
    $token = $request->input('token');

    // Remove the "Bearer " prefix from the token.
    $token = str_replace('Bearer ', '', $token);

    // Get the secret key from the .env file.
    $secret = env('JWT_SECRET');

    // Decode the token using the secret key.
    $decodedToken = JWTAuth::parseToken($token, $secret);

    // Get the session ID from the token payload.
    $sessionID = $decodedToken->getPayload()->get('sub');

    // Get the session from the session store.
    $session = session();

    // Return an array with the session ID and the session from the session store.
    return [
        'sessionIDFromToken' => $sessionID,
        'sessionFromSomething' => $session->get('jwt_session'),
    ];
}
}
