<?php

namespace App\Http\Controllers;

use App\Models\project_tasks;
use App\Models\task_assignment;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TaskAssignmentController extends Controller
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
            'task_id' => 'required',
            'email' => 'required|email',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ]);
        }

        try {
            $task_assignment = new task_assignment;
            $task_assignment->id = Str::uuid()->toString();
            $task_assignment->task_id = $request->input('task_id');
            $email = $request->input('email');

            // Retrieve the user based on the email
            $user = User::where('email', $email)->first();

            if ($user) {
                $task_assignment->user_id = $user->id;
                $task_assignment->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Task assignment created successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User with the provided email does not exist.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while creating the task assignment.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\task_assignment  $task_assignment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|uuid',
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ]);
        }

        $taskAssignments = project_tasks::select('users.*')
            ->join('task_assignments', 'task_assignments.task_id', '=', 'project_tasks.id')
            ->join('users', 'task_assignments.assigned_id', '=', 'users.id')
            ->where('project_tasks.id', $request->input('task_id'))
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Members Found.',
            'members' => $taskAssignments
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\task_assignment  $task_assignment
     * @return \Illuminate\Http\Response
     */
    public function edit(task_assignment $task_assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\task_assignment  $task_assignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, task_assignment $task_assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\task_assignment  $task_assignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|uuid',
            'member_id' => 'required|uuid',

        ]);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ]);
        }

        try {
            $deletedRows = task_assignment::
                        where('assigned_id', $request->input('member_id'))
                        ->where('task_id', $request->input('task_id'))
                        ->delete();

            if ($deletedRows > 0) {
                // Project deleted successfully
                return response()->json(['message' => 'Task deleted successfully'], Response::HTTP_OK);
            } else {
                // Project not found or deletion failed
                return response()->json(['message' => 'Task not found or deletion failed'], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            // Exception occurred during database operation
            return response()->json(['message' => 'Error occurred while deleting the Task Member'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
