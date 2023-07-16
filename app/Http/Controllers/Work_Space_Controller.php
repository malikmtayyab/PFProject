<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\work_space;
use App\Models\workspace_admins;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class Work_Space_Controller extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
            'workspace_name' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Fill all the fields',
            ]);
        }

        $cookie_value = $request->input('userID');
        try {
            $work_space = new Work_Space;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();

                $finalName = date("Y-m-d") . '.' . $filename;
                $file->move('images/', $finalName);
                $work_space->image = 'images/' . $finalName;
            }

            $work_space->id = Str::uuid()->toString();
            $work_space->workspace_name = $request->input('workspace_name');
            $work_space->created_by = $cookie_value;
            $work_space->save();

            $workspaceAdmin = new workspace_admins;
            $workspaceAdmin->id = Str::uuid()->toString();
            $workspaceAdmin->user_id = $cookie_value;
            $workspaceAdmin->workspace_id = $work_space->id;
            $workspaceAdmin->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Added successfully',
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Error occurred while saving the workspace',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An unexpected error occurred',
            ]);
        }
    }
    public function show(Request $request)
    {
        try {
            $userID = $request->input('userID');
            $result = workspace_admins::select('*')
                ->join('invitation_tables as it', 'workspace_admins.user_id', '=', 'it.invited_by')
                ->join('users as u', 'it.id', '=', 'u.id')
                ->where('workspace_admins.user_id', $userID)
                ->get();

            if ($result->isNotEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'workspace_members' => $result
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'failure',
                    'workspace_members' => $result
                ], Response::HTTP_NO_CONTENT);
            }
        } catch (QueryException $e) {
            // Handle the exception
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving workspace members.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
