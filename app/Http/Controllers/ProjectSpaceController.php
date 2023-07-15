<?php

namespace App\Http\Controllers;

use App\Models\project_space;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ProjectSpaceController extends Controller
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
            'projectname' => 'required',
            'projectdeadline' => 'required|date',
            'lead_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cookie_value = $request->cookie("LogIn_Session");
        $project_space = new project_space;
        $project_space->id = Str::uuid()->toString();
        $project_space->project_name = $request->input('projectname');
        $project_space->project_status = 'In-Progress';
        $project_space->project_deadline = $request->input('projectdeadline');
        $project_space->project_completion_date = null;
        $project_space->project_completion_percentage = 0;
        $project_space->project_owner = $cookie_value;
        $project_creation_Date = Carbon::now();
        $project_space->project_creation_date = $project_creation_Date;

        $workspaceAdmin = DB::table('workspace_admins')->where('user_id', $cookie_value)->first();

        if (!$workspaceAdmin) {
            return response()->json([
                'status' => "failed",
                'message' => "You are not allowed to create a Project"
            ], Response::HTTP_BAD_REQUEST);
        }

        $leadUser = DB::table('users')->where('email', $request->input('lead_email'))->first();

        if (!$leadUser) {
            return response()->json([
                'status' => "failed",
                'message' => "Incorrect Lead Entered"
            ], Response::HTTP_BAD_REQUEST);
        }
        $project_space->workspace_id = $workspaceAdmin->workspace_id;
        $project_space->lead_id = $leadUser->id;
        $projectDeadline = Carbon::parse($project_space->project_deadline);
        $project_space->overdue = "false";

        if ($project_creation_Date->greaterThan($projectDeadline)) {
            return response()->json([
                "status" => "failed",
                "message" => "Deadline can't be greater than the current date."
            ], Response::HTTP_BAD_REQUEST);
        }
        if ($project_space->save()) {
            $project = project_space::select('project_spaces.*', 'owner.name AS project_owner_name', 'lead.name AS lead_name')
    ->join('users AS owner', 'project_spaces.project_owner', '=', 'owner.id')
    ->join('users AS lead', 'project_spaces.lead_id', '=', 'lead.id')
    ->where('project_spaces.id', $project_space->id)
    ->groupBy('project_spaces.id', 'owner.name', 'lead.name')
    ->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Project created',
                'created_project' => $project
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Project not created',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\project_space  $project_space
     * @return \Illuminate\Http\Response
     */
    public function getSpecificProjectInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|uuid',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $project_members = project_space::select('*')
            ->join('users AS owner', 'project_spaces.project_owner', '=', 'owner.id')
            ->join('users AS lead', 'project_spaces.lead_id', '=', 'lead.id')
            ->join('project_members', 'project_spaces.id', '=', 'project_members.project_id')
            ->join('users', 'project_members.user_id', '=', 'users.id')
            ->where('project_spaces.id', $request->input('project_id'))
            ->get();

        $project_tasks = project_space::select('project_tasks.*')
            ->join('project_tasks', 'project_tasks.project_id','=','project_spaces.id')
            ->where('project_spaces.id', $request->input('project_id'))
            ->get();

        return response()->json([
            'project_members'=>$project_members,
            'project_tasks' => $project_tasks
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_space  $project_space
     * @return \Illuminate\Http\Response
     */
    public function edit(project_space $project_space)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\project_space  $project_space
     * @return \Illuminate\Http\Response
     */
    // public function update_status(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'project_id' => 'required|uuid',
    //         'project_status' => ['required', 'string', Rule::in(['In-Progress', 'Completed'])],
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }
    //     $updatedRows = project_space
    //         ::where('id', $request->input('project_id'))
    //         ->update(['project_status' => $request->input('project_status')]);
    //     if ($updatedRows > 0) {
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Project status updated successfully',
    //         ], Response::HTTP_OK);
    //     } else {
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'No project found with the given ID',
    //         ], Response::HTTP_NOT_FOUND);
    //     }
    // }

    public function update_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|uuid',
            'project_status' => ['required', 'string', Rule::in(['In-Progress', 'Completed'])],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $updatedRows = project_space::where('id', $request->input('project_id'))
                ->update(['project_status' => $request->input('project_status')]);

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

    // public function update_deadline(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'project_id' => 'required|uuid',
    //         'project_deadline' =>  'required|date',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }
    //     $updatedRows = project_space
    //         ::where('id', $request->input('project_id'))
    //         ->update(['project_deadline' => $request->input('project_deadline')]);
    //     if ($updatedRows > 0) {
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Project Deadline updated successfully',
    //         ], Response::HTTP_OK);
    //     } else {
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'No project found with the given ID',
    //         ], Response::HTTP_NOT_FOUND);
    //     }
    // }
    public function update_deadline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|uuid',
            'project_deadline' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $updatedRows = project_space::where('id', $request->input('project_id'))
                ->update(['project_deadline' => $request->input('project_deadline')]);

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
            'project_id' => 'required|uuid',
            'completion_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $project = project_space::find($request->input('project_id'));

        if (!$project) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No project found with the given ID',
            ], Response::HTTP_NOT_FOUND);
        }

        $deadline = Carbon::parse($project->project_deadline);
        $completionDate = Carbon::parse($request->input('completion_date'));

        if ($completionDate->greaterThan($deadline)) {
            $project->overdue = true;
        } else {
            $project->overdue = false;
        }

        $project->project_completion_date = $request->input('completion_date');
        $project->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Project Deadline updated successfully',
        ], Response::HTTP_OK);
    }


    public function update_overdue(bool $overdue, string $project_id)
    {
        try {
            $updatedRows = project_space::where('id', $project_id)
                ->update(['overdue' => $overdue]);
            if ($updatedRows > 0) {
                return true;
            } else {
                return false;
            }
        } catch (QueryException $e) {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_space  $project_space
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // {
        //     $validator = Validator::make($request->all(), [
        //         'project_id' => 'string'
        //     ]);

        //     if ($validator->fails()) {
        //         return response()->json($validator->errors(), 400);
        //     }
        //     $deletedRows = project_space::where('id', $request->input('project_id'))->delete();
        //     if ($deletedRows > 0) {
        //         // Project deleted successfully
        //         return response()->json(['message' => 'Project deleted successfully'], Response::HTTP_OK);
        //     } else {
        //         // Project not found or deletion failed
        //         return response()->json(['message' => 'Project not found or deletion failed'], Response::HTTP_NOT_FOUND);
        //     }
        $validator = Validator::make($request->all(), [
            'project_id' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $deletedRows = project_space::where('id', $request->input('project_id'))->delete();

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
