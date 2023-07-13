<?php

namespace App\Http\Controllers;

use App\Models\task_assignment;
use Illuminate\Http\Request;
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
    public function show(task_assignment $task_assignment)
    {
        //
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
    public function destroy(task_assignment $task_assignment)
    {
        //
    }
}
