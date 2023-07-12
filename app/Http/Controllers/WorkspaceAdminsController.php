<?php

namespace App\Http\Controllers;

use App\Models\workspace_admins;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\work_space;
use Illuminate\Support\Str;



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
        $workspace_admin = new workspace_admins;
        $email = $request->input('email');
        $workspace = $request->input('workspace_id');
        $workspace_admin->id = Str::uuid()->toString();

        // Search for the user by email
        $user = User::where('email', $email)->first();
        if ($user) {
            $workspaceID = Work_Space::where('created_by', $workspace)->first()->id;
            $userID = User::where('email', $email)->first()->id;


    
    $workspace_admin->workspace_id=$workspaceID;
    $workspace_admin->user_id = $userID;

    if ($workspace_admin->save()) {
        // Return a success response or any other desired logic
        return response()->json(['message' => 'Workspace admin created successfully']);
    } else {
        // Return an error response if saving the workspace admin fails
        return response()->json(['message' => 'Failed to create workspace admin'], 500);
    }

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
    public function destroy(workspace_admins $workspace_admins)
    {
        //
    }
}
