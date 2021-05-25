<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
  Route::get('/user-data', [App\Http\Controllers\WebController::class, 'UserData']);
  // Route::get('/online-users', [App\Http\Controllers\api\IndexController::class, 'getOnlineUsers']);
  // Route::get('/offline-users', [App\Http\Controllers\api\IndexController::class, 'getOfflineUsers']);
  Route::get('/user/{username}', [App\Http\Controllers\api\IndexController::class, 'getUser']);

  Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::post('new-admin', [App\Http\Controllers\AccountController::class, 'createNewAdmin']);
    Route::post('edit-admin', [App\Http\Controllers\AccountController::class, 'editAdmin']);
    Route::post('delete-admin/{id}', [App\Http\Controllers\AccountController::class, 'deleteAdmin']);
    Route::get('getAdmins', [App\Http\Controllers\AccountController::class, 'getAdmins']);
  });

  Route::group(['prefix' => 'students', 'middleware' => 'admin'], function () {
    Route::post('new-student', [App\Http\Controllers\AccountController::class, 'createNewStudent']);
    Route::post('edit-student', [App\Http\Controllers\AccountController::class, 'editStudent']);
    Route::post('delete-student/{id}', [App\Http\Controllers\AccountController::class, 'deleteStudent']);
    Route::get('getStudents', [App\Http\Controllers\AccountController::class, 'getStudents']);
    Route::get('getArchivedStudents', [App\Http\Controllers\AccountController::class, 'getArchivedStudents']);
    Route::get('tasks/{id}', [App\Http\Controllers\TaskController::class, 'getTasks']);
    Route::get('time_logs/{id}', [App\Http\Controllers\TaskController::class, 'getTimeLogs']);
    Route::post('delete-task/{id}', [App\Http\Controllers\TaskController::class, 'deleteTask']);
    Route::post('addTask', [App\Http\Controllers\TaskController::class, 'addTask']);
  });

  Route::group(['prefix' => 'student', 'middleware' => 'student'], function () {
    Route::get('tasks', [App\Http\Controllers\TaskControllerStudent::class, 'getTasks']);
    Route::get('time_logs', [App\Http\Controllers\TaskControllerStudent::class, 'getTimeLogs']);
    Route::post('editTask', [App\Http\Controllers\TaskControllerStudent::class, 'editTask']);
    Route::post('toggleStamp', [App\Http\Controllers\TaskControllerStudent::class, 'toggleStamp']);
    Route::get('getLastStamp', [App\Http\Controllers\TaskControllerStudent::class, 'getLastStamp']);
  });

});


Route::post('login', [App\Http\Controllers\api\AuthController::class, 'login'])->name('dologin');
Route::get('login', [App\Http\Controllers\WebController::class, 'main'])->name('login');