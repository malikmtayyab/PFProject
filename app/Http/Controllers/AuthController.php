<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UnverifiedUser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Models\work_space;


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
        // $this->middleware('auth:api', ['except' => ['login','register']]);
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
            'password' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (! $token = auth()->attempt($credentials)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Email not exist',
                ]);
            }

            $user = auth()->user();

            // Check if user ID exists in Work_Space table
            $workspace = Work_Space::where('created_by', $user->id)->first();
            //Creating Session
            $session = session();
            $session->put("jwt_session", $user->getKey());
            $session->save();
            if ($workspace) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $token,
                    'workspace' => 'true',
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $token,
                    'workspace' => 'false',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Incorrect email',
            ]);
        }
    }

public function register(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    try {
        $user = new User;
        $user->id = Str::uuid()->toString();
        $credentials = $request->only('name', 'email', 'password');
        $credentials['password'] = Hash::make($credentials['password']);
        $user->fill($credentials);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => "Accout created",
        ]);    } catch (\Illuminate\Database\QueryException $e) {
        $errorCode = $e->errorInfo[1];

        // Check if the email already exists
        if ($errorCode == 1062) {

            return response()->json([
                'status' => 'failed',
                'message' => "email already exist",
            ]);
        }

        // Handle other exceptions if needed

        // Default error response
        return response()->json([
            'status' => 'failed',
            'message' => "email already exist",
        ]);
    }
}




        // $items= installment::get();
        // return response()->json([
        //     'items' => $items,
        // ]);

    public function show()
    {
        // $user = new User;


        $items= User::find("f2e3c6ad-fa17-4bd5-a22f-d336af400b72");
        return response()->json([
            'items' => $items,
        ]);

        // $items= installment::get();
        // return response()->json([
        //     'items' => $items,
        // ]);
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
