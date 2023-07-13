<?php

namespace App\Http\Controllers;

use App\Models\project_tasks;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProjectTasksController extends Controller
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
            'task_name' => 'required',
            'project_id' => 'required',
            'task_status' => 'required',
            'task_priority' => 'required',
            'task_completion_date' => 'required|date',
            'task_deadline' => 'required|date',
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
            $project_tasks = new project_tasks;
            $project_tasks->id = Str::uuid()->toString();
            $project_tasks->task_name = $request->input('task_name');
            $project_tasks->project_id = $request->input('project_id');
            $project_tasks->task_status = $request->input('task_status');
            $project_tasks->task_priority = $request->input('task_priority');
            $project_tasks->task_completion_date = $request->input('task_completion_date');
            $project_tasks->task_deadline = $request->input('task_deadline');
        
            $project_tasks->task_creation_date = Carbon::now();
        
            $projectDeadline = Carbon::parse($project_tasks->task_deadline);
            $projectCompletionDate = Carbon::parse($project_tasks->task_completion_date);
        
            // Set overdue field based on completion date and deadline
            if ($projectCompletionDate->greaterThan($projectDeadline)) {
                $project_tasks->task_overdue = 'true';
            } else {
                $project_tasks->task_overdue = 'false';
            }
        
            $project_tasks->save();
        
            return response()->json([
                'status' => 'success',
                'message' => 'Project task created successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while creating the project task.',
                'error' => $e->getMessage(),
            ]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\project_tasks  $project_tasks
     * @return \Illuminate\Http\Response
     */
    public function show(project_tasks $project_tasks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_tasks  $project_tasks
     * @return \Illuminate\Http\Response
     */
    public function edit(project_tasks $project_tasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\project_tasks  $project_tasks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, project_tasks $project_tasks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_tasks  $project_tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy(project_tasks $project_tasks)
    {
        //
    }
}
