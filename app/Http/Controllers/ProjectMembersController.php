<?php

namespace App\Http\Controllers;

use App\Models\project_members;
use App\Models\project_space;
use Illuminate\Database\QueryException;
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
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $users = User::select('users.name', 'users.email', 'project_spaces.project_name')
            ->join('project_members', 'users.id', '=', 'project_members.user_id')
            ->join('project_spaces', 'project_spaces.id', '=', 'project_members.project_id')
            ->where('project_spaces.id', $request->input('project_id'))
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No users found for the given project.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ], Response::HTTP_OK);
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
    public function delete(Request $request)
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
            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User with the provided email does not exist.',
                ], Response::HTTP_NOT_FOUND);
            }

            $projectMembersDeleted = project_members::where('project_id', $request->input('project_id'))
                ->where('user_id', $user->id)
                ->delete();

            if ($projectMembersDeleted) {
                $affectedRows = project_space::join('project_tasks', 'project_spaces.id', '=', 'project_tasks.project_id')
                    ->join('task_assignments', 'project_tasks.id', '=', 'task_assignments.task_id')
                    ->where('project_spaces.id', $request->input('project_id'))
                    ->where('task_assignments.assigned_id', $user->id)
                    ->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Project member deleted successfully.',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No project member found with the given project ID and user ID.',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while deleting the project member.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
