<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\work_space;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;


class Work_Space_Controller extends Controller
{
    public function store(Request $request)
    {
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
            $work_space->created_by = $request->input('created_by');
            $work_space->save();
        
            return response()->json([
                'status' => 200,
                'message' => "Added successfully",
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'message' => "Error occurred while saving the workspace",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "An unexpected error occurred",
            ]);
        }
    }

}