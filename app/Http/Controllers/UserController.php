<?php

namespace App\Http\Controllers;

use App\Models\project_space;
use App\Models\project_tasks;
use App\Models\User;
use App\Models\workspace_admins;
use App\Models\work_space;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function addUserName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Fill all the fields',
            ]);
        }
        $cookie_value = $request->input('userID');
        try {
            $user = User::where('id', $cookie_value)->update(['name' => $request->input('user_name')]);

            if ($user) {
                return response()->json([
                    'status' => 'successful'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while updating the user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Fill all the fields',
            ]);
        }
        $cookie_value = $request->input('userID');
        try {
            $user = User::where('id', $cookie_value)->update(['password' => Hash::make($request->input('new_password'))]);

            if ($user) {
                return response()->json([
                    'status' => 'successful'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failed'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while updating the user'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getUserInitialData(Request $request)
    {
        $cookieValue = $request->input('userID');
        $results = work_space::select('work_space.id as workspace_id', 'work_space.workspace_name', 'workspace_admins.user_id', 'invitation_tables.id')
            ->join('workspace_admins', 'work_space.id', '=', 'workspace_admins.workspace_id')
            ->leftJoin('invitation_tables', 'invitation_tables.invited_by', '=', 'workspace_admins.user_id')
            ->where('workspace_admins.user_id', $cookieValue)
            ->orWhere('invitation_tables.id', $cookieValue)
            ->first();

        if (!$results) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No user found.'
            ], Response::HTTP_BAD_REQUEST);
        }

        //Access these using below calls:
        $workspaceName = $results->workspace_name;
        $workspaceId = $results->workspace_id;
        $adminId = $results->user_id;
        $memberId = $results->id;

        if ($adminId) {
            $result = workspace_admins::select('*')
            ->join('invitation_tables as it', 'workspace_admins.user_id', '=', 'it.invited_by')
            ->join('users as u', 'it.id', '=', 'u.id')
            ->where('workspace_admins.user_id', $cookieValue)
            ->get();
            $projects = project_space::select('project_spaces.*', 'owner.name AS project_owner_name', 'lead.name AS lead_name')
                ->join('users AS owner', 'project_spaces.project_owner', '=', 'owner.id')
                ->join('users AS lead', 'project_spaces.lead_id', '=', 'lead.id')
                ->where('project_spaces.workspace_id', $workspaceId)
                ->groupBy('project_spaces.id', 'owner.name', 'lead.name')
                ->get();
            $filteredProjects = $projects->take(10); // storing first ten projects
            $projectTasksArray = [];
            $projectMembersArray = [];

            foreach($filteredProjects  as $project){
                $projectTasks = project_tasks::where('project_id', $project->id)->get();

                $project_members = project_space::select('*')
                    ->join('users AS owner', 'project_spaces.project_owner', '=', 'owner.id')
                    ->join('users AS lead', 'project_spaces.lead_id', '=', 'lead.id')
                    ->join('project_members', 'project_spaces.id', '=', 'project_members.project_id')
                    ->join('users', 'project_members.user_id', '=', 'users.id')
                    ->where('project_spaces.id', $project->id)
                    ->get();

                $projectTasksArray[$project->id] = $projectTasks;
                $projectMembersArray[$project->id] = $project_members;


            }
            Redis::set($cookieValue."@WorkspaceProjects",  json_encode($filteredProjects));
            Redis::set($cookieValue."@WorkspaceProjectsTasks",  json_encode($projectTasksArray));
            Redis::set($cookieValue."@WorkspaceMembers", json_encode($result));
            Redis::set($cookieValue."@WorkspaceProjectsMembers",  json_encode($projectMembersArray));


            return response()->json([
                'workspace_id' => $workspaceId,
                'workspace_name' => $workspaceName,
                'admin_id' => $adminId,
                'member_id' => null,
                'projects_information' => $projects,
            ]);
        } else if ($memberId) {
            $projects = project_space::select('project_spaces.*', 'owner.name AS project_owner_name', 'lead.name AS lead_name', 'users.name AS member_name')
                ->join('users AS owner', 'project_spaces.project_owner', '=', 'owner.id')
                ->join('users AS lead', 'project_spaces.lead_id', '=', 'lead.id')
                ->join('project_members', 'project_spaces.id', '=', 'project_members.project_id')
                ->join('users', 'project_members.user_id', '=', 'users.id')
                ->where('project_spaces.workspace_id', $workspaceId)
                ->where('project_members.user_id', $memberId)
                ->groupBy('project_spaces.id', 'owner.name', 'lead.name', 'users.name')
                ->get();
                $filteredProjects = $projects->take(10);
                $projectTasksArray = [];
                $projectMembersArray = [];

                foreach($filteredProjects  as $project){
                    $projectTasks = project_tasks::select("*")
                    ->join('task_assignments as TA', 'TA.task_id','=','project_tasks.id')
                    ->where('project_tasks.id', $project->id)->get()
                    ->Where('TA.assigned_id', $memberId)
                    ->get();

                    $project_members = project_space::select('*')
                    ->join('users AS owner', 'project_spaces.project_owner', '=', 'owner.id')
                    ->join('users AS lead', 'project_spaces.lead_id', '=', 'lead.id')
                    ->join('project_members', 'project_spaces.id', '=', 'project_members.project_id')
                    ->join('users', 'project_members.user_id', '=', 'users.id')
                    ->where('project_spaces.id', $project->id)
                    ->get();
                    $projectTasksArray[$project->id] = $projectTasks;
                    $projectMembersArray[$project->id] = $project_members;
                }
                Redis::set($cookieValue."@MemberProjects",  json_encode($filteredProjects));
                Redis::set($cookieValue."@MemberProjectsTasks",  json_encode($projectTasksArray));
                Redis::set($cookieValue."@MemberProjectsMembers",  json_encode($projectMembersArray));


            return response()->json([
                'workspace_id' => $workspaceId,
                'workspace_name' => $workspaceName,
                'admin_id' => null,
                'member_id' => $memberId,
                'projects_information' => $projects,
            ]);
        }
    }
}
