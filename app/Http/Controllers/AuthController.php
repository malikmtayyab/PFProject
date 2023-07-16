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
use Illuminate\Support\Facades\Redis;





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
                    'message' => 'Email or password is incorrect',
                ]);
            }

            $user = auth()->user();


            // $cookieValue = $request->input('userID');
            $results = work_space::select('work_space.id as workspace_id', 'work_space.workspace_name', 'workspace_admins.user_id as admin_id', 'invitation_tables.id as member_id')
                ->join('workspace_admins', 'work_space.id', '=', 'workspace_admins.workspace_id')
                ->leftJoin('invitation_tables', 'invitation_tables.invited_by', '=', 'workspace_admins.user_id')
                ->where('workspace_admins.user_id', $user->id)
                ->orWhere('invitation_tables.id', $user->id)
                ->first();

            // $workspaceName = $results->workspace_name;
            // $workspaceId = $results->workspace_id;
            // $adminId = $results->admin_id;
            // $memberId = $results->member_id;

            $iv = str_repeat("0", openssl_cipher_iv_length("AES-256-CBC"));
            $encryptedID = openssl_encrypt($user->id, "AES-256-CBC", env("AES_SECRET_ACCESS_KEY"), 0, $iv);
            Redis::set($encryptedID . "@access_token", $token);


            if ($results->workspace_id) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $token,
                    'workspace' => 'true',
                    'user_name' => $user->name,
                    'user_id' => $encryptedID,
                    'workspace_info' => $results
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $token,
                    'workspace' => 'false',
                    'user_name' => $user->name,
                    'user_id' => $encryptedID,
                    'workspace_info' => $results
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal server error',
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
                'error' => '$e'
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
