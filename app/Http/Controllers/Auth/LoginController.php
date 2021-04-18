<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use App\User;
use DB;
use Auth;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(){
        return 'username';
    }

    public function redirectTo() {
        return '/messages';
    }

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

        // if ($request->remember_me) {
        //     $token->expires_at = \Carbon\Carbon::now()->addWeeks(1); //1  week expiration
        // }

        $token->save(); //eloquent
      //  $user = User::with('UserRoles')->where('id', Auth::user()->id)->first();
        $user = User::find(Auth::user()->id);
        $user->is_online = 1;
        $user->save();

        return response(['error' => false, 'data' => Auth::user()->id, 'access_token' => $accessToken->accessToken]);

    }
}
