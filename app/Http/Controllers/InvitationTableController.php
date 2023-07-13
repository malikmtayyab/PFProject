<?php

namespace App\Http\Controllers;

use App\Models\invitation_table;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationEmail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ExistingUserEmail;


class InvitationTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{

    $validator = Validator::make($request->all(), [
        'access_token' => 'required',
        'email' => 'required|email',
        'invitedby' => 'required',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()], 400);
    }

     // Get the token from the request.
     $extracted_token = Access_Toekn_Extractor::tokenExtractor($request->input('access_token'));
     $sessionValue = Access_Toekn_Extractor::getSessionValue('jwt_session');
     if($sessionValue==null){
        return response()->json([
            'status' => 'failed',
            'message' => "No Session Found",
        ]);
     }
     if($extracted_token==$sessionValue){
        try {
            $user = User::where('email', $request->input('email'))->first();

            if ($user) {
                return response()->json(['message' => 'Email already exists in users table']);
            }

            $user = new User;
            $user->id = Str::uuid()->toString();
            $user->email = $request->input('email');
            $user->password = Hash::make(Str::random(8));

            if ($user->save()) {
                $invitation_table = new invitation_table;
                $invitation_table->id = $user->id;
                $invitation_table->invited_by = $extracted_token;

                if ($invitation_table->save()) {
                    $invitationDetails = [
                        'email' => $user->email,
                        'password' => $user->password,
                        'invitedBy' => $invitation_table->invited_by,
                    ];

                      // Send welcome email to the user
                    //   Email will be added in this section
                    return response()->json([
                    'status' => 'success',
                    'message' => "user created successfully",
                ]);
            } else {
                    $user->delete();
                    return response()->json([
                        'status' => 'failed',
                        'message' => "user creation fail",
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => "user creation fail",
                ]);
             }}
              catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "error occur",
            ]);
        }
     }
     else{
        // Remove the 'jwt_session' value from the session
        session()->forget('jwt_session');
        return response()->json([
            'status' => 'failed',
            'message' => "Invalid session",
        ]);
     }


}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invitation_table  $invitation_table
     * @return \Illuminate\Http\Response
     */
    public function show(invitation_table $invitation_table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invitation_table  $invitation_table
     * @return \Illuminate\Http\Response
     */
    public function edit(invitation_table $invitation_table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invitation_table  $invitation_table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invitation_table $invitation_table)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invitation_table  $invitation_table
     * @return \Illuminate\Http\Response
     */
    public function destroy(invitation_table $invitation_table)
    {
        //
    }
}