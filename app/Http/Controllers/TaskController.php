<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TimeTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getTasks($id)
    {
        return Task::where('assigned_id', $id)->get()->toArray();
    } 

    public function getTimeLogs($id)
    {
        return TimeTable::where('user_id', $id)->get()->toArray();
    } 

    public function addTask(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'task_name' => 'required|string',
            'assigned_id' => 'required|exists:users,id'
        ]);

        if($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()
            ], 422);
        }

        DB::beginTransaction();

        try{

            Task::create([
                'task_name' => $request->task_name,
                'assigned_id' => $request->assigned_id,
                'status' => 1
            ]);

        }catch(\Throwable $ex) {
            Log::critical($ex);
            return response()->json([
                'success' => false,
                'message' => 'Error occured'
            ], 500);
            DB::rollBack();
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => $request->task_name . ' was successfully added'
        ]);
    }

    public function editTask(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'task_name' => 'required|string',
            'assigned_id' => 'required|exists:users'
        ]);

        if($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()
            ], 422);
        }
    }

    public function deleteTask($id)
    {
        DB::beginTransaction();
        try{
            $task = Task::where('id', $id)->first();
            $task->delete();

        }catch(\Throwable $ex) {
            Log::critical($ex);
            return response()->json([
                'success' => false,
                'message' => 'Error occured'
            ], 500);
            DB::rollBack();
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => $task->task_name . ' was successfully deleted'
        ]);
    }
}
