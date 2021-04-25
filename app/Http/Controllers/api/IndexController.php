<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
class IndexController extends Controller
{

    public function getOnlineUsers(){
        return User::where([
            ['is_online', "=", 1],
            ['id', '<>', Auth::user()->id]
        ])->get();
    }

    public function getOfflineUsers(){
        return User::where('is_online', 0)->get();
    }

    public function getUser($username){
        return User::where('username', $username)->first();
    }
}
