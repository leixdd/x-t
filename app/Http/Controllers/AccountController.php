<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function createNewAdmin(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'fullname' => 'required|alpha',
            'username' => 'required|alpha_num|unique:users',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                //->uncompromised(),
            ]
        ]);

        if($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()
            ], 422);
        }

        DB::beginTransaction();

        try{
            User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 0,
                'user_img' => $request->user_img
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
            'message' => $request->username . ' was successfully added'
        ]);
    }

    public function editAdmin(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'fullname' => 'required|string',
            'id' => 'required|numeric|exists:users',
            'email' => 'required|email'
        ]);

        if($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()
            ], 422);
        }

        $user = User::where('id', $request->id)->first();

        if($user->email !== $request->email) {
            if(Validator::make($request->all(), [
                'email' => 'email|unique:users'
            ])->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation->errors()
                ], 422);
            }
        }

        DB::beginTransaction();

        try{

            User::where('id', $request->id)->update([
                'fullname' => $request->fullname,
                'email' => $request->email
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
            'message' => $request->username . ' was successfully edited'
        ]);
    }

    public function deleteAdmin($id) {
        DB::beginTransaction();
        try{
            $user = User::where('id', $id)->first();
            $user->delete();

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
            'message' => $user->username . ' was successfully deleted'
        ]);
    }

    public function getAdmins(){
        return User::where('role', 0)->get()->toArray();
    }

    public function getStudents(){
        return User::with('info')->where('role', 1)->get()->toArray();
    }

    public function createNewStudent(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username' => 'required|alpha_num|unique:users',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                //->uncompromised(),
            ],
            'fname' => 'required|alpha',
            'lname' => 'required|alpha',
            'school' => 'required|string',
            'gender' => 'required|string|in:Male,Female',
            'address' => 'required|string',
            'phone' => 'required|string',
        ], [
            'fname' => 'Firstname',
            'lname' => 'Lastname',
            'mname' => 'Middlename',
            'phone' => 'Contact Number'
        ]);

        if($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()
            ], 422);
        }

        DB::beginTransaction();

        try{
            $user = User::create([
                'fullname' => $request->fname . ' ' . ($request->mname ?? '') . ' ' . $request->lname,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 1,
                'user_img' => $request->user_img
            ]);

            UserInfo::create([
                'user_id' => $user->id,
                'fname' => $request->fname,
                'mname' => ($request->mname ?? ''),
                'lname' => $request->lname,
                'school' => $request->school,
                'gender' => $request->gender,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone
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
            'message' => $request->username . ' was successfully added'
        ]);
    }

    public function editStudent(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:users',
            'email' => 'required|email',
            'fname' => 'required|alpha',
            'lname' => 'required|alpha',
            'school' => 'required|string',
            'gender' => 'required|string|in:Male,Female',
            'address' => 'required|string',
            'phone' => 'required|string',
        ], [
            'fname' => 'Firstname',
            'lname' => 'Lastname',
            'mname' => 'Middlename',
        ]);

        if($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()
            ], 422);
        }

        $user = User::where('id', $request->id)->first();

        if($user->email !== $request->email) {
            if(Validator::make($request->all(), [
                'email' => 'email|unique:users'
            ])->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation->errors()
                ], 422);
            }
        }


        DB::beginTransaction();

        try{

            User::where('id', $request->id)->update([
                'fullname' => $request->fname . ' ' . ($request->mname ?? '') . ' ' . $request->lname,
                'email' => $request->email
            ]);

            UserInfo::where('user_id', $request->id)->update([
                'fname' => $request->fname,
                'mname' => ($request->mname ?? ''),
                'lname' => $request->lname,
                'school' => $request->school,
                'gender' => $request->gender,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone
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
            'message' => $request->username . ' was successfully edited'
        ]);
    }

    public function deleteStudent($id) {
        DB::beginTransaction();
        try{
            $user = User::where('id', $id)->first();
            $user->delete();

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
            'message' => $user->username . ' was successfully deleted'
        ]);
    }
}
