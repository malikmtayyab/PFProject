<?php

namespace App\Http\Controllers;

use App\Models\workspace_admins;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\work_space;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;




class WorkspaceAdminsController extends Controller
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
        // Validate the request data
        $validator = Validator::make($request->all(), [
            "admin_email" => 'required'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        // Retrieve the cookie value and session value
            $cookie_value = $request->input('userID');
            $workspace_admin = new workspace_admins;

            // Find the user by email
            $user = User::where('email', $request->input('admin_email'))->first();

            if ($user) {
                // Find the workspace ID for the current user
                $workspaceID = workspace_admins::where('user_id', $cookie_value)->first()->workspace_id;

                // Set the properties of the workspace admin
                $workspace_admin->id = Str::uuid()->toString();
                $workspace_admin->workspace_id = $workspaceID;
                $workspace_admin->user_id = $user->id;

                // Save the workspace admin
                if ($workspace_admin->save()) {
                    // Return a success response
                    return response()->json(['message' => 'Workspace admin created successfully']);
                } else {
                    // Return an error response if saving the workspace admin fails
                    return response()->json(['message' => 'Failed to create workspace admin'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                // Return an error response if the admin email is incorrect
                return response()->json([
                    'status' => 'failed',
                    'message' => "Entered Admin Email is incorrect.",
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\workspace_admins  $workspace_admins
     * @return \Illuminate\Http\Response
     */
    public function show(workspace_admins $workspace_admins)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\workspace_admins  $workspace_admins
     * @return \Illuminate\Http\Response
     */
    public function edit(workspace_admins $workspace_admins)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\workspace_admins  $workspace_admins
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, workspace_admins $workspace_admins)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\workspace_admins  $workspace_admins
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {


    }
}
