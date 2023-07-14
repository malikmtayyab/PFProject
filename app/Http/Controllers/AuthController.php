<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\invitation_table;
use App\Models\User;
use App\Models\workspace_admins;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\work_space;


use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;





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
            return response()->json([
                'status' => 'failed',
                'message' => 'Fill all the fields',
            ]);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = auth()->attempt($credentials)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Email not exist',
                ]);
            }

            $user = auth()->user();

            // Check if user ID exists in Work_Space table
            $workspace = workspace_admins::where('user_id', $user->id)->first();
            $workspace_member = invitation_table::where('id', $user->id)->first();
            //Creating Session
            $session = session();
            $session->put("login_session", $user->id);
            $session->save();

            // $payload = JWTFactory::sub($user->id)->expiresAfter(now()->addDays(1))->make();
            // $token = JWTAuth::encode($payload)->get();


            if ($workspace || $workspace_member) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $token,
                    'workspace' => 'true',
                    'user_name'=>$user->name
                ])->cookie("LogIn_Session", $session->get("login_session"), 60);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $token,
                    'workspace' => 'false',
                    'user_name'=>$user->name
                ])->cookie("LogIn_Session", $session->get("login_session"), 60);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Incorrect email',
                'error' => $e
            ]);
        }
    }

    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => "Fill all the field",
            ]);
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
            ]);
        } catch (\Illuminate\Database\QueryException $e) {

            // Default error response
            return response()->json([
                'status' => 'failed',
                'message' => 'Email already exist',
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


        $items = User::find("f2e3c6ad-fa17-4bd5-a22f-d336af400b72");
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

        return response()->json([
            'status' => 'success',
            'message' => "Successfully logged out",
        ]);
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
            'users' => auth()->user()
        ]);
    }
}
