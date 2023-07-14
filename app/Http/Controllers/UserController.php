<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function addUserName(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' =>'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Fill all the fields',
            ]);
        }
        $cookie_value = $request->cookie("LogIn_Session");
            try {
                $user = User::where('id', $cookie_value)->update(['name' => $request->input('user_name')]);

                if ($user) {
                    return response()->json([
                        'status' => 'successful'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 'failed'
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'An error occurred while updating the user'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    }

    public function changePassword(Request $request){

        $validator = Validator::make($request->all(), [
            'new_password' =>'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Fill all the fields',
            ]);
        }
        $cookie_value = $request->cookie("LogIn_Session");
            try {
                $user = User::where('id', $cookie_value)->update(['password' => Hash::make($request->input('new_password'))]);

                if ($user) {
                    return response()->json([
                        'status' => 'successful'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 'failed'
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'An error occurred while updating the user'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
}
