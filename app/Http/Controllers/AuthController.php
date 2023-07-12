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


class AuthController extends Controller
{
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
        $credentials = $request->only('name', 'email', 'password', 'usertype');
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
            'message' => "an error occur due to some issue",
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
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user'=>auth()->user()
        ]);
    }
}
