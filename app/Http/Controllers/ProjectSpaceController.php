<?php

namespace App\Http\Controllers;

use App\Models\project_space;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


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
            'access_token' => 'required',
            'projectname' => 'required',
            'projectstatus' => 'required',
            'projectdeadline' => 'required|date',
            'projectcompletiondate' => 'required|date',
            'projectcompletionpercentage' => 'required',
            'projectowner' => 'required',
            'email' => 'required|email',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Get the token from the request.
        $extracted_token = Access_Toekn_Extractor::tokenExtractor($request->input('access_token'));
        $sessionValue = Access_Toekn_Extractor::getSessionValue('jwt_session');
        if ($sessionValue == null) {
            return response()->json([
                'status' => 'failed',
                'message' => "No Session Found",
            ]);
        }
        if($sessionValue==$extracted_token)
        {
            $project_space = new project_space;
            $project_owner = $request->input('projectowner');
            $project_space->project_name = $request->input('projectname');
            $project_space->project_status = $request->input('projectstatus');
            $project_space->project_deadline = $request->input('projectdeadline');
            $project_space->project_completion_date = $request->input('projectcompletiondate');
            $project_space->project_completion_percentage = $request->input('projectcompletionpercentage');
            $project_space->project_owner = $project_owner; // Save projectowner value as project_owner
            $project_space->project_creation_date = Carbon::now(); // Set current date

            // Check if the current user's ID exists in the workspace_admins table
            $workspaceAdmin = DB::table('workspace_admins')->where('user_id', $project_owner)->first();

            if ($workspaceAdmin) {
                $project_space->workspace_id = $workspaceAdmin->workspace_id;
            } else {
                $project_space->workspace_id = null; // Set workspace_id to null if user_id doesn't exist
            }

            $email = $request->input('email');
            $leadUser = DB::table('users')->where('email', $email)->first();

            if ($leadUser) {
                $project_space->lead_id = $leadUser->id;
            } else {
                $project_space->lead_id = null; // Set lead_id to null if email doesn't exist
            }

            $project_space->id = Str::uuid()->toString();

            $projectDeadline = Carbon::parse($project_space->project_deadline);
            $projectCompletionDate = Carbon::parse($project_space->project_completion_date);

            // Set overdue field based on completion date and deadline
            if ($projectCompletionDate->greaterThan($projectDeadline)) {
                $project_space->overdue = "true";
            } else {
                $project_space->overdue = "false";
            }

            // Save the project_space instance
            if ($project_space->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'project created',
                ]);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'not created',
                ]);
            }
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => "Invalid Session",
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\project_space  $project_space
     * @return \Illuminate\Http\Response
     */
    public function show(project_space $project_space)
    {
        //
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
    public function update(Request $request, project_space $project_space)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_space  $project_space
     * @return \Illuminate\Http\Response
     */
    public function destroy(project_space $project_space)
    {
        //
    }
}
