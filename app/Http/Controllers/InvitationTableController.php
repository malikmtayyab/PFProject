<?php

namespace App\Http\Controllers;

use App\Models\invitation_table;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationEmail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ExistingUserEmail;
use App\Http\Controllers\EmailSender;

use App\Http\Controllers\Access_Token_Extractor;


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
            'email' => 'required|email',
            // 'invitedby' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        // Get the token from the request.
        // $extracted_token = Access_Token_Extractor::tokenExtractor($request->input('access_token'));
        // $sessionValue = Access_Token_Extractor::getSessionValue('jwt_session');
        // if ($sessionValue == null) {
        //     return response()->json([
        //         'status' => 'failed',
        //         'message' => "No Session Found",
        //     ]);
        // }
        // if ($extracted_token == $sessionValue) {
        try {
            $finding_user = User::where('email', $request->input('email'))->first();
            $workspace = DB::table('work_space')
                ->join('workspace_admins', 'work_space.id', '=', 'workspace_admins.workspace_id')
                ->where('workspace_admins.user_id', $request->cookie('LogIn_Session')) // Replace 'John Doe' with the desired admin name
                ->select('work_space.workspace_name')
                ->get();
            $invitedByID = DB::table('users')
                ->where('id', $request->cookie('LogIn_Session'))
                ->first(['email']);
            // return([$invitedByID, $workspace, $finding_user]);
            if ($finding_user) {
                $invitation_table = new invitation_table;
                $invitation_table->id = $finding_user->id;
                $invitation_table->invited_by = $request->cookie('LogIn_Session');
                if ($invitation_table->save()) {

                    $emailSent = EmailSender::sendEmail(
                        $finding_user->email,
                        $invitedByID->email,
                        "You have been added to a New Team.",
                        $workspace[0]->workspace_name
                    );
                    if ($emailSent == 1) {
                        return response()->json(['message' => 'Email sent successfully', 'status' => 'success']);
                    } else {
                        return response()->json(['message' => 'Email Not sent successfully', 'status' => 'Failed']);
                    }
                }
            } else {
                $user = new User;
                $user->id = Str::uuid()->toString();
                $user->email = $request->input('email');
                $password = Str::random(8);
                $user->password = Hash::make($password);

                if ($user->save()) {
                    $invitation_table = new invitation_table;
                    $invitation_table->id = $user->id;
                    $invitation_table->invited_by = $request->cookie('LogIn_Session');

                    if ($invitation_table->save()) {
                        // Send welcome email to the user
                        // Email will be added in this section

                        EmailSender::sendEmailToRegister(
                            $user->email,
                            $password,
                            $invitedByID->email,
                            "You have been added to a New Team.",
                            $workspace[0]->workspace_name
                        );

                        return response()->json([
                            'status' => 'success',
                            'message' => "user created successfully",
                            $invitedByID, $workspace, $finding_user, "Inside the Else"
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
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "error occur",
                'error' => $e
            ]);
        }
        // } else {
        //     // Remove the 'jwt_session' value from the session
        //     session()->forget('jwt_session');
        //     return response()->json([
        //         'status' => 'failed',
        //         'message' => "Invalid session",
        //     ]);
        // }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invitation_table  $invitation_table
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $cookieValue = $request->cookie('LogIn_Session');
        $members =  invitation_table::select(
            'users.name as admin_name',
            'users.email as admin_email',
            'users.id as admin_id',
            'iu.name as member_name',
            'iu.email as member_email',
            'users.*',
            'iu.*'
        )
            ->join('workspace_admins', 'workspace_admins.user_id', '=', 'invitation_tables.invited_by')
            ->join('users as iu', 'iu.id', '=', 'invitation_tables.id')
            ->join('users', 'workspace_admins.user_id', '=', 'users.id')
            ->distinct()
            ->where('workspace_admins.user_id', $cookieValue)
            ->get();

            if($members){
                return response()->json([
                    'status'=> 'failed',
                    "project_members" => $members
                 ]);
            }
        return response()->json([
            'status' => 'success',
           "project_members" => $members
        ]);
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
