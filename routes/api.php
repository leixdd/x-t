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
  Route::get('/online-users', [App\Http\Controllers\api\IndexController::class, 'getOnlineUsers']);
  Route::get('/offline-users', [App\Http\Controllers\api\IndexController::class, 'getOfflineUsers']);
  Route::get('/user/{username}', [App\Http\Controllers\api\IndexController::class, 'getUser']);
});


Route::post('login', [App\Http\Controllers\api\AuthController::class, 'login'])->name('dologin');
Route::get('login', [App\Http\Controllers\WebController::class, 'main'])->name('login');