<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use DB;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $user_data = array(
            'username' => $request->get('username'),
            'password' => $request->get('password')
        );

        if(!Auth::attempt($user_data))
        {
            return response(['error' => true, 'data' => 'Incorrect Username or Password']);
        }

        $accessToken = $request->user()->createToken('authToken');
        
        $token = $accessToken->token;

        if ($request->remember_me) {
            $token->expires_at = \Carbon\Carbon::now()->addWeeks(1); //1  week expiration
        }

        $token->save();
        $user = User::find(Auth::user()->id);
        $user->is_online = 1;
        $user->save();

        return response(['error' => false, 'data' => Auth::user(), 'access_token' => $accessToken->accessToken]);
    }
    
}
