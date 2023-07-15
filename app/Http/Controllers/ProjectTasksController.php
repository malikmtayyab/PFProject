<?php

namespace App\Http\Controllers;

use App\Models\project_space;
use App\Models\project_tasks;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
            // 'task_completion_date' => 'required|date',
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
            // $project_tasks->creator_id = $request->input('LogIn_Session');
            $project_tasks->task_status = 'In-Progress';
            $project_tasks->task_priority = $request->input('task_priority');
            $project_tasks->task_deadline = $request->input('task_deadline');
            $project_tasks->task_completion_date = '2023-07-20';
            $task_creation_Date = Carbon::now();

            $project_tasks->task_creation_date = $task_creation_Date;

            $projectDeadline = Carbon::parse($project_tasks->task_deadline);
            $project_tasks->task_overdue = 'false';

            if ($task_creation_Date->greaterThan($projectDeadline)) {
                return response()->json([
                    "status" => "failed",
                    "message" => "Deadline can't be greater than the current date."
                ], Response::HTTP_BAD_REQUEST);
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

    public function update_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|uuid',
            'task_status' => ['required', 'string', Rule::in(['In-Progress', 'Completed'])],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $updatedRows = project_tasks::where('id', $request->input('task_id'))
                ->update(['task_status' => $request->input('task_status')]);

            if ($updatedRows > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Project status updated successfully',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No project found with the given ID',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Error occurred while updating the project status',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update_deadline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|uuid',
            'task_deadline' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $updatedRows = project_tasks::where('id', $request->input('task_id'))
                ->update(['task_deadline' => $request->input('task_deadline')]);

            if ($updatedRows > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Project Deadline updated successfully',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No project found with the given ID',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Error occurred while updating the project deadline',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function add_completion_date(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|uuid',
            'completion_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $tasks = project_tasks::find($request->input('task_id'));

        if (!$tasks) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Task found with the given ID',
            ], Response::HTTP_NOT_FOUND);
        }

        $deadline = Carbon::parse($tasks->task_deadline);
        $completionDate = Carbon::parse($request->input('completion_date'));

        if ($completionDate->greaterThan($deadline)) {
            $tasks->overdue = true;
        } else {
            $tasks->overdue = false;
        }

        $tasks->project_completion_date = $request->input('completion_date');

        $taskCounts = project_tasks::select('task_status', DB::raw('COUNT(*) as count'))
            ->where('project_id', $tasks->project_id)
            ->groupBy('task_status')
            ->get();
        $inProgress = 0;
        $completed = 0;

        foreach ($taskCounts as $taskCount) {
            if ($taskCount->task_status === 'In-Progress') {
                $inProgress = $taskCount->count ?? 0;
            } elseif ($taskCount->task_status === 'Completed') {
                $completed = $taskCount->count ?? 1;
            }
        }

        $totalTasks = $inProgress + $completed;
        $finalPercentage = $totalTasks > 0 ? ($inProgress / $totalTasks) * 100 : 0;
        $updatedRows = project_space::where('id', $tasks->project_id)
            ->update(['project_completion_percentage' => $finalPercentage]);
        $tasks->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Project Deadline updated successfully',
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_tasks  $project_tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $deletedRows = project_tasks::where('id', $request->input('task_id'))->delete();

            if ($deletedRows > 0) {
                // Project deleted successfully
                return response()->json(['message' => 'Project deleted successfully'], Response::HTTP_OK);
            } else {
                // Project not found or deletion failed
                return response()->json(['message' => 'Project not found or deletion failed'], Response::HTTP_NOT_FOUND);
            }
        } catch (QueryException $e) {
            // Exception occurred during database operation
            return response()->json(['message' => 'Error occurred while deleting the project'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
