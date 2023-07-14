<?php

namespace App\Http\Controllers;

use App\Models\project_members;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use \Exception;

class ProjectMembersController extends Controller
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
            'project_id' => 'required',
            'email' => 'required|email',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $project_members = new project_members;
            $project_members->id = Str::uuid()->toString();
            $project_members->project_id = $request->input('project_id');
            // Retrieve the user based on the email
            $user = User::where('email', $request->input('email'))->first();

            if ($user) {
                $project_members->user_id = $user->id;
                $project_members->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Project member added successfully.',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User with the provided email does not exist.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while adding project member.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\project_members  $project_members
     * @return \Illuminate\Http\Response
     */
    public function show(project_members $project_members)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_members  $project_members
     * @return \Illuminate\Http\Response
     */
    public function edit(project_members $project_members)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\project_members  $project_members
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, project_members $project_members)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_members  $project_members
     * @return \Illuminate\Http\Response
     */
    public function destroy(project_members $project_members)
    {
        //
    }
}
