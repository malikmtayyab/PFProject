<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\work_space;
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
            'created_by' => 'required',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Fill all the fields',
            ]);
        }
    
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
    public function show()
    {
        // $user = new User;


        $items= Work_Space::get();
        return response()->json([
            'items' => $items,
        ]);

        // $items= installment::get();
        // return response()->json([
        //     'items' => $items,
        // ]);
    }
}