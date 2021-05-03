<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TimeTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskControllerStudent extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getTasks()
    {
        return Task::where('assigned_id', Auth::user()->id)->get()->toArray();
    } 

    public function getTimeLogs()
    {
        return TimeTable::where('user_id', Auth::user()->id)->get()->toArray();
    }

    public function editTask(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'task_status' => 'required',
            'task_id' => 'required|exists:tasks,id'
        ]);

        if($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()
            ], 422);
        }

        $task = Task::find($request->task_id);

        DB::beginTransaction();
        
        try 
        {
            $task->status = $request->task_status;
            $task->update();

        }catch(\Exception $e) {
            Log::critical($e);
            return response()->json([
                'success' => false,
                'message' => 'Error occured'
            ], 500);
            DB::rollBack();
        }


        DB::commit();

        return response()->json([
            'success' => true,
            'message' => $task->task_name . ' was successfully added'
        ]);
    }

    public function toggleStamp()
    {
        $time = TimeTable::where('user_id', Auth::user()->id)->latest()->first();

        $new_time = [];
        DB::beginTransaction();

        try {

            if(!$time) {
            
                $new_time = TimeTable::create([
                    'status_stamp' => 1,
                    'user_id' => Auth::user()->id
                ]);
                
            }else {
                $new_time = TimeTable::create([
                    'status_stamp' => ($time->status_stamp == 1 ? 2 : 1),
                    'user_id' => Auth::user()->id
                ]);
            }
            

        } catch (\Exception $e) {
            Log::critical($e);
            return response()->json([
                'success' => false,
                'message' => 'Error occured'
            ], 500);
            DB::rollBack();
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => $new_time
        ]);
    }

    public function getLastStamp() 
    {
        return TimeTable::where('user_id', Auth::user()->id)->latest()->first() ?? [];
    }
     
}
